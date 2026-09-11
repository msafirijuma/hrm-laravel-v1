<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\Employee;
use App\Notifications\PerformanceReviewNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = PerformanceReview::with(['employee.department', 'reviewer']);

        // Manager → only team members 
        if ($user->hasRole('Manager') && $user->employee) {
            $teamIds = Employee::where('department_id', $user->employee->department_id)
                ->pluck('id');
            $query->whereIn('employee_id', $teamIds);
        }

        $reviews = $query->latest()->paginate(15);

        return view('performance-reviews.index', compact('reviews'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('Manager') && $user->employee) {
            // Manager → only team members (excluding manager himself/herself)
            $employees = Employee::where('department_id', $user->employee->department_id)
                ->where('id', '!=', $user->employee->id)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->get();
        } else {
            // HR / Super Admin 
            $employees = Employee::where('status', 'active')
                ->orderBy('first_name')
                ->get();
        }

        $currentPeriod = now()->format('Y') . '-Q' . ceil(now()->month / 3);

        return view('performance-reviews.create', compact('employees', 'currentPeriod'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'period'          => 'required|string',
            'rating'          => 'required|integer|min:1|max:5',
            'strengths'       => 'required|string',
            'weaknesses'      => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security: Manager write review only for employees in their department
        if ($user->hasRole('Manager') && $user->employee) {
            $employee = Employee::findOrFail($request->employee_id);
            if ($employee->department_id !== $user->employee->department_id) {
                abort(403, 'You are not authorized to review this employee.');
            }
        }

        $review  = PerformanceReview::create([
            'employee_id'     => $request->employee_id,
            'reviewed_by'     => Auth::id(),
            'period'          => $request->period,
            'rating'          => $request->rating,
            'strengths'       => $request->strengths,
            'weaknesses'      => $request->weaknesses,
            'recommendations' => $request->recommendations,
            'status'          => 'completed',
        ]);

        $review->load('employee.user');

        if ($review->employee && $review->employee->user) {
            $review->employee->user->notify(new PerformanceReviewNotification($review));
        }

        return redirect()->route('performance-reviews.index')
            ->with('success', 'Performance Review saved successfully!');
    }

    public function show(PerformanceReview $performanceReview)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security check kwa Manager
        if ($user->hasRole('Manager') && $user->employee) {
            if ($performanceReview->employee->department_id !== $user->employee->department_id) {
                abort(403, 'You are not authorized to view this review.');
            }
        }

        $performanceReview->load('employee.department', 'employee.position', 'reviewer');
        return view('performance-reviews.show', compact('performanceReview'));
    }
}
