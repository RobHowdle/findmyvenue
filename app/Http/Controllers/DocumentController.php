<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DocumentController extends Controller
{
    public function store(Request $request)
    {

        dd($request->all());
        // Validate the main form data
        $request->validate([
            'title' => 'required|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
            // Additional validations as needed
        ]);

        // Retrieve the uploaded files from session
        $uploadedFiles = Session::get('uploaded_files', []);

        // Store the main form data, along with the uploaded files
        // (You may want to create a document model to save this)
        $document = new Document();
        $document->title = $request->title;
        $document->category = $request->category;
        $document->description = $request->description;
        $document->serviceable_id = $request->serviceable_id;
        $document->serviceable_type = $request->serviceable_type;
        $document->save();

        // Optionally, you can link the uploaded files to the document
        foreach ($uploadedFiles as $filePath) {
            // Logic to save the file path in the database, e.g.:
            $document->files()->create(['path' => $filePath]);
        }

        // Clear the uploaded files from the session after storing
        Session::forget('uploaded_files');

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,txt,png,jpg,jpeg|max:2048',
        ]);

        if ($request->file('file')) {
            $path = $request->file('file')->store('documents');

            $uploadedFiles = Session::get('uploaded_files', []);
            $uploadedFiles[] = $path;
            Session::put('uploaded_files', $uploadedFiles);

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'File upload failed.'], 400);
    }
}
