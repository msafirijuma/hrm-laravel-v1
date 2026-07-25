@extends('layouts.app')

@section('title', 'Activity Detail')

@section('content')
    <h2>Activity Detail</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Tarehe:</strong> {{ $activityLog->created_at->format('d M Y H:i:s') }}</p>
            <p><strong>User:</strong> {{ $activityLog->user->name ?? 'System' }}</p>
            <p><strong>Action:</strong> <strong>{{ ucfirst($activityLog->action) }}</strong></p>
            <p><strong>Model:</strong> {{ class_basename($activityLog->model_type) }} #{{ $activityLog->model_id }}</p>
            <p><strong>Description:</strong> {{ $activityLog->description }}</p>
            <p><strong>IP:</strong> {{ $activityLog->ip_address }}</p>

            @if($activityLog->old_values || $activityLog->new_values)
            <hr>
            <h5>Changes</h5>
            <pre>{{ json_encode($activityLog->new_values ?? $activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
            @endif
        </div>
    </div>
@endsection