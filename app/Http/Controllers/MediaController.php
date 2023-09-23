<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function upload(Request $request)
    {

        $className = 'App\\Models\\' . $request->input('model');
        $model = new $className();
        $model->id = $request->input('model_id', 0);
        $model->exists = true;

        if ($request->has('singleMedia') && $request->boolean('singleMedia', false)) {
            $model->clearMediaCollection($request->input('collection', 'photo'));
        }

        $media = $model->addMedia($request->file('file'))->toMediaCollection($request->input('collection', 'photo'));
        $media['url'] = $media->getUrl();
        $media['src'] = $media->getUrl();
        return response()->json($media, 202);
        dd($media);
    }
}
