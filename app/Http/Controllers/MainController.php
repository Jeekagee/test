<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Details;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MainController extends Controller
{
    function index()
    {
        return view('login');
    }

    function login(Request $request)
    {
        $today = Carbon::now();
        $todayDate = $today->toDateString('Y-m-d');

        $email = $request->get('email');
        $password = $request->get('password');

        $users = DB::table('users')
            ->where('user_name', $email)
            ->where('password', $password)
            ->first();
        $userLevel = $users->user_level;
        $branch_id = $users->branch_id;

        if($users) 
        {
            if($userLevel == 1 || $userLevel == 3)
            {
                $employee = $this->loadDataRecep($todayDate);
                return view('receptionist/dashboard')->with('employee',$employee)->with('userLevel',$userLevel)->with('userBranch',0);
            }
            else
            {
                $employee = $this->loadDataBranch($todayDate,$branch_id);
                return view('branch/dashboard')->with('employee',$employee)->with('userLevel',$userLevel)->with('userBranch',$branch_id);
            }
        }
        else
        {
            session()->flash('error', 'Invalid Credentials');
        }
    }

    function loadData(Request $request)
    {
        $todayDate = $request->selDate;
        $userLevel = $request->userLevel;
        $branch_id = $request->userBranch;

        if($userLevel == 1)
        {
            $employee = $this->loadDataRecep($todayDate);
            return response()->json([
                'ds_report'=>$employee,
                'userLevel'=>$userLevel,
                'userBranch'=>$branch_id
            ]);}
        else
        {
            $employee = $this->loadDataBranch($todayDate,$branch_id);
            return response()->json([
                'ds_report'=>$employee,
                'userLevel'=>$userLevel,
                'userBranch'=>$branch_id
            ]);}
    }

    public function loadDataRecep($todayDate)
    {
        $employee = DB::table('public_details')
            ->join('visit_details','public_details.ref_no','=','visit_details.public_ref_no')
            ->join('branches','visit_details.branch_id','=','branches.id')
            ->select('public_details.ref_no','public_details.nic','public_details.full_name','public_details.address','public_details.contact_no','visit_details.purpose','branches.branch_name','visit_details.token_no','visit_details.status')
            ->where('visit_details.action_date',$todayDate)
            ->get();
        return $employee;
    }

    public function loadDataBranch($todayDate,$branch_id)
    {
        $employee = DB::table('public_details')
            ->join('visit_details','public_details.ref_no','=','visit_details.public_ref_no')
            ->join('branches','visit_details.branch_id','=','branches.id')
            ->select('visit_details.id','visit_details.token_no','visit_details.comment','public_details.ref_no','public_details.nic','public_details.full_name','visit_details.status','visit_details.purpose','branches.branch_name','visit_details.action_comment')
            ->where('visit_details.branch_id',$branch_id)
            ->where('visit_details.action_date',$todayDate)
            ->get();
        return $employee;
    }

    public function loadEmpDetails(Request $request)
    {
        $nic = $request->nic;
        $today = Carbon::now();
        $todayDate = $today->toDateString('Y-m-d');
        $employee = DB::table('public_details')
            ->join('visit_details','public_details.ref_no','=','visit_details.public_ref_no')
            ->join('branches','visit_details.branch_id','=','branches.id')
            ->select('visit_details.action_date','visit_details.token_no','public_details.nic','visit_details.purpose','visit_details.area','branches.branch_name')
            ->where('public_details.nic',$nic)
            ->where('visit_details.action_date',$todayDate)
            ->where('visit_details.status',1)
            ->get();

            return response()->json([
                'details'=>$employee
            ]);
    }

    function history(Request $request)
    {
        return view('receptionist/historynic');
    }
}
