<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    // ==================== EMPLOYEE ====================
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $employee = $user->employee;

        if (!$employee || $employee->status !== 'active') {
            return redirect()->route('dashboard')
                ->with('error', 'You cannot request a leave. You are currently inactive.');
        }

        $leaveTypes = LeaveType::all();
        return view('leave-requests.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $employee = Auth::user()->employee;

        if (!$employee || $employee->status !== 'active') {
            return redirect()->route('dashboard')
                ->with('error', 'You cannot request a leave. You are currently inactive.');
        }

        if (!$employee) {
            return redirect()->back()->with('error', 'Your details are incomplete.');
        }

        // Starting to Ending (Leave Request)
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        // Start Date to End Date 
        $daysRequested = $start->diffInDays($end) + 1;

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_requested' => $daysRequested,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('my-leaves')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function myLeaves()
    {
        $leaves = Auth::user()->employee->leaveRequests()
            ->with('leaveType')
            ->latest()
            ->get();
        return view('leave-requests.my-leaves', compact('leaves'));
    }

    // ==================== HR APPROVAL ====================
    public function pending()
    {
        $pendingLeaves = LeaveRequest::with(['employee.department', 'leaveType'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('leave-requests.pending', compact('pendingLeaves'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('leave-requests.pending')
            ->with('success', 'Leave request approved!');
    }

    // reject leave request with reason
    public function reject(Request $request, int $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:300',
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('leave-requests.pending')
            ->with('success', 'Leave request rejected!');
    }
    public function edit(LeaveRequest $leaveRequest)
    {
        // Only allow editing if pending belongs to the authenticated employee
        if ($leaveRequest->employee_id !== Auth::user()->employee->id || $leaveRequest->status !== 'pending') {
            abort(403, 'You are not allowed to edit this leave request.');
        }

        $leaveTypes = LeaveType::all();
        return view('leave-requests.edit', compact('leaveRequest', 'leaveTypes'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->employee_id !== Auth::user()->employee->id || $leaveRequest->status !== 'pending') {
            abort(403);
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $leaveRequest->update([
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_requested' => Carbon::parse($request->end_date)
                ->diffInDays(Carbon::parse($request->start_date)) + 1,
            'reason' => $request->reason,
        ]);

        return redirect()->route('my-leaves')
            ->with('success', 'Leave request updated successfully!');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->employee_id !== Auth::user()->employee->id || $leaveRequest->status !== 'pending') {
            abort(403);
        }

        $leaveRequest->delete();

        return redirect()->route('my-leaves')
            ->with('success', 'Leave request deleted successfully!');
    }
}