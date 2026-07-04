<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $institute_name;
    public $institute_email;
    public $institute_phone;
    public $institute_address;
    public $institute_website;
    public $logo;
    public $current_logo;

    public function mount()
    {
        $this->institute_name = Setting::get('institute_name');
        $this->institute_email = Setting::get('institute_email');
        $this->institute_phone = Setting::get('institute_phone');
        $this->institute_address = Setting::get('institute_address');
        $this->institute_website = Setting::get('institute_website');
        $this->current_logo = Setting::get('institute_logo');
    }

    public function save()
    {
        abort_unless(auth()->user()->can('settings.edit'), 403);

        $this->validate([
            'institute_name' => 'required|min:3',
            'institute_email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048',
        ]);

        Setting::set('institute_name', $this->institute_name);
        Setting::set('institute_email', $this->institute_email);
        Setting::set('institute_phone', $this->institute_phone);
        Setting::set('institute_address', $this->institute_address);
        Setting::set('institute_website', $this->institute_website);

        if ($this->logo) {
            $this->current_logo = $this->logo->store('settings', 'public');
            Setting::set('institute_logo', $this->current_logo);
            $this->logo = null;
        }

        session()->flash('success', 'Settings Saved Successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings.index')
            ->layout('layouts.admin');
    }
}
