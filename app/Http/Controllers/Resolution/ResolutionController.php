<?php

namespace App\Http\Controllers\Resolution;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resolution\ResolutionListResource;
use App\Models\Resolution\Resolution;
use App\Http\Requests\Resolution\StoreResolutionRequest;
use App\Http\Requests\Resolution\UpdateResolutionRequest;
use PDF;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ResolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->input('page', 1); // default 1
        $perPage = $request->input('perPage', 50); // default 50
        $queryString = $request->input('query', null);
        $sort = explode('.', $request->input('sort', 'id'));
        $order = $request->input('order', 'asc');

        $data = Resolution::query()
            ->with([])
            ->where(function ($query) use ($queryString) {
                if ($queryString && $queryString != '') {
                    // filter result
                    // $query->where('column', 'like', '%' . $queryString . '%')
                    //     ->orWhere('column', 'like', '%' . $queryString . '%');
                }
            })
            ->when(count($sort) == 1, function ($query) use ($sort, $order) {
                $query->orderBy($sort[0], $order);
            })
            ->paginate($perPage)
            ->withQueryString();

        $props = [
            'data' => ResolutionListResource::collection($data),
            'params' => $request->all(),
        ];

        if ($request->wantsJson()) {
            return json_encode($props);
        }

        if (count($data) <= 0 && $page > 1) {
            return redirect()->route('resolutions.index', ['page' => 1]);
        }

        return Inertia::render('Resolution/Index', $props);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Resolution/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResolutionRequest $request)
    {
        // dd($request);
        $data = $request->all();
      
        $res = Resolution::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'term' => $data['term'],
            'date' => $data['date'],


            'photo' => json_encode($data['photo']),
           
        ]);
        sleep(1);

        if ($request->has('photo')) {

            foreach ($request->photo as $photo) {
                Media::where('id', $photo['id'])
                    ->update([
                        'model_id' => $res->id
                    ]);
            }
        }

        if ($request->wantsJson()) {
            return new ResolutionListResource($data);
        }
        return redirect()->route('resolutions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = Resolution::findOrFail($id);
        if ($request->wantsJson()) {
            return new ResolutionListResource($data);
        }
        return Inertia::render('Admin/Resolution/Show', [
            'data' => $data
        ]);
    }


    public function view(Request $request)
{
    $data = Resolution::findOrFail($request->id);

    // Assuming $data->description contains the HTML content
    $htmlContent = $data->description;

    // Create a PDF instance and load the HTML content
    $pdf = PDF::loadHTML($htmlContent);

    // Use the stream method to display the PDF in the browser
    return $pdf->stream('report.pdf');
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $data = Resolution::findOrFail($id);
        if ($request->wantsJson()) {
            return new ResolutionListResource($data);
        }
        return Inertia::render('Admin/Resolution/Edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResolutionRequest $request, string $id)
    {
        $data = Resolution::findOrFail($id);
        $data->update($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return (new ResolutionListResource($data))
                ->response()
                ->setStatusCode(201);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $data = Resolution::findOrFail($id);
        $data->delete();
        sleep(1);

        if ($request->wantsJson()) {
            return response(null, 204);
        }
        return redirect()->back();
    }
}
