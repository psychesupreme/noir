<div class="min-h-screen bg-[#070708] text-white py-6 px-4 font-sans">
    <div class="max-w-md mx-auto space-y-6">

        <!-- Mobile Header Bar -->
        <div class="flex items-center justify-between border-b border-neutral-800 pb-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders') }}" class="p-2 rounded-full bg-neutral-900 text-neutral-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#C5A880] block font-bold">Courier Dispatch Portal</span>
                    <h1 class="text-lg font-serif font-bold text-white tracking-wide">Order #NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider 
                    {{ $order->status === 'delivered' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/10 text-amber-400 border border-amber-500/30' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $order->status === 'delivered' ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse' }} mr-1.5"></span>
                    {{ strtoupper($order->status) }}
                </span>
            </div>
        </div>

        @if($successMessage)
            <div class="p-4 rounded-xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs font-mono space-y-1 flex items-start space-x-3 shadow-lg">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <span class="font-bold block">Delivery Confirmed</span>
                    <span>{{ $successMessage }}</span>
                </div>
            </div>
        @endif

        <!-- Recipient Details Card -->
        <div class="bg-[#0D0D10] border border-neutral-850 rounded-2xl p-5 space-y-4 shadow-xl">
            <div class="flex items-center justify-between border-b border-neutral-800/80 pb-3">
                <span class="text-[10px] font-mono uppercase tracking-widest text-neutral-450 font-bold">Recipient Handover Target</span>
                @if($order->is_gift)
                    <span class="px-2 py-0.5 rounded text-[9px] font-mono uppercase font-bold bg-[#C5A880]/15 text-[#C5A880] border border-[#C5A880]/30">✦ Luxury Gift Delivery</span>
                @endif
            </div>

            <div class="space-y-2">
                <h2 class="text-base font-semibold text-white">
                    {{ $order->is_gift && $order->recipient_name ? $order->recipient_name : ($order->client?->contact_name ?? 'Valued Client') }}
                </h2>

                @php
                    $phone = $order->is_gift && $order->recipient_phone ? $order->recipient_phone : ($order->client?->phone ?? null);
                @endphp

                @if($phone)
                    <div class="flex items-center space-x-2 pt-1">
                        <a href="tel:{{ $phone }}" class="flex-1 flex items-center justify-center space-x-2 bg-[#C5A880] text-black font-semibold text-xs py-2.5 px-4 rounded-xl hover:bg-[#d6b991] transition-all shadow-md active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.001 1.001 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>Call Recipient ({{ $phone }})</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Address Details -->
            <div class="pt-3 border-t border-neutral-800/80 space-y-1.5">
                <span class="text-[10px] font-mono uppercase tracking-widest text-neutral-500 block">Destination Address & Coordinates</span>
                <p class="text-xs text-neutral-200 leading-relaxed font-light">
                    {{ $order->client?->delivery_address ?? 'Address provided upon dispatch.' }}
                </p>
                <div class="flex items-center space-x-2 pt-1 text-[11px] font-mono text-[#C5A880]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Region: {{ $order->client?->region ?? 'Nairobi' }} &bull; Branch: {{ $order->branch?->name ?? 'Main Atelier' }}</span>
                </div>
            </div>
        </div>

        <!-- Curation Manifest Card -->
        <div class="bg-[#0D0D10] border border-neutral-850 rounded-2xl p-5 space-y-4 shadow-xl">
            <span class="text-[10px] font-mono uppercase tracking-widest text-neutral-450 block font-bold border-b border-neutral-800/80 pb-2">Order Items Manifest</span>
            
            <div class="divide-y divide-neutral-850">
                @foreach($order->products as $product)
                    <div class="py-3 flex justify-between items-center text-xs">
                        <div>
                            <span class="font-semibold text-white block">{{ $product->name }}</span>
                            <span class="text-[10px] font-mono text-neutral-500">
                                Sizing: {{ strtoupper($product->pivot->size ?? 'standard') }} &bull; Qty: {{ $product->pivot->quantity }}
                            </span>
                        </div>
                        <span class="font-mono text-neutral-300 font-semibold">Ksh {{ number_format($product->pivot->price * $product->pivot->quantity) }}</span>
                    </div>
                @endforeach
            </div>

            @if($order->special_instructions)
                <div class="p-3 bg-neutral-900/80 border border-neutral-800 rounded-xl space-y-1">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-amber-400 block font-bold">Special Packaging / Lettering Notes:</span>
                    <p class="text-xs font-light text-neutral-300 leading-normal">{{ $order->special_instructions }}</p>
                </div>
            @endif
        </div>

        <!-- Proof of Delivery Form Card -->
        <div class="bg-[#0D0D10] border border-neutral-850 rounded-2xl p-5 space-y-5 shadow-xl">
            <span class="text-[10px] font-mono uppercase tracking-widest text-[#C5A880] block font-bold border-b border-neutral-800/80 pb-2">Proof of Delivery (PoD) Protocol</span>

            @if($order->status === 'delivered' && $order->pod_photo_path)
                <!-- Existing Photo Display for Delivered Orders -->
                <div class="space-y-3">
                    <span class="text-xs text-emerald-400 font-mono block">✔ Photo Proof Verified & Uploaded</span>
                    <div class="relative rounded-xl overflow-hidden border border-emerald-500/30 max-h-64 bg-black">
                        <img src="{{ Storage::disk('public')->url($order->pod_photo_path) }}" alt="Proof of Delivery Photo" class="w-full h-full object-cover">
                    </div>
                    @if($order->delivered_at)
                        <span class="text-[11px] font-mono text-neutral-400 block">Delivered Time: {{ $order->delivered_at->format('d M Y, h:i A') }}</span>
                    @endif
                </div>
            @else
                <!-- Active Photo Upload Form -->
                <form wire:submit.prevent="markAsDelivered" class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-neutral-300 block">Capture / Upload PoD Photo *</label>
                        
                        <div class="relative border-2 border-dashed border-neutral-750 hover:border-[#C5A880]/60 rounded-2xl p-4 text-center bg-black/40 transition-colors">
                            <input 
                                type="file" 
                                wire:model="photo" 
                                accept="image/*" 
                                capture="environment" 
                                id="pod-photo-input" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            >
                            
                            @if ($photo)
                                <div class="space-y-2">
                                    <div class="w-full h-40 rounded-xl overflow-hidden bg-black border border-neutral-700">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-xs font-mono text-emerald-400 block">Photo ready for upload</span>
                                </div>
                            @else
                                <div class="space-y-2 py-2">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-[#C5A880]/10 flex items-center justify-center text-[#C5A880]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <span class="text-xs font-medium text-white block">Tap to Take Photo / Choose File</span>
                                    <span class="text-[10px] font-mono text-neutral-500 block">Supports direct camera capture on mobile</span>
                                </div>
                            @endif
                        </div>
                        @error('photo') <span class="text-[11px] font-mono text-rose-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-neutral-300 block">Courier Notes / Handover Observations</label>
                        <textarea 
                            wire:model="courier_notes" 
                            rows="3" 
                            placeholder="e.g. Received by reception desk officer, wax card sealed..." 
                            class="w-full bg-[#070708] border border-neutral-800 rounded-xl px-3 py-2 text-xs text-white placeholder-neutral-600 focus:outline-none focus:border-[#C5A880]"
                        ></textarea>
                        @error('courier_notes') <span class="text-[11px] font-mono text-rose-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-[#C5A880] to-[#E5C9A0] text-black font-semibold text-sm hover:opacity-95 transition-all shadow-lg active:scale-98 flex items-center justify-center space-x-2 disabled:opacity-50"
                    >
                        <span wire:loading.remove>Confirm & Mark as Delivered</span>
                        <span wire:loading class="flex items-center space-x-2">
                            <svg class="animate-spin h-4 w-4 text-black" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Processing PoD Upload...</span>
                        </span>
                    </button>
                </form>
            @endif
        </div>

    </div>
</div>
