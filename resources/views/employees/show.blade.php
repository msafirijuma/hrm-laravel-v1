@extends('layouts.app')

@section('title', 'Profile - ' . $employee->first_name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if($employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}" 
                             class="rounded-circle mb-3" width="180" height="180" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 180px; height: 180px;">
                            <i class="fas fa-user fa-5x text-white"></i>
                        </div>
                    @endif
                    
                    <h4>{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                    <p class="text-muted">{{ $employee->employee_number }}</p>
                    
                    <span class="badge bg-{{ $employee->status == 'active' ? 'success' : 'danger' }} fs-6">
                        {{ ucfirst($employee->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Employee Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Department:</strong><br>
                            {{ $employee->department->name ?? '—' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Position:</strong><br>
                            {{ $employee->position->name ?? '—' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Phone:</strong><br>
                            {{ $employee->phone }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong><br>
                            {{ $employee->email }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Date of Birth:</strong><br>
                            {{ $employee->date_of_birth ? $employee->date_of_birth->format('d M Y') : '—' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Hire Date:</strong><br>
                            {{ $employee->date_hired ? $employee->date_hired->format('d M Y') : '—' }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Gender:</strong><br>
                            {{ $employee->gender }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Basic Salary:</strong><br>
                            <strong>TZS {{ number_format($employee->basic_salary, 0) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to Employees</a>
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Edit Info</a>
    </div>
</div>
@endsection