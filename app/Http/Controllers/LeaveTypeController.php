<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'max_days_per_year' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        LeaveType::create([
            'name'               => $request->input('name'),
            'max_days_per_year'  => $request->input('max_days_per_year'),
            'is_paid'            => $request->boolean('is_paid'),
            'description'        => $request->input('description'),
        ]);

        return redirect()->route('leave-types.index')
                         ->with('success', 'Aina ya likizo imeongezwa kwa mafanikio!');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'max_days_per_year' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $leaveType->update([
            'name'               => $request->input('name'),
            'max_days_per_year'  => $request->input('max_days_per_year'),
            'is_paid'            => $request->boolean('is_paid'),
            'description'        => $request->input('description'),
        ]);

        return redirect()->route('leave-types.index')
                         ->with('success', 'Aina ya likizo imehaririwa kwa mafanikio!');
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return redirect()->route('leave-types.index')
                         ->with('success', 'Aina ya likizo imefutwa!');
    }
}