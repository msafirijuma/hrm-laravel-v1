@extends('layouts.app')

@section('title', 'Add New Leave Type')

@section('content')
    <h2 class="mb-4">Add New Leave Type</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('leave-types.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label>Name of Leave <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required 
                                   placeholder="Example: Annual Leave, Sick Leave">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>Maximum days per year <span class="text-danger">*</span></label>
                            <input type="number" name="max_days_per_year" class="form-control" required min="1">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_paid" class="form-check-input" id="is_paid" checked>
                        <label class="form-check-label" for="is_paid">This leave is paid</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="More description about this leave..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Save Leave Type</button>
                <a href="{{ route('leave-types.index') }}" class="btn btn-secondary btn-lg">Back</a>
            </form>
        </div>
    </div>
@endsection