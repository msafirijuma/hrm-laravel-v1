<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HRM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .brand-logo {
            font-size: 2.5rem;
        }
    </style>
</head>
<body class="d-flex align-items-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                
                <div class="card login-card">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-4">
                            <h1 class="brand-logo text-primary">🏢</h1>
                            <h3 class="fw-bold">HRM System</h3>
                            <p class="text-muted">Login </p>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </form>

                    </div>
                </div>

                <div class="text-center mt-3 text-white">
                    <small>&copy; {{ date('Y') }} HRM System</small>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SWEETALERT2 JAVASCRIPT INTEGRATION -->
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script>
        // SweetAlert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // success message
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // error message
        @if($errors->any())
            Toast.fire({
                icon: 'error',
                title: "{{ $errors->first() }}" 
            });
        @endif
    </script>
</body>
</html>
