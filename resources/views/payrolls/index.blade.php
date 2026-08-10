@extends('layouts.app')

@section('title', 'Payroll Records')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Payroll Records</h2>
        <div>
            <a href="{{ route('payrolls.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus"></i> Single Payroll
            </a>
            <a href="{{ route('payrolls.bulk.create') }}" class="btn btn-success">
                <i class="fas fa-copy"></i> Bulk Payroll
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="payrollTable">
                <thead class="table-dark">
                    <tr>
                        <th>Month</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Basic Salary</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                            <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if($payroll->status !== 'paid')
                                <form action="{{ route('payrolls.mark-paid', $payroll) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Are you sure you paid this payrol?')">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </button>
                                </form>
                            @endif

                            <!-- Delete -->
                            <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you canceling this payroll? This action cannot be undone!')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
@endsection