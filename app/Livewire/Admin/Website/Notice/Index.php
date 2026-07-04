<?php

namespace App\Livewire\Admin\Website\Notice;

use App\Models\Notice;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $notice_id;
    public $title;
    public $description;
    public $notice_date;
    public $show_on_website = true;
    public $status = true;
    public $isEdit = false;

    public function mount()
    {
        $this->notice_date = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        abort_unless(auth()->user()->can($this->notice_id ? 'notices.edit' : 'notices.create'), 403);

        $this->validate([
            'title' => 'required|min:3|max:200',
            'description' => 'nullable|max:2000',
            'notice_date' => 'required|date',
        ]);

        Notice::updateOrCreate(
            ['id' => $this->notice_id],
            [
                'title' => $this->title,
                'description' => $this->description,
                'notice_date' => $this->notice_date,
                'show_on_website' => $this->show_on_website,
                'status' => $this->status,
            ],
        );

        session()->flash('success', $this->notice_id ? 'Notice Updated Successfully.' : 'Notice Added Successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('notices.edit'), 403);

        $notice = Notice::findOrFail($id);

        $this->notice_id = $notice->id;
        $this->title = $notice->title;
        $this->description = $notice->description;
        $this->notice_date = $notice->notice_date instanceof \DateTimeInterface
            ? $notice->notice_date->format('Y-m-d')
            : $notice->notice_date;
        $this->show_on_website = $notice->show_on_website;
        $this->status = $notice->status;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('notices.delete'), 403);

        Notice::findOrFail($id)->delete();

        session()->flash('success', 'Notice Deleted Successfully.');
    }

    public function resetForm()
    {
        $this->reset(['notice_id', 'title', 'description']);
        $this->notice_date = now()->format('Y-m-d');
        $this->show_on_website = true;
        $this->status = true;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.admin.website.notice.index', [
            'notices' => Notice::where('title', 'like', '%' . $this->search . '%')
                ->orderByDesc('notice_date')
                ->paginate(10),
        ])->layout('layouts.admin');
    }
}
