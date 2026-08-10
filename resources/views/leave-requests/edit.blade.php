@extends('layouts.app')

@section('title', 'Edit Leave Request')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Leave Request</h2>
                <a href="{{ route('my-leaves') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Leaves
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-edit"></i> Edit Leave Info</h5>
                </div>
                <div class="card-body p-4">
    
                    <form action="{{ route('leave-requests.update', $leaveRequest->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Leave Type -->
                        <div class="mb-3">
                            <label for="leave_type_id" class="form-label fw-bold">Leave Type</label>
                            <select class="form-select @error('leave_type_id') is-invalid @enderror" id="leave_type_id" name="leave_type_id" required>
                                <option value="">-- Choose leave Type --</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id', $leaveRequest->leave_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="row">
                            <!-- Start Date -->
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label fw-bold">Starting Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" 
                                       value="{{ old('start_date', $leaveRequest->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label fw-bold">Ending Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" 
                                       value="{{ old('end_date', $leaveRequest->end_date->format('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-4">
                            <label for="reason" class="form-label fw-bold">Reason</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="4" placeholder="Write your reason for applying leave here..." required>{{ old('reason', $leaveRequest->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('my-leaves') }}" class="btn btn-light border me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
