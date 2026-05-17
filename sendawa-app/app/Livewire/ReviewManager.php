<?php

namespace App\Livewire;

use App\Models\PasswordAdmin;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ReviewManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    // ---------------------------------------------------------------------------
    // State — Search & Form Baru
    // ---------------------------------------------------------------------------

    /** @var string Kata kunci pencarian (real-time, disinkronisasi ke URL) */
    #[Url(as: 'q')]
    public string $search = '';

    /** @var bool Tampilkan/sembunyikan form buat postingan baru */
    public bool $showForm = false;

    // ---------------------------------------------------------------------------
    // Form Fields — Postingan Baru
    // ---------------------------------------------------------------------------

    public string $title   = '';
    public string $content = '';
    public $image          = null;   // TemporaryUploadedFile | null

    // ---------------------------------------------------------------------------
    // State — Modal Hapus
    // ---------------------------------------------------------------------------

    public bool   $showDeleteModal  = false;
    public ?int   $deleteTargetId   = null;
    public string $deletePassword   = '';
    public string $deletePostTitle  = '';

    // ---------------------------------------------------------------------------
    // State — Modal Edit
    // ---------------------------------------------------------------------------

    public bool   $showEditModal  = false;
    public ?int   $editTargetId   = null;
    public string $editTitle      = '';
    public string $editContent    = '';
    public $editImage             = null;  // TemporaryUploadedFile | null
    public string $editPassword   = '';
    public ?string $editOldImage  = null;  // path foto lama yang sudah tersimpan

    // ---------------------------------------------------------------------------
    // Validation Rules
    // ---------------------------------------------------------------------------

    protected function rules(): array
    {
        return [
            'title'   => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'image'   => 'nullable|image|max:2048',
        ];
    }

    protected array $messages = [
        'title.required'   => 'Judul wajib diisi.',
        'title.min'        => 'Judul minimal 3 karakter.',
        'content.required' => 'Isi postingan wajib diisi.',
        'content.min'      => 'Isi postingan minimal 10 karakter.',
        'image.image'      => 'File harus berupa gambar.',
        'image.max'        => 'Ukuran gambar maksimal 2 MB.',
    ];

    // ---------------------------------------------------------------------------
    // Actions — Form Baru
    // ---------------------------------------------------------------------------

    /**
     * Toggle visibilitas form & reset fields ketika ditutup.
     */
    public function toggleForm(): void
    {
        $this->showForm = ! $this->showForm;

        if (! $this->showForm) {
            $this->resetForm();
        }
    }

    /**
     * Proses submit postingan baru.
     */
    public function submitPostingan(): void
    {
        $this->validate();

        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('images', 'public');
        }

        Post::create([
            'title'      => trim($this->title),
            'content'    => trim($this->content),
            'image_path' => $imagePath,
        ]);

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();

        session()->flash('success', 'Postingan berhasil dikirim!');
    }

    // ---------------------------------------------------------------------------
    // Actions — Hapus dengan Password
    // ---------------------------------------------------------------------------

    /**
     * Buka modal konfirmasi hapus dan simpan ID target.
     */
    public function confirmDelete(int $id): void
    {
        $post = Post::findOrFail($id);

        $this->deleteTargetId  = $id;
        $this->deletePostTitle = $post->title;
        $this->deletePassword  = '';
        $this->showDeleteModal = true;
        $this->resetValidation('deletePassword');
    }

    /**
     * Tutup modal hapus tanpa melakukan apa-apa.
     */
    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteTargetId  = null;
        $this->deletePassword  = '';
        $this->deletePostTitle = '';
        $this->resetValidation('deletePassword');
    }

    /**
     * Verifikasi password lalu hapus postingan.
     */
    public function executeDelete(): void
    {
        $this->validateOnly('deletePassword', [
            'deletePassword' => 'required',
        ], [
            'deletePassword.required' => 'Password wajib diisi.',
        ]);

        if (! $this->checkAdminPassword($this->deletePassword)) {
            $this->addError('deletePassword', 'Password salah. Akses ditolak.');
            return;
        }

        $post = Post::findOrFail($this->deleteTargetId);

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        $this->showDeleteModal = false;
        $this->deleteTargetId  = null;
        $this->deletePassword  = '';
        $this->deletePostTitle = '';

        session()->flash('success', 'Postingan berhasil dihapus.');
    }

    // ---------------------------------------------------------------------------
    // Actions — Edit dengan Password
    // ---------------------------------------------------------------------------

    /**
     * Buka modal edit dan prefill data postingan.
     */
    public function openEdit(int $id): void
    {
        $post = Post::findOrFail($id);

        $this->editTargetId  = $id;
        $this->editTitle     = $post->title;
        $this->editContent   = $post->content;
        $this->editOldImage  = $post->image_path;
        $this->editImage     = null;
        $this->editPassword  = '';
        $this->showEditModal = true;
        $this->resetValidation();
    }

    /**
     * Tutup modal edit tanpa menyimpan.
     */
    public function cancelEdit(): void
    {
        $this->showEditModal = false;
        $this->editTargetId  = null;
        $this->editTitle     = '';
        $this->editContent   = '';
        $this->editOldImage  = null;
        $this->editImage     = null;
        $this->editPassword  = '';
        $this->resetValidation();
    }

    /**
     * Verifikasi password lalu simpan perubahan postingan.
     */
    public function executeEdit(): void
    {
        $this->validate([
            'editTitle'    => 'required|string|min:3|max:255',
            'editContent'  => 'required|string|min:10',
            'editImage'    => 'nullable|image|max:2048',
            'editPassword' => 'required',
        ], [
            'editTitle.required'    => 'Judul wajib diisi.',
            'editTitle.min'         => 'Judul minimal 3 karakter.',
            'editContent.required'  => 'Isi postingan wajib diisi.',
            'editContent.min'       => 'Isi postingan minimal 10 karakter.',
            'editImage.image'       => 'File harus berupa gambar.',
            'editImage.max'         => 'Ukuran gambar maksimal 2 MB.',
            'editPassword.required' => 'Password wajib diisi.',
        ]);

        if (! $this->checkAdminPassword($this->editPassword)) {
            $this->addError('editPassword', 'Password salah. Akses ditolak.');
            return;
        }

        $post = Post::findOrFail($this->editTargetId);

        $imagePath = $post->image_path; // default pakai foto lama

        if ($this->editImage) {
            // Hapus foto lama jika ada
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $imagePath = $this->editImage->store('images', 'public');
        }

        $post->update([
            'title'      => trim($this->editTitle),
            'content'    => trim($this->editContent),
            'image_path' => $imagePath,
        ]);

        $this->showEditModal = false;
        $this->editTargetId  = null;
        $this->editTitle     = '';
        $this->editContent   = '';
        $this->editOldImage  = null;
        $this->editImage     = null;
        $this->editPassword  = '';

        session()->flash('success', 'Postingan berhasil diperbarui!');
    }

    // ---------------------------------------------------------------------------
    // Lifecycle
    // ---------------------------------------------------------------------------

    /**
     * Reset halaman ke 1 setiap kali search berubah.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // ---------------------------------------------------------------------------
    // Render
    // ---------------------------------------------------------------------------

    public function render()
    {
        $posts = Post::query()
            ->when(
                $this->search,
                fn ($q) => $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(9);

        return view('livewire.postingan-manager', [
            'posts' => $posts,
        ])->layout('layouts.app');
    }

    // ---------------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------------

    private function resetForm(): void
    {
        $this->reset(['title', 'content', 'image']);
        $this->resetValidation();
    }

    /**
     * Periksa apakah $plain cocok dengan salah satu password di tabel password_admin.
     */
    private function checkAdminPassword(string $plain): bool
    {
        $adminPasswords = PasswordAdmin::all();

        foreach ($adminPasswords as $admin) {
            if (Hash::check($plain, $admin->password)) {
                return true;
            }
        }

        return false;
    }
}
