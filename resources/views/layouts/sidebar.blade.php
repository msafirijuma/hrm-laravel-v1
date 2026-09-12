<div class="sidebar p-2 text-white d-flex flex-column h-100">
    <!-- close button -->
    <div class="d-flex d-md-none justify-content-end px-3 pe-0 pt-0">
        <button type="button" id="sidebarClose" 
                class="btn btn-sm"
                style="background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 8px; width: 36px; height: 36px;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Brand Title -->
    <div class="p-2 text-center border-bottom border-secondary">
        <h4 class="mb-2 text-center fw-bold py-2 border-secondary">
            @if(setting('logo'))
                <img src="{{ asset('storage/' . setting('logo')) }}"
                    alt="{{ setting('platform_name', 'Dr GMbash') }}"
                    class="brand-logo">
                    {{ setting('platform_name', 'HRM System') }}
            @else
                <span class="fw-bold" style="color: #f59e0b;">
                    {{ setting('platform_name', 'Dr GMbash') }}
                </span>
            @endif
        </h4>
    </div>
    
    <ul class="nav flex-column mb-4 mt-2 flex-grow-1">
        
        <!-- Dashboard - All users -->
        <li class="nav-item mb-1">
            <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center @if(Route::currentRouteName() == 'dashboard') active @endif">
                <i class="fas fa-th-large me-3 text-light"></i> Dashboard
            </a>
        </li>

        <!-- HR & Super Admin Only -->
        @if(auth()->user()->hasAnyRole(['Super Admin', 'HR']))
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">Management</span>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('departments.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'departments.index') active @endif">
                    <i class="fas fa-sitemap me-3"></i> Departments
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('employees.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'employees.index') active @endif">
                    <i class="fas fa-user-tie me-3"></i> Employees
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('leave-types.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'leave-types.index') active @endif">
                    <i class="fas fa-cog me-3"></i> Leave Types
                </a>
            </li>

            <!-- Payrolls -->
            <li class="nav-item dropdown mb-1">
                <a class="nav-link d-flex align-items-center justify-content-between text-white dropdown-toggle" href="#" id="payrollDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-money-bill-wave me-3"></i> Payroll
                    </div>
                </a>
                <ul class="dropdown-menu bg-dark border-0 shadow w-100 ps-3" aria-labelledby="payrollDropdown">
                    <li>
                        <a class="dropdown-item text-white py-2" href="{{ route('payrolls.index') }}">
                            <i class="fas fa-list me-2 small"></i> Payroll Management
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white py-2" href="{{ route('payrolls.bulk.create') }}">
                            <i class="fas fa-copy me-2 small"></i> Bulk Generation
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white py-2" href="{{ route('payrolls.reports') }}">
                            <i class="fas fa-chart-bar me-2 small"></i> Payroll Reports
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('hr.attendance') }}" class="nav-link d-flex align-items-center @if (Str::contains(Route::currentRouteName(), 'hr.attendance')) active @endif">
                    <i class="fas fa-clock me-3"></i> Attendance Overview
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('reports.leave-usage') }}" class="nav-link d-flex align-items-center @if (Str::contains(Route::currentRouteName(), 'reports.leave-usage')) active @endif">
                    <i class="fas fa-chart-pie me-3"></i> Leave Usage Report
                </a>
            </li>

            <!-- Pending Leaves Count -->
            <li class="nav-item mb-1">
                <a href="{{ route('leave-requests.pending') }}" class="nav-link d-flex align-items-center justify-content-between @if (Route::currentRouteName() == 'leave-requests.pending') active @endif">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clipboard-list me-3"></i> Pending Leaves
                    </div>
                    @php
                        $pendingCount = \App\Models\LeaveRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge rounded-pill bg-danger px-2 py-1 small fw-bold">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Announcements -->
            <li class="nav-item mb-1">
                <a href="{{ route('announcements.index') }}" class="nav-link d-flex align-items-center @if (Str::contains(Route::currentRouteName(), 'announcements.index')) active @endif">
                    <i class="fas fa-bullhorn me-3"></i> Announcements
                </a>
            </li>

            <!-- Public Holidays -->
            <li class="nav-item mb-1">
                <a href="{{ route('public-holidays.index') }}" class="nav-link d-flex align-items-center @if (Str::contains(Route::currentRouteName(), 'public-holidays.index')) active @endif">
                    <i class="fas fa-calendar-day me-3"></i> Public Holidays
                </a>
            </li>
        @endif

        <!-- Announcement Board -->
        <li class="nav-item mb-3">
            <a href="{{ route('announcements.board') }}" class="nav-link d-flex align-items-center @if (Str::contains(Route::currentRouteName(), 'announcements.board')) active @endif">
                <i class="fas fa-bullhorn me-3"></i> Announcement Board
                @php
                    $unreadCount = \App\Models\Announcement::visible()
                        ->whereDoesntHave('reads', fn($q) => $q->where('user_id', auth()->id()))
                        ->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                @endif
            </a>
        </li>

        <!-- Manager Only -->
        @if(auth()->user()->hasRole('Manager'))
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">Manager</span>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('team.members') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'team.members') active @endif">
                    <i class="fas fa-users me-3"></i> Team Members
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('team.leaves') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'team.leaves') active @endif">
                    <i class="fas fa-calendar me-3"></i> Team Leaves
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('team.attendance') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'team.attendance') active @endif">
                    <i class="fas fa-clock me-3"></i> Team Attendance
                </a>
            </li>
        @endif

        @if (auth()->user()->hasRole(['Manager', 'HR', 'Super Admin']))
            <li class="nav-item mb-1">
                <a href="{{ route('performance-reviews.index') }}" class="nav-link d-flex align-items-center @if (Str::contains(Route::currentRouteName(), 'performance-reviews')) active @endif">
                    <i class="fas fa-chart-line me-3"></i> Performance Reviews
                </a>
            </li>
            
        @endif

        <!-- Employee & Manager -->
        @if(auth()->user()->hasAnyRole(['Employee', 'Manager']))
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">Self Service</span>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('my-leaves') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my-leaves') active @endif">
                    <i class="fas fa-calendar-check me-3"></i> My Leaves
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('apply-leave') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'apply-leave') active @endif">
                    <i class="fas fa-paper-plane me-3"></i> Apply for Leave
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('my.documents') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my.documents') active @endif">
                    <i class="fas fa-folder me-3"></i> My Documents
                </a>
            </li>
        @endif

        <!-- Admin Only -->
        @if(auth()->user()->hasRole('Super Admin'))
            <li class="nav-item mt-3 mb-2">
                <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">System</span>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('settings.index') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'settings.index') active @endif">
                    <i class="fas fa-cog me-3"></i> Settings
                </a>
            </li>
            <!-- Activity Logs -->
            <li class="nav-item mb-1">
                <a href="{{ route('activity-logs.index') }}" class="nav-link d-flex align-items-center @if (Str::contains(Route::currentRouteName(), 'activity-logs')) active @endif">
                    <i class="fas fa-history me-3"></i> Activity Logs
                </a>
            </li>
        @endif

        <!-- Personal Section -->
        <li class="nav-item mt-3 mb-2">
            <span class="text-uppercase text-muted fw-bold small tracking-wider px-3">Personal</span>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('my-payslips') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == '') active @endif">
                <i class="fas fa-file-invoice me-3"></i> My Payslips
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('my-profile') }}" class="nav-link d-flex align-items-center @if (Route::currentRouteName() == 'my-profile') active @endif">
                <i class="fas fa-user-circle me-3"></i> My Profile
            </a>
        </li>
    </ul>

    <!-- Logout -->
    <div class="mt-auto pt-4 pb-3 border-top border-secondary">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
