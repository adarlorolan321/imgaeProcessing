<?php

namespace App\Http\Controllers;
use PDF;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use GuzzleHttp\Client;
class ImageController extends Controller
{
    public function convertToPDF(Request $request)
{

    $apiKey = 'AezpvGAyY6sdEWKMh9DHyo4r';
    $image = $request->input('image');

    dd($request);
    $imageUrl = $image->store('temp', 'public'); 
        $client = new Client();
        $response = $client->post('https://api.remove.bg/v1.0/removebg', [
            'headers' => [
                'X-Api-Key' => $apiKey,
            ],
            'form_params' => [
                'image_url' => $imageUrl,
            ],
        ]);

        $body = $response->getBody();
        // Save or return the modified image
        // For example, return the image URL
        return json_decode($body)->data->result->object_url;

 
}
}
