@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <h2 class="mb-4">Activity Logs (Audit Trail)</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tarehe</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge bg-{{ $log->action == 'created' ? 'success' : ($log->action == 'updated' ? 'warning' : 'danger') }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ class_basename($log->model_type) }}</td>
                        <td>{{ $log->description }}</td>
                        <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">Hakuna activity logs bado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection