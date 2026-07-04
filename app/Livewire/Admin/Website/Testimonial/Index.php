<?php

namespace App\Livewire\Admin\Website\Testimonial;

use App\Models\Testimonial;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $testimonial_id;
    public $name;
    public $designation;
    public $photo;
    public $old_photo;
    public $message;
    public $rating = 5;
    public $status = true;
    public $isEdit = false;

    public function save()
    {
        abort_unless(auth()->user()->can($this->testimonial_id ? 'testimonials.edit' : 'testimonials.create'), 403);

        $this->validate([
            'name' => 'required|min:3|max:100',
            'designation' => 'nullable|max:100',
            'photo' => 'nullable|image|max:2048',
            'message' => 'required|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $photoPath = $this->old_photo;

        if ($this->photo) {
            $photoPath = $this->photo->store('testimonials', 'public');
        }

        Testimonial::updateOrCreate(
            ['id' => $this->testimonial_id],
            [
                'name' => $this->name,
                'designation' => $this->designation,
                'photo' => $photoPath,
                'message' => $this->message,
                'rating' => $this->rating,
                'status' => $this->status,
            ],
        );

        session()->flash('success', $this->testimonial_id ? 'Testimonial Updated Successfully.' : 'Testimonial Added Successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('testimonials.edit'), 403);

        $testimonial = Testimonial::findOrFail($id);

        $this->testimonial_id = $testimonial->id;
        $this->name = $testimonial->name;
        $this->designation = $testimonial->designation;
        $this->old_photo = $testimonial->photo;
        $this->message = $testimonial->message;
        $this->rating = $testimonial->rating;
        $this->status = $testimonial->status;
        $this->photo = null;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('testimonials.delete'), 403);

        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        session()->flash('success', 'Testimonial Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset(['testimonial_id', 'name', 'designation', 'photo', 'old_photo', 'message']);
        $this->rating = 5;
        $this->status = true;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.admin.website.testimonial.index', [
            'testimonials' => Testimonial::latest()->paginate(10),
        ])->layout('layouts.admin');
    }
}
