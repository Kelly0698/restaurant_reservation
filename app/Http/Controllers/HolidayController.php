<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('restaurant_holiday');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $holiday = new Holiday();
        $holiday->holiday_name = $request->input('holiday_name');
        $holiday->start_date = $request->input('start_date');
        $holiday->end_date = $request->input('end_date');
        $holiday->restaurant_id = Auth::guard('restaurant')->user()->id;
        $holiday->save();
    
        return redirect()->route('holiday')->with('success', 'Holiday added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $holidays = Holiday::all()->map(function ($holiday) {
            return [
                'id' => $holiday->id,
                'restaurant_id' => $holiday->restaurant_id,
                'title' => $holiday->holiday_name,
                'start' => $holiday->start_date,
                'end' => $holiday->end_date,
                'allDay' => true,
            ];
        });
    
        return response()->json($holidays);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the holiday by ID and ensure it belongs to the authenticated restaurant user
        $holiday = Holiday::where('id', $id)
                        ->where('restaurant_id', Auth::guard('restaurant')->user()->id)
                        ->firstOrFail();

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required',
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d',
        ]);

        // Update the holiday details
        $holiday->update([
            'holiday_name' => $validatedData['title'],
            'start_date' => $validatedData['start'],
            'end_date' => $validatedData['end'],
        ]);

        // Return a JSON response indicating success
        return response()->json(['success' => true]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $holiday = Holiday::find($id);
        if ($holiday) {
            $holiday->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
