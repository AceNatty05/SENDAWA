<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('SENDAWA – Review Anonim')]
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

    public string $title          = '';
    public string $review_content = '';
    public $image                 = null;   // TemporaryUploadedFile | null

    // ---------------------------------------------------------------------------
    // Validation Rules
    // ---------------------------------------------------------------------------

    protected function rules(): array
    {
        return [
            'title'          => 'required|string|min:3|max:255',
            'review_content' => 'required|string|min:10',
            'image'          => 'nullable|image|max:2048', // 2 MB
        ];
    }

    protected array $messages = [
        'title.required'          => 'Judul wajib diisi.',
        'title.min'               => 'Judul minimal 3 karakter.',
        'review_content.required' => 'Isi review wajib diisi.',
        'review_content.min'      => 'Isi review minimal 10 karakter.',
        'image.image'             => 'File harus berupa gambar.',
        'image.max'               => 'Ukuran gambar maksimal 2 MB.',
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
     * Proses submit review baru.
     */
    public function submitReview(): void
    {
        $this->validate();

        $imagePath = null;

        if ($this->image) {
            // Simpan ke storage/app/public/images → dapat diakses via /storage/images/...
            $imagePath = $this->image->store('images', 'public');
        }

        Review::create([
            'title'          => trim($this->title),
            'review_content' => trim($this->review_content),
            'image_path'     => $imagePath,
        ]);

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();

        session()->flash('success', 'Review berhasil dikirim!');
    }

    /**
     * Hapus sebuah review beserta gambarnya (jika ada).
     */
    public function deleteReview(int $id): void
    {
        $review = Review::findOrFail($id);

        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }

        $review->delete();

        session()->flash('success', 'Review berhasil dihapus.');
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
        $reviews = Review::query()
            ->when(
                $this->search,
                fn ($q) => $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(9);

        return view('livewire.review-manager', [
            'reviews' => $reviews,
        ])->layout('layouts.app');
    }

    // ---------------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------------

    private function resetForm(): void
    {
        $this->reset(['title', 'review_content', 'image']);
        $this->resetValidation();
    }
}
