@extends('layouts.app')

@section('title', 'Add New Performance Review')

@section('content')
    <h2 class="mb-4">Add New Performance Review</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('performance-reviews.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Employee</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">-- Choose Employee --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">
                                {{ $emp->employee_number }} - {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Period (e.g., 2026-Q2)</label>
                    <input type="text" name="period" class="form-control" value="{{ $currentPeriod }}" required>
                </div>

                <div class="mb-3">
                    <label>Rating (1 - 5)</label>
                    <select name="rating" class="form-control" required>
                        <option value="">-- Choose Rating --</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} - {{ $i == 5 ? 'Excellent' : ($i == 4 ? 'Good' : ($i == 3 ? 'Average' : ($i == 2 ? 'Below Average' : 'Poor'))) }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-3">
                    <label>Strengths (Good tiding)</label>
                    <textarea name="strengths" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Weaknesses / Areas to Improve</label>
                    <textarea name="weaknesses" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label>Recommendations</label>
                    <textarea name="recommendations" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success btn-lg">Save Review</button>
            </form>
        </div>
    </div>
@endsection