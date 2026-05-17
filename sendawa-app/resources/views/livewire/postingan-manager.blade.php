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
        <h1 class="hero-title">Ungkapkan Opinimu!</h1>
        <p class="hero-subtitle">Tiada pembungkaman disini</p>

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
         MODAL — Konfirmasi Hapus dengan Password
         ====================================================== --}}
    @if($showDeleteModal)
    <div class="modal-overlay">
        <div class="modal-box modal-box--sm">

            <div class="modal-header modal-header--danger">
                <h2>🗑️ Hapus Postingan</h2>
                <button class="modal-close" wire:click="cancelDelete" title="Batal">✕</button>
            </div>

            <div class="modal-body">
                <p class="modal-desc">
                    Kamu akan menghapus postingan:<br>
                    <strong style="color:#e0d0ff;">"{{ $deletePostTitle }}"</strong>
                </p>
                <p class="modal-desc" style="margin-top:8px;color:#f87171;font-size:0.82rem;">
                    ⚠️ Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="form-group" style="margin-top:20px;">
                    <label for="d-password">Masukkan Password Admin</label>
                    <div class="password-input-wrap">
                        <input
                            id="d-password"
                            type="password"
                            wire:model="deletePassword"
                            class="form-input {{ $errors->has('deletePassword') ? 'input--error' : '' }}"
                            placeholder="••••••••"
                            wire:keydown.enter="executeDelete" />
                        <span class="password-lock-icon">🔒</span>
                    </div>
                    @error('deletePassword')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="modal-actions">
                    <button wire:click="cancelDelete" class="btn-cancel">
                        Batal
                    </button>
                    <button wire:click="executeDelete" class="btn-danger" wire:loading.attr="disabled" wire:target="executeDelete">
                        <span wire:loading.remove wire:target="executeDelete">🗑️ Hapus Sekarang</span>
                        <span wire:loading wire:target="executeDelete">⏳ Menghapus...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endif


    {{-- ======================================================
         MODAL — Edit Postingan dengan Password
         ====================================================== --}}
    @if($showEditModal)
    <div class="modal-overlay">
        <div class="modal-box">

            <div class="modal-header modal-header--edit">
                <h2>✏️ Edit Postingan</h2>
                <button class="modal-close" wire:click="cancelEdit" title="Batal">✕</button>
            </div>

            <div class="modal-body">
                <form wire:submit="executeEdit" enctype="multipart/form-data">

                    {{-- Judul --}}
                    <div class="form-group">
                        <label for="e-title">Judul Postingan</label>
                        <input
                            id="e-title"
                            type="text"
                            wire:model="editTitle"
                            class="form-input {{ $errors->has('editTitle') ? 'input--error' : '' }}"
                            placeholder="Judul postingan..." />
                        @error('editTitle')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Isi Postingan --}}
                    <div class="form-group">
                        <label for="e-content">Isi Postingan</label>
                        <textarea
                            id="e-content"
                            wire:model="editContent"
                            class="form-textarea {{ $errors->has('editContent') ? 'input--error' : '' }}"
                            placeholder="Isi postingan..."></textarea>
                        @error('editContent')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Upload Foto Baru --}}
                    <div class="form-group">
                        <label>Foto</label>

                        {{-- Preview foto lama --}}
                        @if($editOldImage && !$editImage)
                        <div class="edit-photo-preview">
                            <img src="{{ Storage::url($editOldImage) }}" alt="Foto saat ini" />
                            <p class="edit-photo-caption">📸 Foto saat ini — upload baru untuk mengganti</p>
                        </div>
                        @endif

                        <label class="upload-area" for="e-image" style="margin-top: {{ $editOldImage && !$editImage ? '10px' : '0' }};">
                            @if($editImage)
                                <div class="upload-icon">🖼️</div>
                                <p style="color:#a855f7;font-weight:600;margin-top:4px;">{{ $editImage->getClientOriginalName() }}</p>
                                <p style="margin-top:4px;">Klik untuk ganti gambar</p>
                            @else
                                <div class="upload-icon">📷</div>
                                <p>{{ $editOldImage ? 'Klik untuk ganti foto' : 'Klik atau seret foto ke sini' }}</p>
                                <p style="margin-top:4px;font-size:0.75rem;color:#4a4a70;">PNG, JPG, WEBP · Maks 2 MB</p>
                            @endif
                            <input id="e-image" type="file" wire:model="editImage" accept="image/*" />
                        </label>
                        @error('editImage')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <div wire:loading wire:target="editImage" style="font-size:0.8rem;color:#a855f7;margin-top:6px;">
                            ⏳ Mengunggah gambar...
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="modal-divider">
                        <span>🔑 Konfirmasi Admin</span>
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="e-password">Password Admin</label>
                        <div class="password-input-wrap">
                            <input
                                id="e-password"
                                type="password"
                                wire:model="editPassword"
                                class="form-input {{ $errors->has('editPassword') ? 'input--error' : '' }}"
                                placeholder="Masukkan password untuk menyimpan perubahan" />
                            <span class="password-lock-icon">🔒</span>
                        </div>
                        @error('editPassword')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="modal-actions">
                        <button type="button" wire:click="cancelEdit" class="btn-cancel">
                            Batal
                        </button>
                        <button type="submit" class="btn-submit-edit" wire:loading.attr="disabled" wire:target="executeEdit">
                            <span wire:loading.remove wire:target="executeEdit">💾 Simpan Perubahan</span>
                            <span wire:loading wire:target="executeEdit">⏳ Menyimpan...</span>
                        </button>
                    </div>

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
                        onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 16px rgba(139,92,246,0.4)'">>
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

                {{-- Footer: waktu + edit + hapus --}}
                <div class="postingan-card-footer">
                    <span class="postingan-time">🕐 {{ $post->created_at->diffForHumans() }}</span>
                    <div class="postingan-card-actions">
                        <button
                            wire:click="openEdit({{ $post->id }})"
                            class="btn-edit"
                            title="Edit postingan">
                            Edit
                        </button>
                        <button
                            wire:click="confirmDelete({{ $post->id }})"
                            class="btn-delete"
                            title="Hapus postingan">
                            Hapus
                        </button>
                    </div>
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
