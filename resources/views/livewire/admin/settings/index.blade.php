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
