@extends('layouts.app')

@section('title', 'Announcement Board')

@section('content')
<h2 class="mb-4">Company Announcements</h2>

@forelse($announcements as $item)
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <h5 class="mb-1">{{ $item->title }}</h5>
            @if($item->priority == 'urgent')
                <span class="badge bg-danger">Urgent</span>
            @elseif($item->priority == 'important')
                <span class="badge bg-warning text-dark">Important</span>
            @endif
        </div>
        <p class="text-muted small mb-2">
            {{ $item->creator->name ?? 'HR' }} · {{ $item->published_at?->diffForHumans() }}
        </p>
        <p class="mb-0">{{ $item->body }}</p>
    </div>
</div>
@empty
<div class="alert alert-info">No any announcement at the moment.</div>
@endforelse

{{ $announcements->links() }}
@endsection