@extends('layouts.app')

@section('title', 'Leave Usage Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Leave Usage Report – {{ $year }}</h2>
    <form method="GET" class="d-flex gap-2">
        <select name="year" class="form-select" onchange="this.form.submit()">
            @for($y = now()->year; $y >= now()->year - 3; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>
</div>

<div class="row g-4 mb-4">
    <!-- By Leave Type -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0">By Leave Type</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Leave Type</th>
                            <th>Requests</th>
                            <th>Total Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byLeaveType as $type)
                        <tr>
                            <td><strong>{{ $type->name }}</strong></td>
                            <td>{{ $type->approved_count }}</td>
                            <td><span class="badge bg-primary">{{ $type->total_days ?? 0 }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- By Department -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0">By Department</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Department</th>
                            <th>Employees</th>
                            <th>Requests</th>
                            <th>Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byDepartment as $dept)
                        <tr>
                            <td><strong>{{ $dept['name'] }}</strong></td>
                            <td>{{ $dept['employee_count'] }}</td>
                            <td>{{ $dept['request_count'] }}</td>
                            <td><span class="badge bg-info">{{ $dept['total_days'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Trend Chart -->
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Monthly Leave Days Trend – {{ $year }}</h5>
    </div>
    <div class="card-body">
        <canvas id="leaveTrendChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('leaveTrendChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! $monthlyTrend->pluck('month') !!},
            datasets: [{
                label: 'Leave Days',
                data: {!! $monthlyTrend->pluck('days') !!},
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection