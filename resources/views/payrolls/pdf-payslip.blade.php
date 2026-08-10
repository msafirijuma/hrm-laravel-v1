<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->month }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .net { background-color: #d4edda; font-weight: bold; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ config('app.name', 'Your Company Ltd') }}</h2>
        <p>Dar es Salaam, Tanzania | TIN: 123-456-789</p>
        <h3>PAYSLIP - {{ $payroll->month }}</h3>
    </div>

    <p><strong>Employee:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
    <p><strong>Emp. Number:</strong> {{ $payroll->employee->employee_number }}</p>
    <p><strong>Department:</strong> {{ $payroll->employee->department->name ?? '-' }}</p>

    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th>Notes</th>
            <th class="right">Amount (TZS)</th>
        </tr>
        <tr>
            <td>Basic Salary</td>
            <td class="right">{{ number_format($payroll->basic_salary, 0) }}</td>
        </tr>
        <tr>
            <td>Allowances</td>
            <td class="right">{{ number_format($payroll->allowances, 0) }}</td>
        </tr>
        <tr>
            <td><strong>Gross Salary</strong></td>
            <td class="right"><strong>{{ number_format($payroll->gross_salary, 0) }}</strong></td>
        </tr>
        <tr>
            <td>NSSF (10%)</td>
            <td class="right">{{ number_format($payroll->nssf_employee, 0) }}</td>
        </tr>
        <tr>
            <td>NHIF</td>
            <td class="right">{{ number_format($payroll->nhif, 0) }}</td>
        </tr>
        <tr>
            <td>PAYE</td>
            <td class="right">{{ number_format($payroll->paye, 0) }}</td>
        </tr>
        <tr>
            <td>Other Deductions</td>
            <td class="right">{{ number_format($payroll->other_deductions, 0) }}</td>
        </tr>
        <tr class="net">
            <td><strong>NET SALARY</strong></td>
            <td class="right"><strong>{{ number_format($payroll->net_salary, 0) }}</strong></td>
        </tr>
    </table>

    <p style="margin-top: 50px; text-align: center; font-size: 12px;">
        Thank you for your work. Payment has been made in accordance with company policy.<br>
        Printed on: {{ now()->format('d M Y H:i') }}
    </p>
</body>
</html>