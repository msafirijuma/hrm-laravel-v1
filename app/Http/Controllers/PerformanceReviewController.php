<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        $reviews = PerformanceReview::with('employee.department', 'reviewer')
                        ->latest()
                        ->paginate(15);

        return view('performance-reviews.index', compact('reviews'));
    }

    public function create()
    {
        $employees = Employee::with('department')
                        ->where('status', 'active')
                        ->get();
        
        $currentPeriod = Carbon::now()->format('Y') . '-Q' . ceil(Carbon::now()->month / 3);

        return view('performance-reviews.create', compact('employees', 'currentPeriod'));
    }

    public function store(Request $request)
    {

        $user = auth::user();

        $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'period'          => 'required|string',
            'rating'          => 'required|integer|min:1|max:5',
            'strengths'       => 'required|string',
            'weaknesses'      => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        PerformanceReview::create([
            'employee_id'     => $request->employee_id,
            'reviewed_by'     => $user->id,
            'period'          => $request->period,
            'rating'          => $request->rating,
            'strengths'       => $request->strengths,
            'weaknesses'      => $request->weaknesses,
            'recommendations' => $request->recommendations,
            'status'          => 'completed',
        ]);

        return redirect()->route('performance-reviews.index')
                         ->with('success', 'Performance Review imesajiliwa kwa mafanikio!');
    }

    public function show(PerformanceReview $performanceReview)
    {
        $performanceReview->load('employee.department', 'employee.position', 'reviewer');
        return view('performance-reviews.show', compact('performanceReview'));
    }
}