{{-- resources/views/livewire/review-manager.blade.php --}}
<div>

    {{-- ======================================================
         NAVBAR  (di sini supaya wire:model & wire:click bekerja)
         ====================================================== --}}
    <nav class="sendawa-navbar">
        <a href="/" class="sendawa-brand">SENDAWA</a>

        {{-- Badge jumlah postingan --}}
        <span class="post-count-badge">
            {{ $posts->total() }}
        </span>

        <div class="sendawa-search">
            <input
                id="search"
                type="search"
                wire:model.live.debounce.400ms="search"
                placeholder="Cari postingan..." />
            @if($search)
                <button wire:click="$set('search', '')" class="clear-btn" title="Hapus pencarian">✕</button>
            @endif
        </div>

        {{-- Spacer: dorong tombol ke ujung kanan --}}
        <div class="sendawa-spacer"></div>

        {{-- Toggle Tema --}}
        <button id="theme-toggle-btn" class="theme-toggle" onclick="toggleTheme()" title="Ganti tema">🌙</button>

        {{-- New Post --}}
        <button wire:click="toggleForm" class="sendawa-new-post">
            ✏️ NEW POST
        </button>
    </nav>


    {{-- ======================================================
         MODAL — Form Postingan Baru
         ====================================================== --}}
    @if($showForm)
    <div class="modal-overlay">
        <div class="modal-box">

            <div class="modal-header">
                <h2>✍️ Tulis Postingan Baru</h2>
                <button class="modal-close" wire:click="toggleForm" title="Tutup">✕</button>
            </div>

            <div class="modal-body">
                <form wire:submit="submitReview" enctype="multipart/form-data">

                    {{-- Judul --}}
                    <div class="form-group">
                        <label for="f-title">Judul Postingan</label>
                        <input
                            id="f-title"
                            type="text"
                            wire:model="title"
                            class="form-input"
                            placeholder="Cth: Pengalaman Saya di Warung Nasi Pak Budi..." />
                        @error('title')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Isi Postingan --}}
                    <div class="form-group">
                        <label for="f-content">Isi Postingan</label>
                        <textarea
                            id="f-content"
                            wire:model="content"
                            class="form-textarea"
                            placeholder="Ceritakan sesuatu secara anonim..."></textarea>
                        @error('content')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload Foto --}}
                    <div class="form-group">
                        <label>Foto (opsional)</label>
                        <label class="upload-area" for="f-image">
                            @if($image)
                                <div class="upload-icon">🖼️</div>
                                <p style="color:#3b5998;font-weight:600;margin-top:4px;">{{ $image->getClientOriginalName() }}</p>
                                <p style="margin-top:4px;">Klik untuk ganti gambar</p>
                            @else
                                <div class="upload-icon">📷</div>
                                <p>Klik atau seret foto ke sini</p>
                                <p style="margin-top:4px;font-size:0.75rem;color:#aab;">PNG, JPG, WEBP · Maks 2 MB</p>
                            @endif
                            <input id="f-image" type="file" wire:model="image" accept="image/*" />
                        </label>
                        @error('image')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <div wire:loading wire:target="image" style="font-size:0.8rem;color:#3b5998;margin-top:6px;">
                            ⏳ Mengunggah gambar...
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitReview">🚀 Kirim Postingan</span>
                        <span wire:loading wire:target="submitReview">⏳ Mengirim...</span>
                    </button>

                </form>
            </div>

        </div>
    </div>
    @endif

    {{-- ======================================================
         FLASH MESSAGE
         ====================================================== --}}
    @if(session()->has('success'))
    <div class="flash-success">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- Search result info (hanya tampil saat ada pencarian) --}}
    @if($search)
    <div class="search-result-info">
        🔍 <strong>{{ $posts->total() }}</strong> hasil untuk "<em>{{ $search }}</em>"
    </div>
    @endif

    {{-- ======================================================
         POST GRID — Masonry
         ====================================================== --}}
    @if($posts->isEmpty())
        <div class="empty-state">
            <div class="empty-box">
                <div style="font-size:3.5rem;margin-bottom:12px;">{{ $search ? '🔍' : '📭' }}</div>
                <p style="font-size:1.05rem;font-weight:700;color:#fff;text-shadow:0 1px 4px rgba(0,40,80,0.4);margin:0 0 8px;">
                    {{ $search ? 'Tidak ada postingan yang cocok.' : 'Belum ada postingan. Jadilah yang pertama!' }}
                </p>
                @if(!$showForm && !$search)
                <button wire:click="toggleForm"
                        style="margin-top:16px;background:rgba(0,80,160,0.75);backdrop-filter:blur(6px);color:#fff;border:1.5px solid rgba(255,255,255,0.4);border-radius:10px;padding:10px 28px;font-weight:700;cursor:pointer;font-size:0.9rem;transition:background 0.2s,transform 0.15s;"
                        onmouseover="this.style.background='rgba(0,119,182,0.9)';this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='rgba(0,80,160,0.75)';this.style.transform='none'">
                    ✏️ Tulis Sekarang
                </button>
                @endif
            </div>
        </div>

    @else
        <div class="review-masonry">
            @foreach($posts as $post)
            <article wire:key="post-{{ $post->id }}" class="review-card">

                {{-- Header: Judul --}}
                <div class="review-card-header">
                    {{ $post->title }}
                </div>

                {{-- Foto (jika ada) --}}
                @if($post->image_path)
                <div class="review-card-photo">
                    <img src="{{ Storage::url($post->image_path) }}"
                         alt="Foto: {{ $post->title }}"
                         loading="lazy" />
                </div>
                @endif

                {{-- Isi Postingan --}}
                <div class="review-card-body">
                    {{ $post->content }}
                </div>

                {{-- Footer: waktu + hapus --}}
                <div class="review-card-footer">
                    <span class="review-time">🕐 {{ $post->created_at->diffForHumans() }}</span>
                    <button
                        wire:click="deleteReview({{ $post->id }})"
                        wire:confirm="Yakin ingin menghapus postingan ini?"
                        class="btn-delete">
                        Hapus
                    </button>
                </div>

            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
        <div class="pagination-wrap">
            {{ $posts->links() }}
        </div>
        @endif
    @endif

</div>
