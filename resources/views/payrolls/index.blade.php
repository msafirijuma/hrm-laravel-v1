@extends('layouts.app')

@section('title', 'Payroll Records')

@section('content')
    <div class="d-md-flex justify-content-between align-items-center mb-4">
        <h2>Payroll Records</h2>
        <div class="d-flex">
            <a href="{{ route('payrolls.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus"></i> Single Payroll
            </a>
            @php
                $currentMonth = now()->format('Y-m');
                $hasCurrentMonthPayrolls = \App\Models\Payroll::where('month', $currentMonth)->exists();
            @endphp

            @if(!$hasCurrentMonthPayrolls)
                <a href="{{ route('payrolls.bulk.create') }}" class="btn btn-success">
                    <i class="fas fa-copy"></i> Bulk Payroll Generation
                </a>
            @else
                <button class="btn btn-secondary" disabled title="Payrolls for this month are already generated.">
                    <i class="fas fa-lock"></i> Bulk Already Generated
                </button>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="payrollTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Month</th>
                            <th>payroll</th>
                            <th>Department</th>
                            <th>Basic Salary</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th style="width: 200px; min-width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td><strong>{{ $payroll->month }}</strong></td>
                            <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                            <td>{{ $payroll->employee->department->name ?? '-' }}</td>
                            <td>TZS {{ number_format($payroll->basic_salary, 0) }}</td>
                            <td><strong>TZS {{ number_format($payroll->net_salary, 0) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $payroll->status == 'paid' ? 'success' : ($payroll->status == 'processed' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            <td>

                                <!-- Mark as paid Button -->
                                @if($payroll->status !== 'paid')
                                    <form id="mark-payroll-form-{{ $payroll->id }}" action="{{ route('payrolls.mark-paid', $payroll) }}" method="POST" style="display:inline;">
                                        @csrf
                                    </form>

                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="triggerMarkAsPaid({{ $payroll->id }}, '{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}')" class="btn btn-sm btn-danger" title="Mark payroll as paid">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </button>
                                @endif

                                <!-- View Button -->
                                <button type="button" onclick="triggerView('{{ route('payrolls.show', $payroll) }}')" class="btn btn-sm btn-info text-white" title="View Payroll">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Edit Button -->
                                <button type="button" onclick="triggerEdit('{{ route('payrolls.edit', $payroll) }}')" class="btn btn-sm btn-warning" title="Edit Payroll">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete Form -->
                                <form id="delete-payroll-form-{{ $payroll->id }}" action="{{ route('payrolls.destroy', $payroll) }}" method="POST" style="display:none !important;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <!-- Delete Button -->
                                <button type="button" onclick="triggerDelete({{ $payroll->id }}, '{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}' , '{{ $payroll->month }}')" class="btn btn-sm btn-danger" title="Delete Payroll">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="8" class="text-center py-5">No payroll has been created yet</td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Loader function during page navigation
    function showPageLoader(message) {
        Swal.fire({
            title: 'Please wait...',
            text: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // SweetAlert during view action
    function triggerView(url) {
        showPageLoader('We are loading payroll details...');
        window.location.href = url;
    }

    // SweetAlert during edit action
    function triggerEdit(url) {
        showPageLoader('We are preparing edit payroll form...');
        window.location.href = url;
    }

    // SweetAlert confirmation (mark payroll as paid)
    function triggerMarkAsPaid(id, employee) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You will be marking payroll for ${employee} as paid.`,
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Mark!',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Loader during the deletion process
                Swal.fire({
                    title: 'Marking...',
                    text: 'Please wait while payroll is being marked as paid.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('mark-payroll-form-' + id).submit();
            }
        });
    }

    // SweetAlert confirmation during deletion of payroll
    function triggerDelete(id, employee, month) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You will delete the payroll record '${month}' for David Kimaro from the system. This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Loader during the deletion process
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while payroll data is being deleted.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('delete-payroll-form-' + id).submit();
            }
        });
    }
</script>
@endsection