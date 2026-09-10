@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">HR Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row g-3 mb-5">
        <h5 class="small mb-0 fs-5">Quick Info</h5>
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6>Total Employees</h6>
                    <h2>{{ $totalEmployees }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-eye"></i> View Employees
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6>Departments</h6>
                    <h2>{{ $totalDepartments }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('departments.index') }}" class="btn btn-light btn-sm text-success font-weight-bold">
                            <i class="fas fa-eye"></i> View Departments
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h6>Pending Leaves</h6>
                    <h2>{{ $pendingLeaves }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('leave-requests.pending') }}" class="btn btn-light btn-sm text-warning font-weight-bold">
                            <i class="fas fa-eye"></i> View Pending Leaves
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6>Recent Hires (This Month)</h6>
                    <h2 class="mb-0">{{ $newHiresThisMonth }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-eye"></i> View Employees
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Net Payroll (This Month)</h6>
                    <h4 class="fw-bold text-success mb-0">TZS {{ number_format($totalNetThisMonth, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Gross Payroll</h6>
                    <h4 class="fw-bold text-primary mb-0">TZS {{ number_format($totalGrossThisMonth, 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">NSSF</h6>
                    <h5 class="fw-bold mb-0">TZS {{ number_format($totalNSSF, 0) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">NHIF</h6>
                    <h5 class="fw-bold mb-0">TZS {{ number_format($totalNHIF, 0) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">PAYE</h6>
                    <h5 class="fw-bold mb-0">TZS {{ number_format($totalPAYE, 0) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Payroll Trend (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="payrollChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Contracts (Next 30 Days) -->
    <div class="row g-3 mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Expiring Contracts (Next 30 Days)</h5>
                    <span class="badge bg-danger">{{ $expiringContracts->count() }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover mb-0" id="employeeTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th style="width: 100px; min-width: 100px">Type</th>
                                    <th style="width: 120px; min-width: 120px">From</th>
                                    <th style="width: 120px; min-width: 120px">To</th>
                                    <th>Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                    <td>{{ $leave->employee->department->name ?? '-' }}</td>
                                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                    <td>{{ $leave->start_date->format('d M Y') }}</td>
                                    <td>{{ $leave->end_date->format('d M Y') }}</td>
                                    <td><span class="badge bg-info">{{ $leave->days_requested }}</span></td>
                                </tr>
                                @empty
                                {{-- <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No upcoming leave for the next 7 days. 
                                    </td>
                                </tr> --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Pending Leaves -->
    <div class="row g-3 mb-5"> 
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pending Leave Requests</h5>
                    <a href="{{ route('leave-requests.pending') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> View All
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover mb-0" id="employeeTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Employee</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingLeaveList as $leave)
                                <tr>
                                    <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                    <td>{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}</td>
                                    <td>{{ $leave->days_requested }}</td>
                                </tr>
                                @empty
                                {{-- <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No any pending leave requests yet.</td>
                                </tr> --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Hires -->
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Recent Hires</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentHires as $emp)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $emp->first_name }} {{ $emp->last_name }}</span>
                            <small class="text-muted">{{ $emp->created_at->diffForHumans() }}</small>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">No any data now.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Employees / department -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Employees per Department</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <canvas id="deptPieChart" height="250"></canvas>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                @foreach($employeesPerDept as $dept)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span>{{ $dept['name'] }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $dept['count'] }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Public holidays -->
    @include('layouts.public-holidays')
    
    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-dark-subtle text-dark">
                    <h6 class="m-0 font-weight-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('employees.create') }}" class="btn btn-success btn-lg w-100 py-4">
                                <i class="fas fa-plus fa-2x d-block mb-2"></i>
                                Add Employee
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('departments.create') }}" class="btn btn-primary btn-lg w-100 py-4">
                                <i class="fas fa-key fa-2x d-block mb-2"></i>
                                Add Department
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('leave-types.create') }}" class="btn btn-info btn-lg w-100 py-4">
                                <i class="fas fa-list fa-2x d-block mb-2"></i>
                                 Add Leave Type
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('leave-requests.pending') }}" class="btn btn-warning btn-lg w-100 py-4">
                                <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                                Pending Leaves
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Department Pie Chart =====
    const pieCtx = document.getElementById('deptPieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: {!! $employeesPerDept->pluck('name') !!},
                datasets: [{
                    data: {!! $employeesPerDept->pluck('count') !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14', '#20c997'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // ===== Payroll Bar Chart =====
    const payrollCtx = document.getElementById('payrollChart');
    if (payrollCtx) {
        new Chart(payrollCtx, {
            type: 'bar',
            data: {
                labels: {!! $months->pluck('label') !!},
                datasets: [
                    {
                        label: 'Gross Salary',
                        data: {!! $months->pluck('gross') !!},
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderRadius: 6
                    },
                    {
                        label: 'Net Salary',
                        data: {!! $months->pluck('net') !!},
                        backgroundColor: 'rgba(28, 200, 138, 0.7)',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'TZS ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    }
});
</script>
@endsection