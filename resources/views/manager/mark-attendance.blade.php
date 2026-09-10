@extends('layouts.app')

@section('title', 'Mark Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Mark Attendance</h2>
        <a href="{{ route('team.attendance') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $today }}" required>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $index => $employee)
                            <tr>
                                <td>
                                    <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong><br>
                                    <small class="text-muted">{{ $employee->employee_number }}</small>
                                    <input type="hidden" name="attendances[{{ $index }}][employee_id]" value="{{ $employee->id }}">
                                </td>
                                <td>
                                    <select name="attendances[{{ $index }}][status]" class="form-select" required>
                                        <option value="present">Present</option>
                                        <option value="late">Late</option>
                                        <option value="absent">Absent</option>
                                        <option value="half_day">Half Day</option>
                                        <option value="on_leave">On Leave</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="time" name="attendances[{{ $index }}][check_in]" class="form-control">
                                </td>
                                <td>
                                    <input type="time" name="attendances[{{ $index }}][check_out]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="attendances[{{ $index }}][notes]" class="form-control" placeholder="Optional">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection