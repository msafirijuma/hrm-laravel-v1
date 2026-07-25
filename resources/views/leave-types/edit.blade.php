@extends('layouts.app')

@section('title', 'Edit Leave Type')

@section('content')
    <h2 class="mb-4">Edit Leave Type</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('leave-types.update', $leaveType) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label>Name of Leave</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $leaveType->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>Maximum Days Per Year</label>
                            <input type="number" name="max_days_per_year" class="form-control" 
                                   value="{{ old('max_days_per_year', $leaveType->max_days_per_year) }}" required min="1">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_paid" class="form-check-input" id="is_paid"
                               {{ old('is_paid', $leaveType->is_paid) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_paid">This leave is paid</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $leaveType->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                <a href="{{ route('leave-types.index') }}" class="btn btn-secondary btn-lg">Back</a>
            </form>
        </div>
    </div>
@endsection