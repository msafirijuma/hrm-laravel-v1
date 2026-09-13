@extends('layouts.app')

@section('title', 'My Documents')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">My Documents</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover mb-0" id="employeeTable">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 120px; min-width: 120px">Title</th>
                        <th>Type</th>
                        <th>File</th>
                        <th style="width: 120px; min-width: 120px">Uploaded</th>
                        <th style="width: 120px; min-width: 120px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>
                            <strong>{{ $doc->title }}</strong>
                            @if($doc->notes)
                                <br><small class="text-muted">{{ $doc->notes }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($doc->type) }}</span>
                        </td>
                        <td>{{ $doc->file_name }}</td>
                        <td>{{ $doc->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                            No any documents yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection