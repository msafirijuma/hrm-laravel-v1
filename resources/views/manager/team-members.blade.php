@extends('layouts.app')

@section('title', 'My Team Members')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Team Members</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped mb-0" id="employeeTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Employee No</th>
                            <th>Position</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamMembers as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $member->first_name }} {{ $member->last_name }}</strong>
                            </td>
                            <td>{{ $member->employee_number }}</td>
                            <td>{{ $member->position->name ?? '-' }}</td>
                            <td>
                                @if($member->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($member->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No team members found.
                            </td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection