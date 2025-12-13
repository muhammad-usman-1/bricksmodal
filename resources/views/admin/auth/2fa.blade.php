@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="text-center mt-2">
                    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo" style="height: 30px; width:150px">
                </div>
                <hr>
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Two-Factor Authentication</h5>
                    <p class="text-muted text-center mb-4">Enter the code from your authenticator app</p>
                    <p class="text-muted text-center small mb-4">Or use a recovery code if you don't have access to your authenticator app</p>

                    <form method="POST" action="{{ route('admin.login.2fa.verify') }}">
                        @csrf

                        <div class="form-group">
                            <label for="code">Verification Code or Recovery Code</label>
                            <input id="code" type="text" 
                                class="form-control @error('code') is-invalid @enderror" 
                                name="code" 
                                maxlength="25" 
                                required 
                                autofocus
                                autocomplete="one-time-code"
                                placeholder="Enter code from authenticator app"
                                style="text-align: center; letter-spacing: 2px; font-size: 16px; font-weight: 600;">
                            @error('code')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <small class="form-text text-muted">Enter your authenticator code (usually 6 digits) or a recovery code</small>
                        </div>

                        <button type="submit" class="btn btn-dark btn-block">Verify Code</button>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.login') }}" class="text-muted">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('code');
            if (codeInput) {
                // Allow alphanumeric characters and dashes for recovery codes
                // Remove only spaces, keep digits, letters, and dashes
                codeInput.addEventListener('input', function(e) {
                    // Allow digits, letters, and dashes, remove spaces
                    e.target.value = e.target.value.replace(/[^\dA-Za-z-]/g, '');
                });
            }
        });
    </script>
@endsection

