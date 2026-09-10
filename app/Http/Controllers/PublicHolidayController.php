<?php

namespace App\Http\Controllers;

use App\Models\PublicHoliday;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{
    public function index()
    {
        $holidays = PublicHoliday::orderBy('date')->paginate(20);
        return view('public-holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('public-holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date|unique:public_holidays,date',
            'is_recurring' => 'nullable|boolean',
            'description'  => 'nullable|string',
        ]);

        PublicHoliday::create([
            'name'         => $request->name,
            'date'         => $request->date,
            'is_recurring' => $request->boolean('is_recurring'),
            'description'  => $request->description,
        ]);

        return redirect()->route('public-holidays.index')
            ->with('success', 'Public Holiday added successfully!');
    }

    public function edit(PublicHoliday $publicHoliday)
    {
        return view('public-holidays.edit', compact('publicHoliday'));
    }

    public function update(Request $request, PublicHoliday $publicHoliday)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date|unique:public_holidays,date,' . $publicHoliday->id,
            'is_recurring' => 'nullable|boolean',
            'description'  => 'nullable|string',
        ]);

        $publicHoliday->update([
            'name'         => $request->name,
            'date'         => $request->date,
            'is_recurring' => $request->boolean('is_recurring'),
            'description'  => $request->description,
        ]);

        return redirect()->route('public-holidays.index')
            ->with('success', 'Public Holiday updated successfully!');
    }

    public function destroy(PublicHoliday $publicHoliday)
    {
        $publicHoliday->delete();
        return redirect()->route('public-holidays.index')
            ->with('success', 'Public Holiday deleted successfully!');
    }

    // Calendar View
    public function calendar()
    {
        $holidays = PublicHoliday::orderBy('date')->get();
        return view('public-holidays.calendar', compact('holidays'));
    }
}
