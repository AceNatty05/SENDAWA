{{-- resources/views/livewire/review-manager.blade.php --}}
<div>
    {{-- ===== HERO SECTION ===== --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl bg-gradient-to-r from-violet-400 via-fuchsia-400 to-pink-400 bg-clip-text text-transparent">
            Suaramu, Tanpa Identitas
        </h1>
        <p class="mt-3 text-slate-400 max-w-xl mx-auto text-base sm:text-lg">
            Tulis review jujurmu — anonim, bebas, dan tanpa perlu daftar.
        </p>
    </div>

    {{-- ===== FORM CARD ===== --}}
    @if($showForm)
    <div class="mb-8 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm p-6 shadow-xl shadow-black/30"
         x-data x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-semibold text-slate-100">✍️ Tulis Review Baru</h2>
            <button wire:click="toggleForm"
                    class="rounded-lg p-1.5 text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form wire:submit="submitReview" enctype="multipart/form-data">
            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-slate-300 mb-1.5">Judul Review</label>
                <input  id="title"
                        type="text"
                        wire:model="title"
                        placeholder="Cth: Pengalaman Saya di Warung Nasi Pak Budi..."
                        class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-slate-100 placeholder-slate-500
                               focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" />
                @error('title')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Review Content --}}
            <div class="mb-4">
                <label for="review_content" class="block text-sm font-medium text-slate-300 mb-1.5">Isi Review</label>
                <textarea   id="review_content"
                            wire:model="review_content"
                            rows="5"
                            placeholder="Ceritakan pengalamanmu secara detail..."
                            class="w-full rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-slate-100 placeholder-slate-500
                                   focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all resize-none">
                </textarea>
                @error('review_content')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image Upload --}}
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-slate-300 mb-1.5">Foto (opsional)</label>

                <label for="image"
                       class="flex flex-col items-center justify-center w-full h-32 rounded-xl border-2 border-dashed border-slate-700
                              cursor-pointer bg-slate-800 hover:border-violet-500 hover:bg-slate-800/80 transition-all group">
                    @if($image)
                        <span class="text-sm text-violet-400 font-medium">✅ {{ $image->getClientOriginalName() }}</span>
                        <span class="text-xs text-slate-500 mt-1">Klik untuk ganti</span>
                    @else
                        <svg class="h-8 w-8 text-slate-500 group-hover:text-violet-400 transition-colors mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-slate-400">Klik atau seret foto ke sini</span>
                        <span class="text-xs text-slate-500 mt-1">PNG, JPG, WEBP • Maks 2MB</span>
                    @endif
                    <input id="image" type="file" wire:model="image" accept="image/*" class="hidden" />
                </label>

                @error('image')
                    <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                @enderror
                <div wire:loading wire:target="image" class="mt-2 text-xs text-violet-400">⏳ Mengunggah gambar...</div>
            </div>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 px-6 py-3 text-sm font-semibold text-white
                           shadow-lg hover:from-violet-500 hover:to-fuchsia-500 hover:shadow-violet-500/25 transition-all duration-200
                           disabled:opacity-60 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="submitReview">🚀 Kirim Review</span>
                <span wire:loading wire:target="submitReview">⏳ Mengirim...</span>
            </button>
        </form>
    </div>
    @endif

    {{-- ===== ACTION BAR: Tombol Tulis + Search ===== --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        @if(!$showForm)
        <button wire:click="toggleForm"
                class="flex-shrink-0 flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 px-5 py-2.5 text-sm font-semibold text-white
                       shadow-lg hover:from-violet-500 hover:to-fuchsia-500 hover:shadow-violet-500/30 transition-all duration-200">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tulis Review
        </button>
        @endif

        {{-- Search Bar --}}
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input  id="search"
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Cari review berdasarkan judul..."
                    class="w-full rounded-xl border border-slate-700 bg-slate-800 pl-10 pr-4 py-2.5 text-slate-100 placeholder-slate-500
                           focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all" />
            @if($search)
            <button wire:click="$set('search', '')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session()->has('success'))
    <div class="mb-5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- ===== REVIEW COUNT ===== --}}
    <div class="mb-5 text-sm text-slate-500">
        @if($search)
            Menampilkan <span class="text-violet-400 font-medium">{{ $reviews->total() }}</span> hasil untuk "<span class="text-slate-300">{{ $search }}</span>"
        @else
            <span class="text-violet-400 font-medium">{{ $reviews->total() }}</span> review ditemukan
        @endif
    </div>

    {{-- ===== REVIEW GRID ===== --}}
    @if($reviews->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="mb-4 text-6xl">{{ $search ? '🔍' : '📭' }}</div>
            <p class="text-lg font-medium text-slate-400">
                {{ $search ? 'Tidak ada review yang cocok.' : 'Belum ada review. Jadilah yang pertama!' }}
            </p>
            @if(!$showForm && !$search)
            <button wire:click="toggleForm"
                    class="mt-5 rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-violet-500 transition-colors">
                Tulis Sekarang
            </button>
            @endif
        </div>
    @else
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($reviews as $review)
            <article wire:key="review-{{ $review->id }}"
                     class="group relative flex flex-col rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden
                            hover:border-violet-500/50 hover:shadow-xl hover:shadow-violet-500/10 transition-all duration-300">

                {{-- Image --}}
                @if($review->image_path)
                <div class="aspect-video w-full overflow-hidden bg-slate-800">
                    <img src="{{ Storage::url($review->image_path) }}"
                         alt="Gambar review: {{ $review->title }}"
                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy" />
                </div>
                @else
                <div class="aspect-video w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                    <span class="text-4xl opacity-30">💬</span>
                </div>
                @endif

                {{-- Content --}}
                <div class="flex flex-col flex-1 p-5">
                    <div class="mb-1 flex items-center gap-2">
                        <span class="inline-block h-2 w-2 rounded-full bg-violet-500"></span>
                        <time class="text-xs text-slate-500">
                            {{ $review->created_at->diffForHumans() }}
                        </time>
                    </div>
                    <h3 class="mt-1 text-base font-semibold text-slate-100 leading-snug line-clamp-2 group-hover:text-violet-300 transition-colors">
                        {{ $review->title }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed line-clamp-4 flex-1">
                        {{ $review->review_content }}
                    </p>

                    {{-- Delete --}}
                    <button wire:click="deleteReview({{ $review->id }})"
                            wire:confirm="Yakin ingin menghapus review ini?"
                            class="mt-4 self-end rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-red-500/10 hover:text-red-400 transition-colors border border-transparent hover:border-red-500/20">
                        Hapus
                    </button>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($reviews->hasPages())
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
        @endif
    @endif
</div>
