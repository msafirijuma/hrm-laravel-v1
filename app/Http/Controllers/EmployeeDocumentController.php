<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Notifications\DocumentUploadedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    public function index(Employee $employee)
    {
        $documents = $employee->documents()->latest()->get();
        return view('employee-documents.index', compact('employee', 'documents'));
    }

    public function create(Employee $employee)
    {
        return view('employee-documents.create', compact('employee'));
    }

    public function store(Request $request, Employee $employee)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type'  => 'required|in:contract,certificate,cv,id,other',
            'file'  => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120', // 5MB in kilobytes
            ],
            'notes' => 'nullable|string|max:500',
        ], [
            'title.required' => 'Document title is required.',
            'type.required'  => 'Choose document type.',
            'type.in'        => 'Invalid type.',
            'file.required'  => 'Choose a file.',
            'file.file'      => 'Invalid file.',
            'file.mimes'     => 'Accepted file format: PDF, DOC, DOCX, JPG, JPEG, PNG tu.',
            'file.max'       => 'File cannot exceeds 5MB.',
            'notes.max'      => 'Notes cannot exceeds 500 character.',
        ]);

        $file = $request->file('file');
        $path = $file->store('employee-documents/' . $employee->id, 'public');

        $document = EmployeeDocument::create([
            'employee_id' => $employee->id,
            'title'       => $request->title,
            'type'        => $request->type,
            'file_path'   => $path,
            'file_name'   => $file->getClientOriginalName(),
            'mime_type'   => $file->getClientMimeType(),
            'file_size'   => $file->getSize(),
            'uploaded_by' => auth()->id(),
            'notes'       => $request->notes,
        ]);

        if ($employee->user) {
            $employee->user->notify(new DocumentUploadedNotification($document));
        }

        return redirect()->route('employees.documents.index', $employee)
            ->with('success', 'Document uploaded successfully!');
    }

    public function download(EmployeeDocument $document)
    {
        // Security: HR/Admin or the employee themselves
        $user = auth()->user();
        if (!$user->hasAnyRole(['Super Admin', 'HR']) && $user->employee?->id !== $document->employee_id) {
            abort(403);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function destroy(EmployeeDocument $document)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'HR'])) {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    // My documents
    public function myDocuments()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            abort(403, 'You are not authorized to access this site.');
        }

        $documents = $employee->documents()->latest()->get();

        return view('employee-documents.my-documents', compact('documents', 'employee'));
    }
}
