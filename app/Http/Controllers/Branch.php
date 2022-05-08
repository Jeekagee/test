<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Branch extends Controller
{
    
    function branchAction(Request $request)
    {
        
        $employee = DB::table('public_details')
                ->join('visit_details','public_details.ref_no','=','visit_details.public_ref_no')
                ->join('branches','visit_details.branch_id','=','branches.id')
                ->select('public_details.*','visit_details.purpose','branches.branch_name')
                ->get();
                return view('receptionist/dashboard')->with('employee',$employee);
    }

}
