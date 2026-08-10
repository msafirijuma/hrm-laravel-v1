@extends('layouts.app')

@section('title', 'Apply Leave')

@section('content')
    <div class="container">
        <h2 class="mb-4">Apply Leave</h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Leave Type</label>
                                <select name="leave_type_id" class="form-control" required>
                                    <option value="" disabled selected>-- Choose Leave Type --</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }} ({{ $type->max_days_per_year }} Days)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Starting Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Ending Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Reason</label>
                        <textarea name="reason" class="form-control" rows="4" required placeholder="Explain why you apply leave..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                    <a href="{{ route('my-leaves') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection