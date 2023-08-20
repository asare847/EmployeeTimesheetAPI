<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
class AttendanceController extends Controller
{
    //
    public function clock_in(Request $request)
    {
        //user id
        $user_id = $request->user()->id;
        //find attendance if exist
        $attendance =Attendance::where('date',Carbon::today())
        ->where('user_id',$user_id)->first();
        if(!$attendance)
        {
            $attendance = Attendance::create([
                'user_id'=>$user_id,
                'date'=>Carbon::today()
            ]);
        }
        //save clock in
        if(!$attendance->clock_in)
        {
            $attendance->clock_in = Carbon::now();
            $attendance->save();
        }
        //return response
        return response()->json($attendance,200);
    }
    //
    public function clock_out(Request $request)
    {
        //user id
        $user_id = $request->user()->id;
        //find attendance if exist
        $attendance =Attendance::where('date',Carbon::today())
        ->where('user_id',$user_id)->first();
        if(!$attendance)
        {
            $attendance = Attendance::create([
                'user_id'=>$user_id,
                'date'=>Carbon::today()
            ]);
        }
        //save clock in
        
            $attendance->clock_out = Carbon::now();
            $attendance->save();
        
        //return response
        return response()->json($attendance,200);
    }
    public function reports(Request $request,$id)
    {
        if(Gate::denies('reports',$id)){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        //input validation
        $validated = $request->validate([
            'start'=> 'required|date',
            'end'=> 'required|date'
        ]);
        //get attendance
        //by user id and date range
        $attendances = Attendance::where('user_id',$id)
        ->whereBetween('date',[
            $validated['start'],
            $validated['end']
        ])
        ->orderBy('date','asc')
        ->get();
        //return response
        return response()->json($attendances,200);
    }
    public function all_reports(Request $request)
    {
        if(Gate::denies('reports')){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        //input validation
        $validated = $request->validate([
            'start'=> 'required|date',
            'end'=> 'required|date'
        ]);
        $start= $validated['start'];
        $end = $validated['end'];
        //get all users together with the attendance
        //by date range
    $users = User::with(['attendance'=>function($query)use ($start,$end){
        $query->whereBetween('date',[$start,$end])
        
        ->orderBy('date','asc');
    }])->get();
    //return response
    return response()->json($users,200);
    }
}
