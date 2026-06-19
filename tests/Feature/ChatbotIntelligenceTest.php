<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatbotIntelligenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_responds_to_secret_admirer_queries(): void
    {
        $component = Livewire::test(\App\Livewire\Storefront::class);

        $component->set('chatMessage', 'tell me about secret admirer delivery')
            ->call('sendChatMessage');

        $history = $component->get('chatHistory');
        $lastBotMsg = end($history);
        $this->assertStringContainsString('Secret Admirer Delivery', $lastBotMsg['text']);
        $this->assertStringContainsString('Uniformed Concierge Drop-off', $lastBotMsg['text']);
    }

    public function test_chatbot_responds_to_occasion_queries(): void
    {
        $component = Livewire::test(\App\Livewire\Storefront::class);

        $component->set('chatMessage', 'how do you style anniversary flowers?')
            ->call('sendChatMessage');

        $history = $component->get('chatHistory');
        $lastBotMsg = end($history);
        $this->assertStringContainsString('Birthday Celebration', $lastBotMsg['text']);
        $this->assertStringContainsString('Anniversary & Love', $lastBotMsg['text']);
        $this->assertStringContainsString('Sympathy & Comfort', $lastBotMsg['text']);
    }

    public function test_chatbot_responds_to_b2b_credit_queries(): void
    {
        $component = Livewire::test(\App\Livewire\Storefront::class);

        $component->set('chatMessage', 'do you support net 30 payment?')
            ->call('sendChatMessage');

        $history = $component->get('chatHistory');
        $lastBotMsg = end($history);
        $this->assertStringContainsString('net 30 payment options', $lastBotMsg['text']);
        $this->assertStringContainsString('KRA PIN', $lastBotMsg['text']);
    }

    public function test_chatbot_responds_to_sourcing_queries(): void
    {
        $component = Livewire::test(\App\Livewire\Storefront::class);

        $component->set('chatMessage', 'where do you source your flowers?')
            ->call('sendChatMessage');

        $history = $component->get('chatHistory');
        $lastBotMsg = end($history);
        $this->assertStringContainsString('Naivasha and Limuru', $lastBotMsg['text']);
        $this->assertStringContainsString('volcanic soil', $lastBotMsg['text']);
    }

    public function test_chatbot_responds_to_extended_loyalty_queries(): void
    {
        $component = Livewire::test(\App\Livewire\Storefront::class);

        $component->set('chatMessage', 'what are the gold loyalty tiers?')
            ->call('sendChatMessage');

        $history = $component->get('chatHistory');
        $lastBotMsg = end($history);
        $this->assertStringContainsString('Bronze', $lastBotMsg['text']);
        $this->assertStringContainsString('Gold', $lastBotMsg['text']);
        $this->assertStringContainsString('calligraphy', $lastBotMsg['text']);
    }
}
