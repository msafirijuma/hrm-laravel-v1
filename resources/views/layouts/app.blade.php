<!DOCTYPE html>
<html lang="en-US">
    @include('layouts.header')
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar */
        .app-sidebar-container {
            width: 260px; 
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            background-color: #1a233a; 
            overflow-y: auto;
        }

        .app-content-container {
            flex: 1;
            margin-left: 260px; /* same width to sidebar */
            padding: 1.5rem;
            min-height: 100vh;
            display: flex;
            flex-column;
            justify-content: space-between;
        }
    </style>
<body>

<div class="app-wrapper">
    <!-- Left Side: Sidebar -->
    <div class="app-sidebar-container">
        @include('layouts.sidebar')
    </div>

    <!-- Right side: Main Content & Footer -->
    <div class="app-content-container d-flex flex-column justify-content-between">
        <div class="main-content-body w-100">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <div class="w-100 mt-5">
            @include('layouts.footer')
        </div>
    </div>
</div>

<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Local JS -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

<!-- Datatable -->
<script>
    $(document).ready(function() {
        $('#employeeTable, #departmentTable, #leaveTable, #payrollTable, #performanceTable').DataTable({
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Prev"
                }
            }
        });
    });
</script>

<!-- SweetAlert2 -->
<script>
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

<script>
    window.addEventListener('pageshow', function (event) {
        // Back button pressed
        var historyTraversal = event.persisted || 
                               (typeof window.performance != 'undefined' && 
                                window.performance.navigation.type === 2);
                                
        if (historyTraversal) {
            // Close any frozen SweetAlert spinner immediately
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
        }
    });
</script>

@yield('scripts')
</body>
</html>
