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

        /* ========== MOBILE TOP BAR ========== */
        .mobile-topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 1040;
            background: #1a233a;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .mobile-topbar .btn-toggle {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.15);
            color: #0d6efd;
            padding: 6px 12px;
            border-radius: 8px;
        }

        /* ========== OVERLAY ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 1045;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Settings Navigation */
        .settings-nav .nav-link {
            color: #94a3b8 !important;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 4px;
        }

        .settings-nav .nav-link:hover {
            color: #0278B6 !important;
            background: rgba(160, 153, 141, 0.08);
        }

        .settings-nav .nav-link.active {
            color: white !important;
            background: #1A233A !important;
            font-weight: 600;
        }

        /* ========== MOBILE RESPONSIVE ========== */
        @media (max-width: 767.98px) {
            .app-sidebar-container {
                transform: translateX(-100%);
                z-index: 1050;
            }

            .app-sidebar-container.show {
                transform: translateX(0);
            }

            .app-content-container {
                margin-left: 0;
                padding: 0 !important;
                justify-content: start !important;
            }

            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        }

        /* Dropdown Menu */
        .app-sidebar-container .dropdown-menu {
            background-color: #ffffff !important; 
            border: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important; 
            border-radius: 8px !important;
            padding: 0.5rem 0 !important;
            margin-top: 5px !important;
        }

        /* Items in the Dropdown */
        .app-sidebar-container .dropdown-item {
            color: #4a5568 !important; 
            font-weight: 500 !important;
            padding: 0.6rem 1.2rem !important;
            transition: all 0.2s ease !important;
            display: flex !important;
            align-items: center !important;
            background: none !important;
        }

        /* Icons inside Dropdown */
        .app-sidebar-container .dropdown-item i {
            color: #718096 !important; 
            width: 25px !important;
            font-size: 1rem !important;
            transition: all 0.2s ease !important;
        }

        /* Dropdown effect on dropdown items  */
        .app-sidebar-container .dropdown-item:hover {
            background-color: #f1f5f9 !important; 
            color: #0d6efd !important;            
            padding-left: 1.5rem !important;      
        }

        /* Icons change color on hovering */
        .app-sidebar-container .dropdown-item:hover i {
            color: #0d6efd !important;
        }
    </style>
<body>

<!-- Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">
    <!-- Left Side: Sidebar -->
    <div class="app-sidebar-container" id="sidebar">
        @include('layouts.sidebar')
    </div>

    <!-- Right side: Main Content & Footer -->
    <div class="app-content-container d-flex flex-column justify-content-between">

        <div class="mobile-topbar">
                <button class="btn-toggle" id="sidebarToggle" type="button">
                    <i class="fas fa-hamburger"></i>
                </button>
                <span class="fw-bold text-white">
                    <span class="text-white me-2">{{ setting('platform_name', 'HRM System') }}</span>
                </span>
                <div style="width: 40px;"></div> {{-- spacer --}}
        </div>

        <div class="main-content-body p-2 w-100 mt-3 mt-md-1">
            @include('layouts.top-navbar')
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
        $('#employeeTable, #departmentTable, #leaveTable, #payrollTable, #performanceTable, #holidayTable', '#announcementTable').DataTable({
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                responsive: true,
                scrollX: true,        // horizontal scroll 
                autoWidth: false,
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
        timerProgressBar: false,
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
    
    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
    @endif
</script>

<!-- Overlays & Offset-->
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
    });

    closeBtn?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);

    document.querySelectorAll('.app-sidebar-container a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) closeSidebar();
        });
    });
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
