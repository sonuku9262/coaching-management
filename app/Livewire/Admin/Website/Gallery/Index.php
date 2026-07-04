<?php

namespace App\Livewire\Admin\Website\Gallery;

use App\Models\Gallery;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $gallery_id;
    public $title;
    public $image;
    public $old_image;
    public $sort_order = 0;
    public $status = true;
    public $isEdit = false;

    public function save()
    {
        abort_unless(auth()->user()->can($this->gallery_id ? 'gallery.edit' : 'gallery.create'), 403);

        $this->validate([
            'title' => 'nullable|max:100',
            'image' => ($this->gallery_id ? 'nullable' : 'required') . '|image|max:4096',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $imagePath = $this->old_image;

        if ($this->image) {
            $imagePath = $this->image->store('gallery', 'public');
        }

        Gallery::updateOrCreate(
            ['id' => $this->gallery_id],
            [
                'title' => $this->title,
                'image' => $imagePath,
                'sort_order' => $this->sort_order ?: 0,
                'status' => $this->status,
            ],
        );

        session()->flash('success', $this->gallery_id ? 'Photo Updated Successfully.' : 'Photo Added Successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('gallery.edit'), 403);

        $gallery = Gallery::findOrFail($id);

        $this->gallery_id = $gallery->id;
        $this->title = $gallery->title;
        $this->old_image = $gallery->image;
        $this->sort_order = $gallery->sort_order;
        $this->status = $gallery->status;
        $this->image = null;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('gallery.delete'), 403);

        $gallery = Gallery::findOrFail($id);

        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        session()->flash('success', 'Photo Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset(['gallery_id', 'title', 'image', 'old_image']);
        $this->sort_order = 0;
        $this->status = true;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.admin.website.gallery.index', [
            'galleries' => Gallery::orderBy('sort_order')->latest()->paginate(12),
        ])->layout('layouts.admin');
    }
}
