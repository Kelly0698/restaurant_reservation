@extends('layouts')
@section('title', 'Restaurant Reset Password')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid d-flex justify-content-center">
        <div class="col-md-8 mx-auto">
            <br><br><br><br>
            <div class="card">
                <div style="font-size:20px" class="card-header">{{ __('Reset Password') }}</div>
                <div class="card-body text-center" style="font-size:15px">
                    <form method="POST" action="{{ route('ResetPasswordPost') }}" id="resetPasswordForm">
                        @csrf
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input style="font-size:15px" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input style="font-size:15px; border-radius: 15px;" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" onclick="togglePasswordVisibility('password', this)">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input style="font-size:15px; border-radius: 15px;" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" onclick="togglePasswordVisibility('password-confirm', this)">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-4 offset-md-4">
                                <button style="font-size:15px" type="submit" class="btn btn-outline-secondary yellow rounded-pill">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId, icon) {
        const field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
            icon.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            field.type = "password";
            icon.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }

    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('password-confirm').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please try again.');
        }
    });
</script>
@endsection
