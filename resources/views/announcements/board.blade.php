@extends('layouts.app')

@section('title', 'Announcement Board')

@section('content')
<style>
    .announcement-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .announcement-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.12) !important;
    }
    .announcement-card.priority-urgent {
        border-left-color: #dc3545;
    }
    .announcement-card.priority-important {
        border-left-color: #ffc107;
    }
    .announcement-card.priority-normal {
        border-left-color: #0d6efd;
    }
    .announcement-card .priority-bar {
        height: 4px;
        transition: height 0.3s ease;
    }
    .announcement-card:hover .priority-bar {
        height: 6px;
    }
    .badge-animated {
        transition: transform 0.2s ease;
    }
    .announcement-card:hover .badge-animated {
        transform: scale(1.05);
    }
    .announcement-body {
        transition: color 0.2s ease;
    }
    .announcement-card:hover .announcement-body {
        color: #333 !important;
    }
    .pagination {
    display: flex;
    gap: 6px;                    /* space kati ya buttons */
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.pagination .page-item .page-link 
border-radius: 8px !important;
    min-width: 36px;
    text-align: center;{
    border-radius: 8px !important;
    min-width: 36px;
    text-align: center;
    border: 1px solid #dee2e6;
    color: #495057;
    padding: 0.4rem 0.75rem;
    transition: all 0.2s ease;
}

.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    color: #0d6efd;
}

.pagination .page-item.disabled .page-link {
    color: #adb5bd;
    background-color: #f8f9fa;
}
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between  mb-4">
        <div>
            <h2 class="fw-bold mb-1">Company Announcements</h2>
            <p class="text-muted mb-0">Announcements from Management</p>
        </div>
    </div>

    <div class="row justify-content-start">
        <div class="col-lg-9">
            @forelse($announcements as $announcement)
            <div class="card announcement-card border-0 shadow-sm mb-4 overflow-hidden priority-{{ $announcement->priority }}">
                <!-- Priority bar -->
                <div class="priority-bar" style="background: {{ $announcement->priority == 'urgent' ? '#dc3545' : ($announcement->priority == 'important' ? '#ffc107' : '#0d6efd') }};"></div>

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h4 class="fw-bold mb-1">{{ $announcement->title }}</h4>
                            <div class="d-md-flex gap-2 text-muted small">
                                <span>
                                    <i class="fas fa-user-circle me-1"></i>
                                    {{ $announcement->creator->name ?? 'HR' }}
                                </span>
                                <span>•</span>
                                <p>
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $announcement->published_at?->diffForHumans() ?? $announcement->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <div>
                            @if($announcement->priority == 'urgent')
                                <span class="badge bg-danger rounded-pill px-3 py-2 badge-animated">
                                    <i class="fas fa-exclamation-circle me-1"></i> Urgent
                                </span>
                            @elseif($announcement->priority == 'important')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 badge-animated">
                                    <i class="fas fa-star me-1"></i> Important
                                </span>
                            @else
                                <span class="badge bg-primary rounded-pill px-3 py-2 badge-animated">Normal</span>
                            @endif
                        </div>
                    </div>

                    <div class="announcement-body text-secondary" style="line-height: 1.7; font-size: 1.05rem;">
                        {!! nl2br(e($announcement->body)) !!}
                    </div>

                    @if($announcement->expires_at)
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-hourglass-end me-1"></i>
                            Expires at: {{ $announcement->expires_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No any announcement at the moment.</h5>
                    <p class="text-muted mb-0">New announcement will appear here.</p>
                </div>
            </div>
            @endforelse

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-4">
                <div class="text-muted small">
                    Showing {{ $announcements->firstItem() ?? 0 }}
                    to {{ $announcements->lastItem() ?? 0 }}
                    of {{ $announcements->total() }} results
                </div>

                <div>
                    {{ $announcements->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection