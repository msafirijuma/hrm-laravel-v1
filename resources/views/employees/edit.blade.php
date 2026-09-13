@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Employee's Details</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" 
                                       value="{{ old('first_name', $employee->first_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" 
                                       value="{{ old('last_name', $employee->last_name) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" 
                                       value="{{ old('email', $employee->email) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" 
                                       value="{{ old('phone', $employee->phone) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hired Date <span class="text-danger">*</span></label>
                                <input type="date" name="date_hired" class="form-control" 
                                       value="{{ old('date_hired', $employee->date_hired?->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contract End Date</label>
                                <input type="date" name="contract_end_date" class="form-control"
                                    value="{{ old('contract_end_date', $employee->contract_end_date?->format('Y-m-d') ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control" required>
                                    <option value="">-- Choose Department --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $dept->id == $employee->department_id ? 'selected' : '' }}>
                                            {{ $dept->name }} ({{ $dept->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Position <span class="text-danger">*</span></label>
                                <select name="position_id" class="form-control" required>
                                    <option value="">-- Choose Department  --</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->id }}" {{ $pos->id == $employee->position_id ? 'selected' : '' }}>
                                            {{ $pos->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Basic Salary (TZS)</label>
                                <input type="number" name="basic_salary" class="form-control" step="0.01"
                                       value="{{ old('basic_salary', $employee->basic_salary) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ $employee->user && $employee->user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date of Birth <span class="text-muted">(Optional)</span></label>
                                <input type="date" name="date_of_birth" class="form-control" 
                                    value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status', $employee->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive" {{ old('status', $employee->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                    <option value="terminated" {{ old('status', $employee->status ?? '') == 'terminated' ? 'selected' : '' }}>
                                        Terminated
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Reason for Status Change (Optional)</label>
                                <input type="text" name="status_reason" class="form-control" placeholder="e.g. End of contract, Resignation...">
                            </div>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label class="form-label">New Image (Optional)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        
                        @if($employee->photo)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $employee->photo) }}" 
                                     class="img-thumbnail" width="150" alt="Current Photo">
                                <p class="text-muted small mt-1">Current Image</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection