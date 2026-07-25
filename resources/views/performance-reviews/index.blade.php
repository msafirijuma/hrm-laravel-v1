@extends('layouts.app')

@section('title', 'Performance Reviews')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Performance Reviews</h2>
        <a href="{{ route('performance-reviews.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ongeza Review Mpya
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mfanyakazi</th>
                        <th>Idara</th>
                        <th>Period</th>
                        <th>Rating</th>
                        <th>Reviewed By</th>
                        <th>Tarehe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->employee->first_name }} {{ $review->employee->last_name }}</td>
                        <td>{{ $review->employee->department->name ?? '-' }}</td>
                        <td><strong>{{ $review->period }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $review->rating >= 4 ? 'success' : ($review->rating >= 3 ? 'warning' : 'danger') }}">
                                {{ $review->rating }} / 5
                            </span>
                        </td>
                        <td>{{ $review->reviewer->name ?? 'N/A' }}</td>
                        <td>{{ $review->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('performance-reviews.show', $review) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Angalia
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">Hakuna Performance Review bado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection