@extends('layouts.app')

@section('title', 'Setting')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Settings</h3>
    <p class="text-muted mb-0">Configure platform settings and preferences</p>
</div>


<div class="row g-4">
    <!-- Left Menu -->
    <div class="col-lg-3">
        <div class="card border-1">
            <div class="card-body p-2">
                <nav class="nav flex-column settings-nav">
                    <a href="#general" class="nav-link settings-tab active" data-target="general">
                        <i class="fas fa-cog me-2"></i> General
                    </a>
                    <a href="#security" class="nav-link settings-tab" data-target="security">
                        <i class="fas fa-shield-alt me-2"></i> Security
                    </a>
                    <a href="#appearance" class="nav-link settings-tab" data-target="appearance">
                        <i class="fas fa-palette me-2"></i> Appearance
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Right Panels -->
    <div class="col-lg-9">

        <!-- GENERAL -->
        <div class="settings-panel" id="panel-general">
            <div class="card border-1">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">General Settings</h5>

                    <form action="{{ route('settings.general') }}" 
                        method="POST" 
                        id="generalSettingsForm" 
                        novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-muted">Platform Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                name="platform_name" 
                                id="platform_name"
                                style="background-color: #1A233A"
                                class="form-control text-white border-secondary @error('platform_name') is-invalid @enderror"
                                value="{{ old('platform_name', $settings['platform_name']) }}"
                                required
                                minlength="2"
                                maxlength="255">
                            <div class="invalid-feedback" id="error_platform_name">
                                @error('platform_name') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Support Email</label>
                            <input type="email" 
                                name="support_email" 
                                id="support_email"
                                style="background-color: #1A233A"
                                class="form-control text-white border-secondary @error('support_email') is-invalid @enderror"
                                value="{{ old('support_email', $settings['support_email']) }}"
                                maxlength="255">
                            <div class="invalid-feedback" id="error_support_email">
                                @error('support_email') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Support Phone</label>
                            <input type="text" 
                                name="support_phone" 
                                id="support_phone"
                                style="background-color: #1A233A"
                                class="form-control text-white border-secondary @error('support_phone') is-invalid @enderror"
                                value="{{ old('support_phone', $settings['support_phone']) }}"
                                maxlength="30"
                                placeholder="e.g. +255 712 345 678">
                            <div class="invalid-feedback" id="error_support_phone">
                                @error('support_phone') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Tagline</label>
                            <input type="text" 
                                name="tagline" 
                                id="tagline"
                                style="background-color: #1A233A"
                                class="form-control text-white border-secondary @error('tagline') is-invalid @enderror"
                                value="{{ old('tagline', $settings['tagline']) }}"
                                maxlength="255"
                                placeholder="e.g. Master Biology with confidence">
                            <div class="invalid-feedback" id="error_tagline">
                                @error('tagline') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Location</label>
                            <input type="text"
                                name="location"
                                id="location"
                                style="background-color: #1A233A"
                                class="form-control text-white border-secondary @error('location') is-invalid @enderror"
                                value="{{ old('location', $settings['location']) }}"
                                maxlength="255"
                                placeholder="e.g. Dar es Salaam, Tanzania">
                            <div class="invalid-feedback" id="error_location">
                                @error('location') {{ $message }} @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECURITY -->
        <div class="settings-panel d-none" id="panel-security">
            <div class="card border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Security Settings</h5>
                    <form action="{{ route('settings.security') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted">Current Password</label>
                            <input type="password" name="current_password" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">New Password</label>
                            <input type="password" name="new_password" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update 
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- APPEARANCE -->
        <div class="settings-panel d-none" id="panel-appearance">
            <div class="card border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Appearance</h5>
                    <form action="{{ route('settings.appearance') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted">Platform Logo</label>
                            @if($settings['logo'])
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" style="max-height: 60px;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control bg-dark text-white border-secondary" accept="image/*">
                            <small class="text-muted">PNG or JPG, max 2MB</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // ===== General Settings Validation =====
    const generalForm = document.getElementById('generalSettingsForm');

    if (generalForm) {
        generalForm.addEventListener('submit', function (e) {
            let isValid = true;

            // Clear previous errors
            generalForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            generalForm.querySelectorAll('.invalid-feedback').forEach(el => {
                if (!el.textContent.includes('{{--')) el.textContent = '';
            });

            const platformName = document.getElementById('platform_name');
            const supportEmail = document.getElementById('support_email');
            const supportPhone = document.getElementById('support_phone');

            // Platform Name - required, min 2
            if (!platformName.value.trim()) {
                showError(platformName, 'error_platform_name', 'Platform name is required.');
                isValid = false;
            } else if (platformName.value.trim().length < 2) {
                showError(platformName, 'error_platform_name', 'Platform name must be at least 2 characters.');
                isValid = false;
            }

            // Support Email - optional but must be valid if filled
            if (supportEmail.value.trim() && !isValidEmail(supportEmail.value.trim())) {
                showError(supportEmail, 'error_support_email', 'Please enter a valid email address.');
                isValid = false;
            }

            // Support Phone - optional but basic format if filled
            if (supportPhone.value.trim() && !isValidPhone(supportPhone.value.trim())) {
                showError(supportPhone, 'error_support_phone', 'Please enter a valid phone number.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    function showError(input, errorId, message) {
        input.classList.add('is-invalid');
        const errorEl = document.getElementById(errorId);
        if (errorEl) errorEl.textContent = message;
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
        return /^[0-9+\-\s()]{7,30}$/.test(phone);
    }
</script>

<script>
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove active from all tabs
            document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Hide all panels
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.add('d-none'));

            // Show target panel
            const target = this.getAttribute('data-target');
            document.getElementById('panel-' + target).classList.remove('d-none');
        });
    });
</script>
@endsection