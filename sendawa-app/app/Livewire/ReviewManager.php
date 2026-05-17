<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

// #[Title('SENDAWA – Postingan Anonim')]
class ReviewManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    // ---------------------------------------------------------------------------
    // State
    // ---------------------------------------------------------------------------

    /** @var string Kata kunci pencarian (real-time, disinkronisasi ke URL) */
    #[Url(as: 'q')]
    public string $search = '';

    /** @var bool Tampilkan/sembunyikan form */
    public bool $showForm = false;

    // ---------------------------------------------------------------------------
    // Form Fields
    // ---------------------------------------------------------------------------

    public string $title   = '';
    public string $content = '';
    public $image          = null;   // TemporaryUploadedFile | null

    // ---------------------------------------------------------------------------
    // Validation Rules
    // ---------------------------------------------------------------------------

    protected function rules(): array
    {
        return [
            'title'   => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'image'   => 'nullable|image|max:2048', // 2 MB
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
    // Actions
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

    /**
     * Hapus sebuah postingan beserta gambarnya (jika ada).
     */
    public function deletePostingan(int $id): void
    {
        $post = Post::findOrFail($id);

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        session()->flash('success', 'Postingan berhasil dihapus.');
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
}
