@extends('layouts.app')

@section('title', 'Performance Review - {{ $performanceReview->employee->first_name }}')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Performance Review</h2>
            <a href="{{ route('performance-reviews.index') }}" class="btn btn-secondary">
                ← Rudi kwenye Reviews
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    {{ $performanceReview->employee->first_name }} {{ $performanceReview->employee->last_name }} 
                    - {{ $performanceReview->period }}
                </h5>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Mfanyakazi:</strong> {{ $performanceReview->employee->first_name }} {{ $performanceReview->employee->last_name }}<br>
                        <strong>Namba:</strong> {{ $performanceReview->employee->employee_number }}<br>
                        <strong>Idara:</strong> {{ $performanceReview->employee->department->name ?? '-' }}<br>
                        <strong>Cheo:</strong> {{ $performanceReview->employee->position->name ?? '-' }}
                    </div>
                    <div class="col-md-6 text-md-end">
                        <strong>Reviewed By:</strong> {{ $performanceReview->reviewer->name ?? 'N/A' }}<br>
                        <strong>Tarehe:</strong> {{ $performanceReview->created_at->format('d M Y H:i') }}<br>
                        <strong>Rating:</strong> 
                        <span class="badge bg-{{ $performanceReview->rating >= 4 ? 'success' : ($performanceReview->rating >= 3 ? 'warning' : 'danger') }} fs-5">
                            {{ $performanceReview->rating }} / 5
                        </span>
                    </div>
                </div>

                <hr>

                <!-- Strengths -->
                <div class="mb-4">
                    <h5 class="text-success"><i class="fas fa-thumbs-up"></i> Strengths (Mambo Mazuri)</h5>
                    <div class="p-3 bg-light border rounded">
                        {{ $performanceReview->strengths }}
                    </div>
                </div>

                <!-- Weaknesses -->
                @if($performanceReview->weaknesses)
                <div class="mb-4">
                    <h5 class="text-warning"><i class="fas fa-exclamation-triangle"></i> Areas to Improve</h5>
                    <div class="p-3 bg-light border rounded">
                        {{ $performanceReview->weaknesses }}
                    </div>
                </div>
                @endif

                <!-- Recommendations -->
                @if($performanceReview->recommendations)
                <div class="mb-4">
                    <h5 class="text-info"><i class="fas fa-lightbulb"></i> Recommendations</h5>
                    <div class="p-3 bg-light border rounded">
                        {{ $performanceReview->recommendations }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection