<?php

namespace App\Http\Controllers;
use PDF;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use GuzzleHttp\Client;
class ImageController extends Controller
{
    public function convertToPDF(Request $request){
        $text = $request->input('text');

        // Pass the $text variable to the view
        $pdf = PDF::loadView('image', ['text' => $text]);
    
        return $pdf->download('generated_pdf.pdf');
 
}
}
