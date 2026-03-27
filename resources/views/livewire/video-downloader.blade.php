<div class="space-y-6">
    {{-- URL Girişi --}}
    <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
        <h2 class="text-lg font-semibold mb-4 text-gray-100">🔗 Video URL</h2>

        <div class="flex gap-3">
            <input
                type="url"
                wire:model="url"
                wire:keydown.enter="fetchInfo"
                placeholder="YouTube, fullhdfilmizlesene, hdfilmizle veya herhangi bir site..."
                class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 transition"
            />
            <button
                wire:click="fetchInfo"
                wire:loading.attr="disabled"
                class="bg-blue-600 hover:bg-blue-500 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-semibold transition"
            >
                <span wire:loading.remove wire:target="fetchInfo">Getir</span>
                <span wire:loading wire:target="fetchInfo">⏳ Yükleniyor...</span>
            </button>
        </div>

        @error('url')
            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Hata Mesajı --}}
    @if($error)
        <div class="bg-red-900/30 border border-red-700 rounded-xl p-4 text-red-300">
            ⚠️ {{ $error }}
        </div>
    @endif

    {{-- Video Bilgisi --}}
    @if(!empty($videoInfo))
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
            <h2 class="text-lg font-semibold mb-4 text-gray-100">📋 Video Bilgisi</h2>

            <div class="flex gap-4">
                @if(!empty($videoInfo['thumbnail']))
                    <img src="{{ $videoInfo['thumbnail'] }}" class="w-40 h-24 object-cover rounded-lg flex-shrink-0" alt="Thumbnail">
                @endif
                <div class="flex-1">
                    <p class="font-semibold text-white text-lg">{{ $videoInfo['title'] }}</p>
                    @if($isPlaylist)
                        <p class="text-gray-400 text-sm mt-1">📋 Playlist — {{ $videoInfo['count'] }} video</p>
                    @elseif(!empty($videoInfo['uploader']))
                        <p class="text-gray-400 text-sm mt-1">👤 {{ $videoInfo['uploader'] }}</p>
                    @endif
                </div>
            </div>

            {{-- Format & Kalite --}}
            @if(!$isPlaylist && !empty($videoInfo['formats']))
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-gray-400 text-sm block mb-2">Format</label>
                        <select wire:model="selectedFormat" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                            <option value="mp4">MP4 (Video)</option>
                            <option value="mp3">MP3 (Ses)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-gray-400 text-sm block mb-2">Kalite</label>
                        <select wire:model="selectedQuality" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                            @foreach($videoInfo['formats'] as $fmt)
                                <option value="{{ $fmt['value'] }}">{{ $fmt['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <button
                wire:click="startDownload"
                class="mt-4 w-full bg-green-600 hover:bg-green-500 text-white py-3 rounded-xl font-semibold transition"
            >
                ⬇️ İndirmeyi Başlat
            </button>
        </div>
    @endif
</div>

<div>
    {{-- Simplicity is an acquired taste. - Katharine Gerould --}}
</div>