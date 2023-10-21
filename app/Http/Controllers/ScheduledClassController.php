<?php

namespace App\Http\Controllers;

use App\Events\ClassCanceled;
use App\Models\ClassType;
use App\Models\ScheduledClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduledClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scheduledClasses = auth()->user()->scheduledClasses()->upcoming()->oldest('date_time')->get();

        return view('instructor.upcoming')->with('scheduledClasses', $scheduledClasses);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classTypes = ClassType::all();

        return view('instructor.schedule')->with('classTypes', $classTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date_time = $request->input('date').' '.$request->input('time');
        $request->merge([
            'date_time' => $date_time,
            'instructor_id' => auth()->user()->id,
        ]);

        $validator = $request->validate([
            'class_type_id' => 'required',
            'instructor_id' => 'required',
            'date_time' => 'required|unique:scheduled_classes,date_time|after:now',
        ]);

        ScheduledClass::create($validator);

        return redirect()->route('schedule.index');

        // $validator = Validator::make($request->all(),[
        //     'class_type_id' => 'required',
        //     'instructor_id' => 'required',
        //     'date_time' => 'required|unique:scheduled_classes,date_time|after:now'
        // ]);

        // if ($validated->fails()) {
        //     //dd($request->all());
        //     return redirect()->back()->withErrors($validator)->withInput($request->all());
        // } else {
        //     ScheduledClass::create($validator);
        //     return redirect()->route('schedule.index');
        // }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduledClass $schedule)
    {
        if (auth()->user()->cannot('delete', $schedule)) {
            abort(403);
        }
        // if(auth()->user()->id !== $schedule->instructor_id) {
        //     abort(403);
        // }

        ClassCanceled::dispatch($schedule);

        $schedule->students()->detach();
        $schedule->delete();

        return redirect()->route('schedule.index');
    }
}
