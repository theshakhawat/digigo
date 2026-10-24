<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class DgePayService
{
    protected string $clientId;

    protected string $clientSecret;

    protected string $apiKey;

    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.dgepay.client_id', '6dfa6e6e66d040b68648afaa6a95979e');
        $this->clientSecret = config('services.dgepay.client_secret', 'b6dc62fbfe887c52');
        $this->apiKey = config('services.dgepay.client_api_key', '8d89041569146d8b15b1c39b79f5070');
        $this->baseUrl = rtrim(config('services.dgepay.base_url', 'https://api-uat.dgepay.net/dipon/v3'), '/');
    }

    /**
     * Authenticate with DGePay and obtain a JWT access token.
     *
     * @return array{success: bool, access_token?: string, message?: string}
     */
    public function authenticate(): array
    {
        try {
            $basicAuth = base64_encode($this->clientId.':'.$this->clientSecret);

            $ch = curl_init($this->baseUrl.'/payment_gateway/authenticate');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic '.$basicAuth,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $data = json_decode((string) $response, true) ?? [];

            if ($httpCode < 200 || $httpCode >= 300 || empty($data['data']['access_token'])) {
                Log::warning('DGePay authentication failed', ['http_code' => $httpCode, 'response' => $data]);

                return [
                    'success' => false,
                    'message' => $data['error'][0] ?? $data['message'] ?? 'DGePay authentication failed.',
                ];
            }

            return [
                'success' => true,
                'access_token' => $data['data']['access_token'],
            ];
        } catch (Exception $e) {
            Log::error('DGePay authentication exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Connection to DGePay gateway failed: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Initiate payment transaction on DGePay gateway.
     *
     * @param  array  $paymentData  {
     *
     * @type float $amount Required
     * @type string $orderId Required
     * @type string $redirectUrl Required
     * @type string $description Optional
     * @type array $meta_data Optional
     *             }
     *
     * @return array{success: bool, payment_url?: string, transaction_id?: string, message?: string}
     */
    public function initiatePayment(array $paymentData): array
    {
        try {
            $auth = $this->authenticate();
            if (! $auth['success']) {
                return $auth;
            }

            $payload = [
                'amount' => (float) ($paymentData['amount'] ?? 0),
                'customer_token' => null,
                'note' => (string) ($paymentData['description'] ?? 'Order Payment'),
                'payee_information' => null,
                'payment_method' => null,
                'redirect_url' => (string) $paymentData['redirectUrl'],
                'unique_txn_id' => (string) $paymentData['orderId'],
                'meta_data' => $paymentData['meta_data'] ?? [
                    'custom_field_1' => 'digigo_order',
                    'custom_field_2' => (string) ($paymentData['orderId'] ?? ''),
                    'custom_field_3' => 'bdt',
                ],
                'unique_user_reference' => (string) ($paymentData['unique_user_reference'] ?? 'customer'),
            ];

            $signature = $this->generateSignature($payload);
            $encryptedBody = $this->encryptPayload($payload);

            $ch = curl_init($this->baseUrl.'/payment_gateway/initiate_payment');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encryptedBody);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$auth['access_token'],
                'Signature: '.$signature,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $data = json_decode((string) $response, true) ?? [];

            if ($httpCode < 200 || $httpCode >= 300 || empty($data['data']['webview_url'])) {
                Log::warning('DGePay initiate payment failed', ['http_code' => $httpCode, 'response' => $data]);

                return [
                    'success' => false,
                    'message' => $data['error'][0] ?? $data['message'] ?? 'Failed to initiate DGePay payment gateway.',
                ];
            }

            return [
                'success' => true,
                'payment_url' => $data['data']['webview_url'],
                'transaction_id' => $data['data']['unique_txn_id'] ?? $paymentData['orderId'],
            ];
        } catch (Exception $e) {
            Log::error('DGePay initiate exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Error initiating payment: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Check transaction status directly server-to-server.
     *
     * @return array{success: bool, data?: array, message?: string}
     */
    public function getTransactionStatus(string $uniqueTxnId): array
    {
        try {
            $auth = $this->authenticate();
            if (! $auth['success']) {
                return $auth;
            }

            $payload = ['unique_txn_id' => $uniqueTxnId];
            $signature = $this->generateSignature($payload);
            $encryptedBody = $this->encryptPayload($payload);

            $ch = curl_init($this->baseUrl.'/payment_gateway/check_transaction_status');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encryptedBody);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer '.$auth['access_token'],
                'Signature: '.$signature,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $data = json_decode((string) $response, true) ?? [];

            if ($httpCode < 200 || $httpCode >= 300) {
                Log::warning('DGePay transaction status check failed', ['txn' => $uniqueTxnId, 'response' => $data]);

                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Failed to check transaction status.',
                ];
            }

            return [
                'success' => true,
                'data' => $data['data'] ?? $data,
            ];
        } catch (Exception $e) {
            Log::error('DGePay getTransactionStatus exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Status check connection error: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Decrypt callback data string received from DGePay.
     */
    public function decryptCallbackData(string $encryptedData): ?array
    {
        try {
            // Restore space to plus if query parameter decoding modified it
            $fixed = str_replace(' ', '+', $encryptedData);

            // Attempt 1: direct string base64 decrypt via openssl
            $decrypted = openssl_decrypt($fixed, 'AES-128-ECB', $this->clientSecret, 0);

            // Attempt 2: raw decoded
            if ($decrypted === false) {
                $decoded = base64_decode($fixed, true);
                if ($decoded !== false) {
                    $decrypted = openssl_decrypt($decoded, 'AES-128-ECB', $this->clientSecret, OPENSSL_RAW_DATA);
                }
            }

            if (! $decrypted) {
                return null;
            }

            $parsed = json_decode($decrypted, true);

            return is_array($parsed) ? $parsed : null;
        } catch (Exception $e) {
            Log::warning('DGePay decrypt callback exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Generate HMAC-SHA256 signature according to DGePay API v3 standard.
     */
    public function generateSignature(array $data): string
    {
        $flatString = $this->flattenParams($data);
        $cleaned = str_replace(['{', '}', '"', ':', ' ', ','], '', $flatString);

        $hmac = hash_hmac('sha256', $cleaned, $this->apiKey, true);

        return base64_encode($hmac);
    }

    /**
     * Recursively flatten params into a concatenated string.
     */
    protected function flattenParams(array $data): string
    {
        ksort($data);

        $result = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result .= $key.$this->flattenParams($value);
            } else {
                if (is_null($value)) {
                    $strValue = 'null';
                } elseif (is_bool($value)) {
                    $strValue = $value ? 'true' : 'false';
                } elseif (is_int($value) || is_float($value)) {
                    $strValue = number_format((float) $value, 1, '.', '');
                } else {
                    $strValue = (string) $value;
                }
                $result .= $key.$strValue;
            }
        }

        return $result;
    }

    /**
     * Encrypt payload with AES-128-ECB.
     */
    public function encryptPayload(array $data): string
    {
        $json = json_encode($data);

        return (string) openssl_encrypt($json, 'AES-128-ECB', $this->clientSecret, 0);
    }

    /**
     * Generate unique transaction ID for DGePay.
     */
    public static function generateTransactionId(string $prefix = 'DG'): string
    {
        return $prefix.date('YmdHis').rand(100, 999);
    }
}
