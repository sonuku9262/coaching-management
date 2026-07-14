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

    // website content
    public $hero_title;
    public $hero_subtitle;
    public $about_text;
    public $facebook_url;
    public $instagram_url;
    public $youtube_url;
    public $whatsapp_number;

    // payment gateway
    public $razorpay_enabled = false;
    public $razorpay_key_id;
    public $razorpay_key_secret;

    // sms / whatsapp
    public $sms_enabled = false;
    public $msg91_auth_key;
    public $msg91_sender_id;

    public function mount()
    {
        $this->institute_name = Setting::get('institute_name');
        $this->institute_email = Setting::get('institute_email');
        $this->institute_phone = Setting::get('institute_phone');
        $this->institute_address = Setting::get('institute_address');
        $this->institute_website = Setting::get('institute_website');
        $this->current_logo = Setting::get('institute_logo');

        $this->hero_title = Setting::get('hero_title');
        $this->hero_subtitle = Setting::get('hero_subtitle');
        $this->about_text = Setting::get('about_text');
        $this->facebook_url = Setting::get('facebook_url');
        $this->instagram_url = Setting::get('instagram_url');
        $this->youtube_url = Setting::get('youtube_url');
        $this->whatsapp_number = Setting::get('whatsapp_number');

        $this->razorpay_enabled = (bool) Setting::get('razorpay_enabled', false);
        $this->razorpay_key_id = Setting::get('razorpay_key_id');
        $this->razorpay_key_secret = Setting::get('razorpay_key_secret');

        $this->sms_enabled = (bool) Setting::get('sms_enabled', false);
        $this->msg91_auth_key = Setting::get('msg91_auth_key');
        $this->msg91_sender_id = Setting::get('msg91_sender_id');
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

        Setting::set('hero_title', $this->hero_title);
        Setting::set('hero_subtitle', $this->hero_subtitle);
        Setting::set('about_text', $this->about_text);
        Setting::set('facebook_url', $this->facebook_url);
        Setting::set('instagram_url', $this->instagram_url);
        Setting::set('youtube_url', $this->youtube_url);
        Setting::set('whatsapp_number', $this->whatsapp_number);

        Setting::set('razorpay_enabled', $this->razorpay_enabled ? '1' : '0');
        Setting::set('razorpay_key_id', $this->razorpay_key_id);
        Setting::set('razorpay_key_secret', $this->razorpay_key_secret);

        Setting::set('sms_enabled', $this->sms_enabled ? '1' : '0');
        Setting::set('msg91_auth_key', $this->msg91_auth_key);
        Setting::set('msg91_sender_id', $this->msg91_sender_id);

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
