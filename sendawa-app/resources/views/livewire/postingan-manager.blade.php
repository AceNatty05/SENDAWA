{{-- resources/views/livewire/postingan-manager.blade.php --}}
<div>

    {{-- ======================================================
         NAVBAR
         ====================================================== --}}
    <nav class="sendawa-navbar">
        <div class="navbar-left">
            <div class="brand-avatar">S</div>
            <a href="/" class="sendawa-brand">SENDAWA</a>
        </div>
        <span class="anonim-badge">100% Anonim</span>
    </nav>


    {{-- ======================================================
         HERO SECTION
         ====================================================== --}}
    <div class="hero">
        <h1 class="hero-title">Suaramu, Tanpa Identitas</h1>
        <p class="hero-subtitle">Tulis postingan jujurmu — anonim, bebas, dan tanpa perlu daftar.</p>

        <div class="hero-actions">
            {{-- Tombol Tulis Postingan --}}
            <button wire:click="toggleForm" class="btn-new-post">
                + &nbsp;Tulis Postingan
            </button>

            {{-- Search Bar --}}
            <div class="hero-search-wrap">
                <span class="search-icon">🔍</span>
                <input
                    id="search"
                    type="search"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Cari postingan berdasarkan judul..." />
                @if($search)
                    <button wire:click="$set('search', '')" class="clear-btn" title="Hapus pencarian">✕</button>
                @endif
            </div>
        </div>

        {{-- Jumlah Postingan --}}
        <p class="post-count-text">
            <strong>{{ $posts->total() }}</strong> postingan ditemukan
        </p>
    </div>


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
                <form wire:submit="submitPostingan" enctype="multipart/form-data">

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
                                <p style="color:#a855f7;font-weight:600;margin-top:4px;">{{ $image->getClientOriginalName() }}</p>
                                <p style="margin-top:4px;">Klik untuk ganti gambar</p>
                            @else
                                <div class="upload-icon">📷</div>
                                <p>Klik atau seret foto ke sini</p>
                                <p style="margin-top:4px;font-size:0.75rem;color:#4a4a70;">PNG, JPG, WEBP · Maks 2 MB</p>
                            @endif
                            <input id="f-image" type="file" wire:model="image" accept="image/*" />
                        </label>
                        @error('image')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <div wire:loading wire:target="image" style="font-size:0.8rem;color:#a855f7;margin-top:6px;">
                            ⏳ Mengunggah gambar...
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitPostingan">🚀 Kirim Postingan</span>
                        <span wire:loading wire:target="submitPostingan">⏳ Mengirim...</span>
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
    <div style="max-width:1280px;margin:0 auto;padding:0 20px;">
        <div class="flash-success">
            ✅ {{ session('success') }}
        </div>
    </div>
    @endif

    {{-- Search result info --}}
    @if($search)
    <div style="text-align:center;margin-bottom:16px;">
        <div class="search-result-info">
            🔍 <strong>{{ $posts->total() }}</strong> hasil untuk "<em>{{ $search }}</em>"
        </div>
    </div>
    @endif

    {{-- ======================================================
         POST GRID — Masonry
         ====================================================== --}}
    @if($posts->isEmpty())
        <div class="empty-state">
            <div class="empty-box">
                <div style="font-size:3.5rem;margin-bottom:16px;">{{ $search ? '🔍' : '📭' }}</div>
                <p style="font-size:1.05rem;font-weight:700;color:#d0d0f0;margin:0 0 6px;">
                    {{ $search ? 'Tidak ada postingan yang cocok.' : 'Belum ada postingan. Jadilah yang pertama!' }}
                </p>
                @if(!$showForm && !$search)
                <button wire:click="toggleForm"
                        style="margin-top:20px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border:none;border-radius:12px;padding:12px 32px;font-weight:700;cursor:pointer;font-size:0.9rem;letter-spacing:0.3px;transition:transform 0.18s,box-shadow 0.18s;box-shadow:0 4px 16px rgba(139,92,246,0.4);"
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(139,92,246,0.55)'"
                        onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 16px rgba(139,92,246,0.4)'">
                    ✏️ Tulis Sekarang
                </button>
                @endif
            </div>
        </div>

    @else
        <div class="postingan-masonry">
            @foreach($posts as $post)
            <article wire:key="post-{{ $post->id }}" class="postingan-card">

                {{-- Header: Judul --}}
                <div class="postingan-card-header">
                    {{ $post->title }}
                </div>

                {{-- Foto (jika ada) --}}
                @if($post->image_path)
                <div class="postingan-card-photo">
                    <img src="{{ Storage::url($post->image_path) }}"
                         alt="Foto: {{ $post->title }}"
                         loading="lazy" />
                </div>
                @endif

                {{-- Isi Postingan --}}
                <div class="postingan-card-body">
                    {{ $post->content }}
                </div>

                {{-- Footer: waktu + hapus --}}
                <div class="postingan-card-footer">
                    <span class="postingan-time">🕐 {{ $post->created_at->diffForHumans() }}</span>
                    <button
                        wire:click="deletePostingan({{ $post->id }})"
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
