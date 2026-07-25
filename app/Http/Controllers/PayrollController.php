<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    // ************************ SINGLE PAYROLL *********************
    public function index()
    {
        $payrolls = Payroll::with('employee.department')
                        ->orderBy('month', 'desc')
                        ->paginate(15);
        return view('payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::with('department')
                        ->where('status', 'active')
                        ->get();
        $currentMonth = Carbon::now()->format('Y-m');
        return view('payrolls.create', compact('employees', 'currentMonth'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|date_format:Y-m',
            'allowances'  => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $month = $request->month;

        if (Payroll::where('employee_id', $employee->id)->where('month', $month)->exists()) {
            return redirect()->back()->with('error', 'Payroll tayari imeshatengenezwa kwa mwezi huu.');
        }

        $payrollData = $this->calculatePayroll($employee, $request);
        Payroll::create($payrollData);

        return redirect()->route('payrolls.index')
                         ->with('success', 'Payroll imetengenezwa kwa ' . $employee->first_name);
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.department', 'employee.position');
        return view('payrolls.show', compact('payroll'));
    }

    // ************************ BULK PAYROLL ************************
    public function bulkCreate()
    {
        $departments = Department::all();
        $currentMonth = Carbon::now()->format('Y-m');
        return view('payrolls.bulk-create', compact('departments', 'currentMonth'));
    }

    public function bulkPreview(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $query = Employee::with('department', 'position')->where('status', 'active');

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->get();
        $month = $request->month;

        return view('payrolls.bulk-preview', compact('employees', 'month'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'employees' => 'required|array',
        ]);

        $created = 0;
        $month = $request->month;

        foreach ($request->employees as $employeeId => $data) {
            $employee = Employee::find($employeeId);
            if (!$employee) continue;

            if (Payroll::where('employee_id', $employee->id)->where('month', $month)->exists()) {
                continue;
            }

            $allowances = $data['allowances'] ?? 0;
            $otherDeductions = $data['other_deductions'] ?? 0;

            $payrollData = $this->calculatePayrollBulk($employee, $month, $allowances, $otherDeductions);
            Payroll::create($payrollData);
            $created++;
        }

        return redirect()->route('payrolls.index')
                         ->with('success', "Payroll imetengenezwa kwa wafanyakazi {$created} kwa mwezi {$month}");
    }

    // ******************* HELPER METHODS *****************
    private function calculatePayroll(Employee $employee, Request $request)
    {
        $basic = $employee->basic_salary ?? 0;
        $allowances = $request->input('allowances', 0);

        return $this->calculatePayrollBulk($employee, $request->month, $allowances, $request->input('other_deductions', 0));
    }

    private function calculatePayrollBulk(Employee $employee, $month, $allowances = 0, $otherDeductions = 0)
    {
        $basic = $employee->basic_salary ?? 0;
        $gross = $basic + $allowances;

        $nssf = $basic * 0.10;
        $nhif = $this->calculateNHIF($basic);
        $paye = $this->calculatePAYE($gross);

        $totalDeductions = $nssf + $nhif + $paye + $otherDeductions;
        $net = $gross - $totalDeductions;

        return [
            'employee_id'       => $employee->id,
            'month'             => $month,
            'basic_salary'      => $basic,
            'allowances'        => $allowances,
            'gross_salary'      => $gross,
            'nssf_employee'     => round($nssf, 2),
            'nhif'              => round($nhif, 2),
            'paye'              => round($paye, 2),
            'other_deductions'  => $otherDeductions,
            'net_salary'        => round($net, 2),
            'status'            => 'processed',
            'notes'             => 'Generated',
        ];
    }

    private function calculateNHIF($basic)
    {
        if ($basic <= 15000) return 150;
        if ($basic <= 20000) return 300;
        if ($basic <= 25000) return 400;
        if ($basic <= 30000) return 500;
        if ($basic <= 35000) return 600;
        if ($basic <= 40000) return 700;
        if ($basic <= 45000) return 800;
        if ($basic <= 50000) return 900;
        return 1000;
    }

    private function calculatePAYE($gross)
    {
        if ($gross <= 22500) return 0;
        return round($gross * 0.09, 2); // Flat 9% for simplicity
    }

    // Mark payroll as paid
    public function markAsPaid(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Payroll tayari imelipwa.');
        }

        $payroll->update([
            'status' => 'paid',
            'notes' => ($payroll->notes ? $payroll->notes . "\n" : '') . 'Marked as paid on ' . now()->format('d M Y')
        ]);

        return redirect()->back()
                        ->with('success', 'Payroll ya ' . $payroll->employee->first_name . ' imewekwa kama Paid!');
    }

    // Edit Payroll
    public function edit(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('payrolls.edit', compact('payroll'));
    }

    // Update Payroll
    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'allowances'       => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        $gross = $payroll->basic_salary + $request->input('allowances', 0);
        
        $nssf = $payroll->basic_salary * 0.10;
        $nhif = $this->calculateNHIF($payroll->basic_salary);
        $paye = $this->calculatePAYE($gross);

        $totalDeductions = $nssf + $nhif + $paye + $request->input('other_deductions', 0);
        $net = $gross - $totalDeductions;

        $payroll->update([
            'allowances'        => $request->input('allowances', 0),
            'other_deductions'  => $request->input('other_deductions', 0),
            'gross_salary'      => $gross,
            'nssf_employee'     => round($nssf, 2),
            'nhif'              => round($nhif, 2),
            'paye'              => round($paye, 2),
            'net_salary'        => round($net, 2),
            'notes'             => $request->notes,
        ]);

        return redirect()->route('payrolls.index')
                        ->with('success', 'Payroll imerekebishwa!');
    }

    // Download payslip
    public function downloadPayslip(Payroll $payroll)
    {
        $payroll->load('employee.department', 'employee.position');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasAnyRole(['Employee']) && $payroll->employee_id !== $user->employee->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('payrolls.pdf-payslip', compact('payroll'));
        
        return $pdf->download('payslip-' . $payroll->employee->employee_number . '-' . $payroll->month . '.pdf');
    }
    
    // Show all payslips for the authenticated employee
    public function myPayslips()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Huna taarifa za mfanyakazi.');
        }

        $payrolls = Payroll::where('employee_id', $employee->id)
                            ->orderBy('month', 'desc')
                            ->paginate(12);

        return view('payrolls.my-payslips', compact('payrolls'));
    }

    // Show individual payslip for employee
    public function showEmployeePayslip(Payroll $payroll)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->employee || $payroll->employee_id !== $user->employee->id) {
            abort(403, 'Huna ruhusa ya kuona payslip hii.');
        }

        $payroll->load('employee.department', 'employee.position');

        return view('payrolls.employee-payslip', compact('payroll'));
    }

    // Generate report
    public function reports()
    {
        
        $months = Payroll::select('month')
                        ->distinct()
                        ->orderBy('month', 'desc')
                        ->pluck('month');

        $totalPayroll = Payroll::sum('net_salary');
        $totalEmployeesPaid = Payroll::distinct('employee_id')->count('employee_id');

        return view('payrolls.reports', compact('months', 'totalPayroll', 'totalEmployeesPaid'));
    }

    // Monthly report
    public function monthlyReport($month = null)
    {
        if (!$month) {
            $month = Carbon::now()->format('Y-m');
        }

        $payrolls = Payroll::with('employee.department')
                        ->where('month', $month)
                        ->get();

        $summary = [
            'total_basic' => $payrolls->sum('basic_salary'),
            'total_allowances' => $payrolls->sum('allowances'),
            'total_gross' => $payrolls->sum('gross_salary'),
            'total_nssf' => $payrolls->sum('nssf_employee'),
            'total_nhif' => $payrolls->sum('nhif'),
            'total_paye' => $payrolls->sum('paye'),
            'total_deductions' => $payrolls->sum('nssf_employee') + $payrolls->sum('nhif') + 
                                $payrolls->sum('paye') + $payrolls->sum('other_deductions'),
            'total_net' => $payrolls->sum('net_salary'),
            'employee_count' => $payrolls->count(),
        ];

        return view('payrolls.monthly-report', compact('payrolls', 'month', 'summary'));
    }
}