<div>

    <div class="container-fluid py-4">

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">Institute Settings</h4>

        </div>

        <div class="card-body">

            <form wire:submit.prevent="save">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Institute Name</label>

                        <input type="text" class="form-control" wire:model="institute_name">

                        @error('institute_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Email</label>

                        <input type="email" class="form-control" wire:model="institute_email">

                        @error('institute_email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Phone</label>

                        <input type="text" class="form-control" wire:model="institute_phone">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Website</label>

                        <input type="text" class="form-control" wire:model="institute_website">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Address</label>

                        <textarea class="form-control" rows="2" wire:model="institute_address"></textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Logo</label>

                        <input type="file" class="form-control" wire:model="logo">

                        @error('logo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <div wire:loading wire:target="logo" class="text-muted mt-1">
                            Uploading...
                        </div>

                    </div>

                    <div class="col-md-6 mb-3 text-center">

                        @if($current_logo)
                            <img src="{{ asset('storage/' . $current_logo) }}" height="80" class="border rounded p-1">
                        @endif

                    </div>

                </div>

                <hr>

                <h5 class="fw-bold mb-3">🌐 Website Content</h5>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Hero Title (home page heading)</label>

                        <input type="text" class="form-control"
                            placeholder="Default: institute name" wire:model="hero_title">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Hero Subtitle</label>

                        <input type="text" class="form-control"
                            placeholder="Chhota sa tagline / description" wire:model="hero_subtitle">

                    </div>

                    <div class="col-12 mb-3">

                        <label>About Text (about page & home)</label>

                        <textarea class="form-control" rows="3" wire:model="about_text"
                            placeholder="Institute ke baare me 2-4 lines..."></textarea>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Facebook URL</label>

                        <input type="text" class="form-control" wire:model="facebook_url">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Instagram URL</label>

                        <input type="text" class="form-control" wire:model="instagram_url">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>YouTube URL</label>

                        <input type="text" class="form-control" wire:model="youtube_url">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>WhatsApp Number</label>

                        <input type="text" class="form-control"
                            placeholder="e.g. 919876543210" wire:model="whatsapp_number">

                    </div>

                </div>

                <hr>

                <h5 class="fw-bold mb-3">💳 Payment Gateway (Razorpay)</h5>

                <div class="row">

                    <div class="col-md-12 mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="razorpay_enabled" wire:model="razorpay_enabled">
                        <label class="form-check-label" for="razorpay_enabled">Enable online fee payment via Razorpay</label>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Razorpay Key ID</label>
                        <input type="text" class="form-control" wire:model="razorpay_key_id" placeholder="rzp_live_xxxxxxxxxxxx">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Razorpay Key Secret</label>
                        <input type="password" class="form-control" wire:model="razorpay_key_secret" placeholder="••••••••••••">
                    </div>

                    <div class="col-12">
                        <small class="text-muted">Get these from your Razorpay Dashboard → Settings → API Keys. Students will see a "Pay Online" button only once both keys are saved and the toggle above is on.</small>
                    </div>

                </div>

                <hr>

                <h5 class="fw-bold mb-3">📱 SMS / WhatsApp (MSG91)</h5>

                <div class="row">

                    <div class="col-md-12 mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="sms_enabled" wire:model="sms_enabled">
                        <label class="form-check-label" for="sms_enabled">Enable SMS alerts (fee reminders, absence alerts)</label>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>MSG91 Auth Key</label>
                        <input type="password" class="form-control" wire:model="msg91_auth_key" placeholder="••••••••••••">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>MSG91 Sender ID</label>
                        <input type="text" class="form-control" wire:model="msg91_sender_id" placeholder="e.g. SUNRSE (6-char DLT approved)">
                    </div>

                    <div class="col-12">
                        <small class="text-muted">Get the auth key from your MSG91 dashboard. The sender ID must be DLT-registered for Indian mobile numbers before SMS will actually deliver.</small>
                    </div>

                </div>

                @can('settings.edit')
                <button type="submit" class="btn btn-primary">
                    💾 Save Settings
                </button>
                @endcan

            </form>

        </div>

    </div>

</div>

</div>
