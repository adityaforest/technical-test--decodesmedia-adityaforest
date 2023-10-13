<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $actor = $request->input('actor');
        $dateRange = $request->input('date');
        $search = $request->input('search'); // search by order_code

        $query = Activity::query()->with('purchaseOrder');

        // Filter by actor (created_by)
        if ($actor) {
            $query->where('created_by', $actor);
        }

        // Filter by date range
        if ($dateRange) {            
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        // Search by order_code
        if ($search) {
            $query->whereHas('purchaseOrder', function ($query) use ($search) {
                $query->where('order_code', 'like', '%' . $search . '%');
            });
        }

        $result = $query                        
            ->paginate(10);
                                                
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
