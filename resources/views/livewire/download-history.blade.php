<div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-100">📥 İndirme Geçmişi</h2>
        <span wire:poll.3s="$refresh" class="text-xs text-gray-500">Otomatik yenileniyor</span>
    </div>

    @if($downloads->isEmpty())
        <div class="px-6 py-12 text-center text-gray-500">
            Henüz indirme yok.
        </div>
    @else
        <div class="divide-y divide-gray-800">
            @foreach($downloads as $dl)
                <div class="px-6 py-4 flex items-center gap-4">
                    {{-- Thumbnail --}}
                    @if($dl->thumbnail)
                        <img src="{{ $dl->thumbnail }}" class="w-16 h-10 object-cover rounded flex-shrink-0">
                    @else
                        <div class="w-16 h-10 bg-gray-800 rounded flex-shrink-0 flex items-center justify-center text-gray-600">🎬</div>
                    @endif

                    {{-- Bilgi --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-medium truncate">{{ $dl->title }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ match($dl->status) {
                                'completed'  => 'bg-green-900 text-green-300',
                                'processing' => 'bg-blue-900 text-blue-300',
                                'failed'     => 'bg-red-900 text-red-300',
                                default      => 'bg-gray-800 text-gray-400',
                            } }}">
                                {{ match($dl->status) {
                                    'completed'  => '✅ Tamamlandı',
                                    'processing' => '⏳ İndiriliyor',
                                    'failed'     => '❌ Hata',
                                    default      => '🕐 Bekliyor',
                                } }}
                            </span>
                            <span class="text-xs text-gray-500">{{ strtoupper($dl->format) }} • {{ $dl->quality }}</span>
                        </div>

                        {{-- Progress bar --}}
                        @if($dl->status === 'processing')
                            <div class="mt-2 bg-gray-800 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full transition-all" style="width: {{ $dl->progress }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $dl->progress }}%</p>
                        @endif
                    </div>

                    {{-- Sil butonu --}}
                    <button wire:click="deleteDownload({{ $dl->id }})" class="text-gray-600 hover:text-red-400 transition text-xl flex-shrink-0">🗑</button>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-3 border-t border-gray-800">
            {{ $downloads->links() }}
        </div>
    @endif
</div>

<div>
    {{-- He who is contented is rich. - Laozi --}}
</div>