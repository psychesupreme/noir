<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\Mail\OrderReceiptMail;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_approval_queues_receipt_email()
    {
        Mail::fake();
        Queue::fake();

        // 1. Create client and order
        $user = User::factory()->create();
        $client = Client::create([
            'user_id' => $user->id,
            'contact_name' => 'Jane Doe',
            'email' => 'jane.doe@receipt-test.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => 'Test Address',
        ]);

        $branch = Branch::updateOrCreate(
            ['code' => 'NB-TEST-EMAIL'],
            [
                'name' => 'Test Branch',
                'location_city' => 'Nairobi',
                'is_active' => true,
            ]
        );

        $order = Order::create([
            'client_id' => $client->id,
            'branch_id' => $branch->id,
            'total_amount' => 12000,
            'service_fee_amount' => 1500,
            'status' => 'pending',
        ]);

        // 2. Approve the order via OrderService
        app(OrderService::class)->approve($order);

        // 3. Assert order status changed
        $order->refresh();
        $this->assertEquals('approved', $order->status);

        // 4. Assert OrderReceiptMail was queued to correct address
        Mail::assertQueued(OrderReceiptMail::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id &&
                   $mail->hasTo('jane.doe@receipt-test.com');
        });
    }
}
