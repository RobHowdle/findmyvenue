<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DocumentController extends Controller
{
    public function storeDocument(Request $request)
    {
        // Validate the main form data
        $request->validate([
            'title' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'array',
            'description' => 'nullable|string',
        ]);

        // Retrieve the uploaded files from session
        $uploadedFiles = Session::get(
            'uploaded_files',
            []
        );
        $user = auth()->user()->load('roles');
        $role = $user->roles->first()->name;
        $service = $user->otherService((ucfirst($role)))->first();

        if ($service) {
            $serviceableId = $service->id;
            $serviceableType = get_class($service);
            $serviceType = $service->otherServiceList()->first()->service_name;

            foreach ($uploadedFiles as $filePath) {
                $document = new Document();
                $document->user_id = $user->id;
                $document->serviceable_type = $serviceableType;
                $document->service = $serviceType;
                $document->serviceable_id = $serviceableId;
                $document->title = $request->title;
                $document->description = $request->description;
                $document->category = json_encode($request->tags);
                $document->file_path = $filePath;
                $document->save();
            }
        } else {
            return response()->json(['sucess' => false, 'message' => 'No service associated with this user'], 400);
        }

        Session::forget('uploaded_files');

        return response()->json(['success' => true, 'message' => 'Document uploaded successfully!']);
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,txt,png,jpg,jpeg|max:2048',
        ]);

        if ($request->file('file')) {
            $userId = auth()->id();
            $user = auth()->user();
            $userType = strtolower($user->getRoleNames()->first());

            $customPath = "documents/{$userType}/{$userId}";
            $path = $request->file('file')->store($customPath);

            $uploadedFiles = Session::get('uploaded_files', []);
            $uploadedFiles[] = $path;
            Session::put('uploaded_files', $uploadedFiles);

            return response()->json(['success' => true, 'path' => $path]);
        }

        return response()->json(['success' => false, 'message' => 'File upload failed.'], 400);
    }
}
