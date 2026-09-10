@extends('layouts.app')

@section('title', 'Team Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-md-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Team Attendance Summary</h2>
        <div class="d-flex justify-content-between align-items-center mt-2 mt-md-0">
            <a href="{{ route('attendance.mark') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus"></i> Mark Attendance
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="alert alert-info">
        This week attendance ({{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }})
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped mb-0" id="employeeTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Present</th>
                            <th>Late</th>
                            <th>Absent</th>
                            <th>Half Day</th>
                            <th>On Leave</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamMembers as $member)
                        @php
                            $memberAttendance = $attendances->get($member->id, collect());
                            $present = $memberAttendance->where('status', 'present')->count();
                            $late    = $memberAttendance->where('status', 'late')->count();
                            $absent  = $memberAttendance->where('status', 'absent')->count();
                            $half    = $memberAttendance->where('status', 'half_day')->count();
                            $leave   = $memberAttendance->where('status', 'on_leave')->count();
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $member->first_name }} {{ $member->last_name }}</strong><br>
                                <small class="text-muted">{{ $member->employee_number }}</small>
                            </td>
                            <td><span class="badge bg-success">{{ $present }}</span></td>
                            <td><span class="badge bg-warning">{{ $late }}</span></td>
                            <td><span class="badge bg-danger">{{ $absent }}</span></td>
                            <td><span class="badge bg-info">{{ $half }}</span></td>
                            <td><span class="badge bg-secondary">{{ $leave }}</span></td>
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                No team members found.
                            </td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection