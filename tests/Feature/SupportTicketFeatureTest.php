<?php

namespace Tests\Feature;

use App\Mail\TicketReplyMail;
use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SupportTicketFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_open_ticket_with_multiple_attachments_and_admin_is_notified(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'customer@example.com',
        ]);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'customer_name' => 'Customer Test',
            'customer_email' => $user->email,
            'customer_phone' => '01712345678',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'street_address' => 'Mirpur, Dhaka',
            'subtotal' => 1000,
            'delivery_charge' => 50,
            'total' => 1050,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
        ]);

        $file1 = UploadedFile::fake()->create('screenshot.png', 500, 'image/png');
        $file2 = UploadedFile::fake()->create('invoice.pdf', 300, 'application/pdf');

        $response = $this->actingAs($user)
            ->post(route('user.tickets.store'), [
                'department' => 'refund',
                'priority' => 'high',
                'order_id' => $order->id,
                'subject' => 'Requesting refund for damaged item',
                'message' => 'The item received is not working as expected. Please refund.',
                'attachments' => [$file1, $file2],
            ]);

        $response->assertRedirect();

        // 1. Assert ticket created in database
        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'department' => 'refund',
            'priority' => 'high',
            'subject' => 'Requesting refund for damaged item',
            'status' => 'open',
        ]);

        // 2. Assert ticket message thread started
        $ticket = SupportTicket::where('user_id', $user->id)->first();
        $this->assertNotNull($ticket);
        $this->assertCount(2, $ticket->attachments);
        $this->assertDatabaseHas('ticket_messages', [
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'is_admin' => false,
        ]);

        // 3. Assert admin notification created
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'ticket',
            'is_read' => false,
        ]);
    }

    public function test_admin_can_reply_to_ticket_with_attachments_and_user_gets_notified_and_emailed(): void
    {
        Mail::fake();
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'email' => 'client@example.com']);

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $user->id,
            'department' => 'technical',
            'priority' => 'medium',
            'status' => 'open',
            'subject' => 'Need help resetting password',
            'message' => 'I cannot log in to the subscription account.',
            'last_reply_at' => now(),
        ]);

        $replyFile = UploadedFile::fake()->create('guide.pdf', 200, 'application/pdf');

        $response = $this->actingAs($admin)
            ->post(route('admin.tickets.reply', $ticket->id), [
                'message' => 'We have refreshed your credentials. Please follow the attached guide.',
                'status' => 'answered',
                'attachments' => [$replyFile],
            ]);

        $response->assertRedirect();

        // 1. Assert ticket status updated to answered
        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'answered',
        ]);

        // 2. Assert staff reply record stored with is_admin true
        $this->assertDatabaseHas('ticket_messages', [
            'support_ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'is_admin' => true,
        ]);

        // 3. Assert customer received in-app notification
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'type' => 'ticket',
            'is_read' => false,
        ]);

        // 4. Assert customer received reply email
        Mail::assertQueued(TicketReplyMail::class, function ($mail) use ($user, $ticket) {
            return $mail->hasTo($user->email) && $mail->ticket->id === $ticket->id;
        });
    }

    public function test_user_can_reply_back_and_close_ticket(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $user->id,
            'department' => 'general',
            'priority' => 'low',
            'status' => 'answered',
            'subject' => 'Question about renewal',
            'message' => 'Will it renew automatically?',
            'last_reply_at' => now(),
        ]);

        // User replies back
        $response = $this->actingAs($user)
            ->post(route('user.tickets.reply', $ticket->id), [
                'message' => 'Thanks, that explains it! You can close this.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'open',
        ]);

        // User closes the ticket
        $closeResponse = $this->actingAs($user)
            ->post(route('user.tickets.close', $ticket->id));

        $closeResponse->assertRedirect();
        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'closed',
        ]);
    }
}
