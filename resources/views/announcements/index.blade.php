@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
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
                        <th>Priority</th>
                        <th>Status</th>
                        <th style="width: 120px; min-width: 120px;">Published</th>
                        <th style="width: 120px; min-width: 120px;">Expires</th>
                        <th style="width: 150px; min-width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $item)
                    <tr>
                        <td><strong>{{ $item->title }}</strong></td>
                        <td>
                            @if($item->priority == 'urgent')
                                <span class="badge bg-danger">Urgent</span>
                            @elseif($item->priority == 'important')
                                <span class="badge bg-warning text-dark">Important</span>
                            @else
                                <span class="badge bg-secondary">Normal</span>
                            @endif
                        </td>
                        <td>
                            @if($item->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $item->published_at?->format('d M Y H:m') ?? '—' }}</td>
                        <td>{{ $item->expires_at?->format('d M Y H:m') ?? '—' }}</td>
                        <td>
                            <a href="{{ route('announcements.edit', $item) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('announcements.destroy', $item) }}" method="POST" style="display:inline;">
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