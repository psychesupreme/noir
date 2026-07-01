<?php

namespace Tests\Feature\Admin;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\User;
use App\Mail\CampaignMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_broadcast_sends_emails_to_clients()
    {
        Mail::fake();

        // 1. Create admin user
        $admin = User::factory()->create(['account_tier' => 'admin']);

        // 2. Create clients with emails
        Client::create([
            'contact_name' => 'John Client 1',
            'email' => 'client1@test.com',
            'phone' => '0711111111',
            'region' => 'Nairobi',
            'delivery_address' => 'Test Address 1',
        ]);

        Client::create([
            'contact_name' => 'John Client 2',
            'email' => 'client2@test.com',
            'phone' => '0722222222',
            'region' => 'Mombasa',
            'delivery_address' => 'Test Address 2',
        ]);

        // Client without email should be skipped
        Client::create([
            'contact_name' => 'John Client 3',
            'email' => '',
            'phone' => '0733333333',
            'region' => 'Kisumu',
            'delivery_address' => 'Test Address 3',
        ]);

        // 3. Create a campaign
        $campaign = Campaign::create([
            'title' => 'Luxury Summer Sale',
            'channel' => 'email',
            'subject' => 'Exclusive Luxury Curation Offers',
            'content' => 'Discover our selected luxury flower collections this summer.',
            'status' => 'draft',
        ]);

        // 4. Trigger sending via Livewire component
        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\CampaignIndex::class)
            ->call('triggerSend', $campaign->id);

        // 5. Assert campaign status updated
        $campaign->refresh();
        $this->assertEquals('sent', $campaign->status);
        $this->assertEquals(2, $campaign->sent_count);

        // 6. Assert emails were queued / sent
        Mail::assertQueued(CampaignMail::class, function ($mail) use ($campaign) {
            return $mail->campaign->id === $campaign->id;
        });

        Mail::assertQueuedCount(2);
    }
}
