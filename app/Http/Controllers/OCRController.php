<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class OCRController extends Controller
{
    public function performOCR(Request $request)
    {
        $image = $request->file('image');

        // Save the uploaded image to a temporary location
        $imagePath = $image->store('temp', 'public');
       
        // Perform OCR using Tesseract.js
        $output = shell_exec("tesseract public/{$imagePath} public/output.txt 2>&1");
return response()->json(['output' => $output]);

        // Read the extracted text from the output file
        $extractedText = Storage::disk('public')->get('output.txt');

        return response()->json(['text' => $extractedText]);
    }
}
