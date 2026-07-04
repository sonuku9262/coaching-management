<?php

namespace App\Livewire\Admin\Enquiry;

use App\Models\Enquiry;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function updating($property)
    {
        if (in_array($property, ['search', 'status'])) {
            $this->resetPage();
        }
    }

    public function markStatus($id, $status)
    {
        abort_unless(auth()->user()->can('enquiries.edit'), 403);

        if (! in_array($status, ['new', 'contacted', 'closed'])) {
            return;
        }

        Enquiry::findOrFail($id)->update(['status' => $status]);

        session()->flash('success', 'Enquiry marked as ' . $status . '.');
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('enquiries.delete'), 403);

        Enquiry::findOrFail($id)->delete();

        session()->flash('success', 'Enquiry Deleted Successfully.');
    }

    public function render()
    {
        $enquiries = Enquiry::with('course')
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->search, fn ($q) => $q->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            }))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.enquiry.index', [
            'enquiries' => $enquiries,
            'newCount' => Enquiry::where('status', 'new')->count(),
        ])->layout('layouts.admin');
    }
}
