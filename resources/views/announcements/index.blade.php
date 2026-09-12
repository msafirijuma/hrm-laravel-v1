@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="d-flex justify-content-between align-announcements-center mb-4">
    <h2>Company Announcements</h2>
    <a href="{{ route('announcements.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Announcement
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0" id="announcementTable">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Creator</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th style="width: 120px; min-width: 120px;">Published</th>
                        <th style="width: 120px; min-width: 120px;">Expires</th>
                        <th style="width: 150px; min-width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr>
                        <td><strong>{{ $announcement->title }}</strong></td>
                        <td>
                            <div>{{ $announcement->creator->name ?? '—' }}</div>
                            @if($announcement->creator && $announcement->creator->roles->isNotEmpty())
                                @php $role = $announcement->creator->roles->first()->name; @endphp
                                <small class="badge bg-{{ $role === 'Super Admin' ? 'dark' : ($role === 'HR' ? 'info' : 'secondary') }} mt-1">
                                    {{ $role }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($announcement->priority == 'urgent')
                                <span class="badge bg-danger">Urgent</span>
                            @elseif($announcement->priority == 'important')
                                <span class="badge bg-warning text-dark">Important</span>
                            @else
                                <span class="badge bg-secondary">Normal</span>
                            @endif
                        </td>
                        <td>
                            @if($announcement->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $announcement->published_at?->format('d M Y H:m') ?? '—' }}</td>
                        <td>{{ $announcement->expires_at?->format('d M Y H:m') ?? '—' }}</td>
                        <td>
                            <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Futa?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    {{-- <tr>
                        <td colspan="6" class="text-center py-4">No any announcement yet.</td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{ $announcements->links() }}
@endsection