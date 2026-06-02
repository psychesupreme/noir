<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\CrmTimelineLog;
use App\Models\Deal;
use App\Models\Order;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CrmManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user to perform actions
        $this->admin = User::factory()->create([
            'account_tier' => UserRole::Admin,
        ]);

        // Create a client with a linked user
        $clientUser = User::factory()->create([
            'account_tier' => UserRole::Retail,
        ]);
        $this->client = Client::create([
            'user_id' => $clientUser->id,
            'contact_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0712345678',
            'region' => 'Nairobi',
            'delivery_address' => '123 Atelier Way',
        ]);
    }

    public function test_admin_can_manage_b2b_deals_via_livewire(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\ClientIndex::class)
            ->set('selectedClientId', $this->client->id)
            ->call('openCreateDealModal')
            ->assertSet('showDealModal', true)
            ->set('dealTitle', 'Riverside Lobby Curation')
            ->set('dealStage', 'proposal')
            ->set('dealValue', 25000)
            ->set('dealClosedAt', '2026-07-01 12:00:00')
            ->call('saveDeal')
            ->assertHasNoErrors()
            ->assertSet('showDealModal', false);

        $this->assertDatabaseHas('deals', [
            'client_id' => $this->client->id,
            'title' => 'Riverside Lobby Curation',
            'stage' => 'proposal',
            'deal_value' => 25000,
        ]);

        $deal = Deal::first();

        // Test editing the deal
        Livewire::test(\App\Livewire\Admin\ClientIndex::class)
            ->set('selectedClientId', $this->client->id)
            ->call('openEditDealModal', $deal->id)
            ->assertSet('isEditingDeal', true)
            ->assertSet('dealTitle', 'Riverside Lobby Curation')
            ->set('dealStage', 'won')
            ->call('saveDeal')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('deals', [
            'id' => $deal->id,
            'stage' => 'won',
        ]);

        // Test deleting the deal
        Livewire::test(\App\Livewire\Admin\ClientIndex::class)
            ->set('selectedClientId', $this->client->id)
            ->call('confirmDeleteDeal', $deal->id)
            ->assertSet('showDeleteDealModal', true)
            ->call('deleteDeal')
            ->assertSet('showDeleteDealModal', false);

        $this->assertDatabaseMissing('deals', ['id' => $deal->id]);
    }

    public function test_admin_can_manage_crm_timeline_logs_via_livewire(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Admin\ClientIndex::class)
            ->set('selectedClientId', $this->client->id)
            ->set('logEventType', 'call')
            ->set('logDescription', 'Discussed weekly hotel arrangements with client.')
            ->call('saveTimelineLog')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('crm_timeline_logs', [
            'client_id' => $this->client->id,
            'user_id' => $this->admin->id,
            'event_type' => 'call',
            'description' => 'Discussed weekly hotel arrangements with client.',
        ]);

        $log = CrmTimelineLog::first();

        // Delete timeline log
        Livewire::test(\App\Livewire\Admin\ClientIndex::class)
            ->set('selectedClientId', $this->client->id)
            ->call('deleteTimelineLog', $log->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('crm_timeline_logs', ['id' => $log->id]);
    }

    public function test_timeline_compiler_combines_and_orders_events_correctly(): void
    {
        $this->actingAs($this->admin);

        // 1. Create an Order (Oldest)
        $order = Order::create([
            'client_id' => $this->client->id,
            'total_amount' => 15000,
            'status' => 'delivered',
        ]);
        $order->created_at = now()->subDays(3);
        $order->save();

        // 2. Create Loyalty Transaction (Middle)
        $loyalty = LoyaltyTransaction::create([
            'user_id' => $this->client->user_id,
            'points' => 150,
            'type' => 'earn',
            'description' => 'Points earned from order',
        ]);
        $loyalty->created_at = now()->subDays(2);
        $loyalty->save();

        // 3. Create CRM Timeline Log (Newest)
        $log = CrmTimelineLog::create([
            'client_id' => $this->client->id,
            'user_id' => $this->admin->id,
            'event_type' => 'meeting',
            'description' => 'Completed alignment review meeting.',
        ]);
        $log->created_at = now()->subDay();
        $log->save();

        // Run component and check timelineEvents computed output
        Livewire::test(\App\Livewire\Admin\ClientIndex::class)
            ->set('selectedClientId', $this->client->id)
            ->assertViewHas('timelineEvents', function ($timelineEvents) {
                $this->assertCount(3, $timelineEvents);

                // Assert chronological order (newest first)
                $this->assertEquals('crm_log', $timelineEvents[0]['type']);
                $this->assertEquals('users', $timelineEvents[0]['icon']);
                $this->assertStringContainsString('alignment review meeting', $timelineEvents[0]['description']);

                $this->assertEquals('loyalty', $timelineEvents[1]['type']);
                $this->assertStringContainsString('150 points', $timelineEvents[1]['description']);

                $this->assertEquals('order', $timelineEvents[2]['type']);
                $this->assertStringContainsString('Gross Amount: Ksh 15,000', $timelineEvents[2]['description']);
                
                return true;
            });
    }
}
