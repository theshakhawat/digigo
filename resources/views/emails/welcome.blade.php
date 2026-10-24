<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ setting('site_name', 'DigiGo') }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px 15px; color: #1e293b; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 36px 24px; text-align: center; color: #ffffff;">
            <a href="{{ url('/') }}" target="_blank" style="text-decoration: none; display: inline-block;">
                <img src="{{ default_site_logo() }}" alt="{{ setting('site_name', 'DigiGo') }}" style="max-height: 48px; max-width: 200px; object-fit: contain; margin-bottom: 12px; background: rgba(255, 255, 255, 0.15); padding: 6px 14px; border-radius: 8px;">
            </a>
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Welcome to {{ setting('site_name', 'DigiGo') }}! 🎉</h1>
            <p style="margin: 8px 0 0 0; font-size: 13px; opacity: 0.9;">Bangladesh's Premier Marketplace for Digital & Physical Products</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 32px 28px;">
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px;">
                Hello {{ $user->name }},
            </h2>
            
            <p style="margin-bottom: 16px; color: #475569; font-size: 14px; line-height: 1.7;">
                Thank you for joining <strong>{{ setting('site_name', 'DigiGo') }}</strong>! Your account has been registered successfully. We are excited to have you in our community. Visit our online storefront today and discover authentic digital software tools, subscription keys, electronics, and lifestyle products.
            </p>

            <!-- Store Visit CTA -->
            <div style="text-align: center; margin: 26px 0 32px 0;">
                <a href="{{ url('/') }}" target="_blank" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; padding: 13px 32px; font-size: 14px; font-weight: 700; text-decoration: none; border-radius: 8px; display: inline-block; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);">
                    🛍️ Start Shopping on DigiGo
                </a>
            </div>

            <!-- Digital Products Showcase (2 Products) -->
            @if(isset($digitalProducts) && $digitalProducts->isNotEmpty())
                <div style="margin-top: 32px; margin-bottom: 24px;">
                    <div style="border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 16px;">
                        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #2563eb;">⚡ Popular Digital Tools & Subscriptions</span>
                    </div>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            @foreach($digitalProducts as $index => $prod)
                                <td width="48%" valign="top" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; {{ $index === 0 ? 'padding-right: 14px;' : 'padding-left: 14px;' }}">
                                    @if($prod->thumbnail_url)
                                        <div style="text-align: center; margin-bottom: 10px;">
                                            <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->name }}" style="max-height: 110px; max-width: 100%; border-radius: 6px; object-fit: contain;">
                                        </div>
                                    @endif
                                    <div style="font-size: 11px; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 4px;">Digital Key</div>
                                    <h4 style="margin: 0 0 6px 0; font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.4; height: 36px; overflow: hidden;">
                                        {{ Str::limit($prod->name, 45) }}
                                    </h4>
                                    <div style="margin-bottom: 10px;">
                                        <span style="font-size: 15px; font-weight: 800; color: #0f172a;">৳{{ number_format($prod->effective_price, 2) }}</span>
                                        @if($prod->sale_price && $prod->sale_price < $prod->price)
                                            <span style="font-size: 11px; color: #94a3b8; text-decoration: line-through; margin-left: 4px;">৳{{ number_format($prod->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('product.show', $prod->slug) }}" target="_blank" style="display: block; text-align: center; background-color: #ffffff; color: #2563eb; border: 1px solid #2563eb; font-size: 12px; font-weight: 700; padding: 6px 10px; border-radius: 6px; text-decoration: none;">
                                        View Details →
                                    </a>
                                </td>
                                @if($index === 0 && $digitalProducts->count() > 1)
                                    <td width="4%"></td>
                                @endif
                            @endforeach
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Physical Products Showcase (2 Products) -->
            @if(isset($physicalProducts) && $physicalProducts->isNotEmpty())
                <div style="margin-top: 24px; margin-bottom: 24px;">
                    <div style="border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 16px;">
                        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #059669;">📦 Top Trending Physical Products</span>
                    </div>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            @foreach($physicalProducts as $index => $prod)
                                <td width="48%" valign="top" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                    @if($prod->thumbnail_url)
                                        <div style="text-align: center; margin-bottom: 10px;">
                                            <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->name }}" style="max-height: 110px; max-width: 100%; border-radius: 6px; object-fit: contain;">
                                        </div>
                                    @endif
                                    <div style="font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; margin-bottom: 4px;">Gadgets & Physical</div>
                                    <h4 style="margin: 0 0 6px 0; font-size: 13px; font-weight: 700; color: #0f172a; line-height: 1.4; height: 36px; overflow: hidden;">
                                        {{ Str::limit($prod->name, 45) }}
                                    </h4>
                                    <div style="margin-bottom: 10px;">
                                        <span style="font-size: 15px; font-weight: 800; color: #0f172a;">৳{{ number_format($prod->effective_price, 2) }}</span>
                                        @if($prod->sale_price && $prod->sale_price < $prod->price)
                                            <span style="font-size: 11px; color: #94a3b8; text-decoration: line-through; margin-left: 4px;">৳{{ number_format($prod->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('product.show', $prod->slug) }}" target="_blank" style="display: block; text-align: center; background-color: #ffffff; color: #059669; border: 1px solid #059669; font-size: 12px; font-weight: 700; padding: 6px 10px; border-radius: 6px; text-decoration: none;">
                                        Order Now →
                                    </a>
                                </td>
                                @if($index === 0 && $physicalProducts->count() > 1)
                                    <td width="4%"></td>
                                @endif
                            @endforeach
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Assistance Note -->
            <div style="background-color: #f1f5f9; border-radius: 8px; padding: 14px 18px; margin-top: 24px; font-size: 12px; color: #64748b; line-height: 1.5;">
                Need help or have questions regarding our items or payment methods? Reach our 24/7 hotline <strong>{{ setting('topbar_support_phone', '+8809611678088') }}</strong> or visit your account dashboard anytime.
            </div>
        </div>

        <!-- Footer & Unsubscribe -->
        <div style="background-color: #f8fafc; padding: 24px 20px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
            <p style="margin: 0 0 6px 0;">© {{ date('Y') }} {{ setting('site_name', 'DigiGo') }}. All rights reserved.</p>
            <p style="margin: 0 0 14px 0;">Dhaka, Bangladesh</p>

            @if(!empty($subscriber?->uuid))
                <p style="margin: 0; font-size: 11px; color: #94a3b8; border-top: 1px dashed #cbd5e1; pt-3; padding-top: 12px;">
                    Don't want to receive promotional emails from DigiGo? 
                    <a href="{{ route('newsletter.unsubscribe', $subscriber->uuid) }}" target="_blank" style="color: #64748b; text-decoration: underline; font-weight: 600;">
                        Unsubscribe from this list
                    </a>
                </p>
            @endif
        </div>

    </div>
</body>
</html>
