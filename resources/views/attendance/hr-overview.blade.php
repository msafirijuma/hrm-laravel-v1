@extends('layouts.app')

@section('title', 'HR Attendance Overview')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Attendance Overview</h2>
    <a href="{{ route('attendance.mark') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Mark Attendance
    </a>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('hr.attendance') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tarehe</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Idara</label>
                <select name="department_id" class="form-select">
                    <option value="">-- All Departments --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('hr.attendance') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Present</h6>
                <h3 class="text-success fw-bold">{{ $summary['present'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Late</h6>
                <h3 class="text-warning fw-bold">{{ $summary['late'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Absent</h6>
                <h3 class="text-danger fw-bold">{{ $summary['absent'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Half Day</h6>
                <h3 class="text-info fw-bold">{{ $summary['half_day'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">On Leave</h6>
                <h3 class="text-secondary fw-bold">{{ $summary['on_leave'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Not Marked</h6>
                <h3 class="text-dark fw-bold">{{ $summary['not_marked'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Attendance – {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Mfanyakazi</th>
                    <th>Idara</th>
                    <th>Status</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                @php $att = $attendances->get($emp->id); @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $emp->first_name }} {{ $emp->last_name }}</strong><br>
                        <small class="text-muted">{{ $emp->employee_number }}</small>
                    </td>
                    <td>{{ $emp->department->name ?? '-' }}</td>
                    <td>
                        @if($att)
                            @if($att->status == 'present')
                                <span class="badge bg-success">Present</span>
                            @elseif($att->status == 'late')
                                <span class="badge bg-warning text-dark">Late</span>
                            @elseif($att->status == 'absent')
                                <span class="badge bg-danger">Absent</span>
                            @elseif($att->status == 'half_day')
                                <span class="badge bg-info">Half Day</span>
                            @else
                                <span class="badge bg-secondary">On Leave</span>
                            @endif
                        @else
                            <span class="badge bg-light text-dark border">Not Marked</span>
                        @endif
                    </td>
                    <td>{{ $att?->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '—' }}</td>
                    <td>{{ $att?->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '—' }}</td>
                    <td>{{ $att?->notes ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection