<?php

namespace App\Http\Controllers\Ordinance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ordinance\OrdinanceListResource;
use App\Models\Ordinance\Ordinance;
use App\Http\Requests\Ordinance\StoreOrdinanceRequest;
use App\Http\Requests\Ordinance\UpdateOrdinanceRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class OrdinanceController extends Controller
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

        $data = Ordinance::query()
            ->with([])
            ->where(function ($query) use ($queryString) {
                if ($queryString && $queryString != '') {
                    // filter result
                    $query->where('date', 'like', '%' . $queryString . '%')
                    ->orWhere('title', 'like', '%' . $queryString . '%')
                    ->orWhere('status', 'like', '%' . $queryString . '%')
                    ->orWhere('ordinance_no', 'like', '%' . $queryString . '%')
                    ->orWhere('term', 'like', '%' . $queryString . '%');
                }
            })
            ->when(count($sort) == 1, function ($query) use ($sort, $order) {
                $query->orderBy($sort[0], $order);
            })
            ->paginate($perPage)
            ->withQueryString();

        $props = [
            'data' => OrdinanceListResource::collection($data),
            'params' => $request->all(),
        ];

        if ($request->wantsJson()) {
            return json_encode($props);
        }

        if(count($data) <= 0 && $page > 1)
        {
            return redirect()->route('ordinances.index', ['page' => 1]);
        }

        return Inertia::render('Ordinance/Index', $props);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Ordinance/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdinanceRequest $request)
    {
        $data = $request->all();

        $res = Ordinance::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'term' => $data['term'],
            'date' => $data['date'],
            'status' => $data['status'],
            'ordinance_no' => $data['ordinance_no'],
            'photo' => json_encode($data['photo']),

        ]);
        sleep(1);
        if ($request->has('photo')) {
            $photoIds = array_column($request->photo, 'id');

            // Update the Media records in a single query
            DB::table('media')
                ->whereIn('id', $photoIds) // Filter by the photo IDs
                ->where('model_type', Ordinance::class) // Filter by the model type
                ->where('model_id', 0) // Filter by the model_id being 0
                ->update(['model_id' => $res->id]); // Set the new model_id
        }
        if ($request->wantsJson()) {
            return new OrdinanceListResource($data);
        }
        return redirect()->route('ordinances.index');
    }

    public function view(Request $request)
    {
        $data = Ordinance::with([])->findOrFail($request->id);
        //    dd($data);

        if ($data && $data->status == 'New') {
            $htmlContent = $data->description;

            // Create a PDF instance and load the HTML content
            // $pdf = PDF::loadHTML($htmlContent);
            $pdf = PDF::loadView('image', compact('data'));
            // Use the stream method to display the PDF in the browser
            return $pdf->stream('report.pdf');
        } else {


            $photos = $data->file_url;

            if ($photos == '' || $photos == null) {
                $photos = [];
            }


            // Create a PDF instance


            $pdf = PDF::loadView('pdf_template', compact('photos'));

            // Use the stream method to display the PDF in the browser
            return $pdf->stream('report.pdf');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = Ordinance::findOrFail($id);
        if ($request->wantsJson()) {
            return new OrdinanceListResource($data);
        }
        return Inertia::render('Admin/Ordinance/Show', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $data = Ordinance::findOrFail($id);
        if ($request->wantsJson()) {
            return new OrdinanceListResource($data);
        }
        return Inertia::render('Ordinance/Edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdinanceRequest $request, string $id)
    {
        $data = Ordinance::findOrFail($id);
        $data->update($request->validated());
        sleep(1);

        if ($request->wantsJson()) {
            return (new OrdinanceListResource($data))
                ->response()
                ->setStatusCode(201);
        }

        return redirect()->route('ordinances.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $data = Ordinance::findOrFail($id);
        $data->delete();
        sleep(1);

        if ($request->wantsJson()) {
            return response(null, 204);
        }
        return redirect()->back();
    }
}
