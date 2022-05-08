<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Details;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Receptionist extends Controller
{
    static function getBranches()
    {
        $branches = DB::table('branches')->get();
        return $branches;
    }

    function addRec(Request $request)
    {
        $lastRecord = $refNo = DB::table('public_details')
            ->orderBy('id','DESC')
            ->first();
        if($lastRecord)
        {
            $lastID = $lastRecord->id;
        }
        else
        {
            $lastID = 0;
        }
        $nextID = $lastID + 1;
        $branches = DB::table('branches')->get();
        return view('receptionist/add')->with('branches',$branches)->with('nextID',$nextID);
    }
    
    function refNoExist(Request $request)
    {
        if($request->ref_no > 0)
        {
            $refNo = DB::table('public_details')
            ->where('ref_no', $request->ref_no)
            ->get();
            $count = $refNo->count();
        }
        else
        {
            $refNo = DB::table('public_details')
            ->where('nic', $request->nic)
            ->where('status','!=', 3)
            ->get();
            $count = $refNo->count();
        }
        
        if($count > 0)
        {
            return response()->json([
                'detailsRef'=>$refNo,
            ]);
        }
        else
        {
            return 0;
        }
    }

    function saveDetails(Request $request)
    {
        //public details
        $ref = $request->ref;
        $nic = $request->nic;
        $name = $request->name;
        $address = $request->address;
        $contact = $request->contact;

        //visit details
        $today = Carbon::now();
        $todayDate = $today->toDateString('Y-m-d');
        $token_no = 0;
        $public_ref = $ref;
        $purpose = $request->purpose;
        $comment = $request->comment;
        $branch_id = $request->branch_id;
        $area = $request->area;
        $status = 1; // pending

        $last_token = DB::table('visit_details')
                ->where(DB::raw("(STR_TO_DATE(action_date,'%Y-%m-%d'))"), ">=", $todayDate)
                ->where('branch_id',$branch_id)
                ->orderBy('token_no','DESC')
                ->first();
        
        if ($last_token)
        {
            $token_no = $last_token->token_no;
            $createdDate = $last_token->created_at;

            $new_token = $token_no + 1;
        }
        else
        {
            $new_token = 1;
        }
        
        $exist  = DB::table('public_details')
              ->where('nic', $nic)
              ->where('status','!=',3)
              ->first();

        if($exist)
        {
            
        }
        else
        {
            $newRecord = DB::insert('insert into public_details (ref_no, nic, full_name, address, contact_no, status) 
                    values (?,?,?,?,?,?)', [$ref,$nic,$name,$address,$contact,1]);
        }
        /*$newNIC = DB::table('public_details')->updateOrInsert([
            'nic' => $nic,
            'status' => 1
        ],[
            'ref_no' => $ref,
            'full_name' => $name,
            'address' => $address,
            'contact_no' => $contact
        ]);*/

        $save = DB::insert('insert into visit_details (token_no, public_ref_no, purpose, comment, branch_id, area, status, action_date) 
                    values (?,?,?,?,?,?,?,?)', [$new_token,$public_ref,$purpose,$comment,$branch_id,$area,$status,$todayDate]);
        
        if($save)
        {
            $this->show_details($ref);
        }
    }

    function loadDetails(Request $request)
    {
        $ref = '';
        if($request->ref_no != 0)
        {
            $ref = $request->ref_no;
        }
        else
        {
            $details = DB::table('public_details')
                ->where('nic',$request->nic)
                ->where('status','!=',3)
                ->orderBy('id','ASC')
                ->get();
            foreach($details as $row)
            {
                $ref = $row->ref_no;
            }
        }
        
        $this->show_details($ref);
    }

    function submitDetails(Request $request)
    {
        $today = Carbon::now();
        $todayDate = $today->toDateString('Y-m-d');

        $nic = $request->nic;

        $updateStatus = DB::table('public_details')
              ->where('nic', $nic)
              ->where('status', 1)
              ->update(['status' => 2]);

        /*$updateStatus = DB::table('public_details')->updateOrInsert([
            'nic' => $nic,
            'status' => 1
        ],[
            'status' => 2
        ]);*/

        $employee = DB::table('public_details')
                ->join('visit_details','public_details.ref_no','=','visit_details.public_ref_no')
                ->join('branches','visit_details.branch_id','=','branches.id')
                ->select('public_details.ref_no','public_details.nic','public_details.full_name','public_details.address','public_details.contact_no','visit_details.purpose','branches.branch_name','visit_details.token_no','visit_details.status','visit_details.id')
                ->where('visit_details.action_date',$todayDate)
                ->get();
                return view('receptionist/dashboard')->with('employee',$employee)->with('userLevel',1)->with('userBranch',0);
    }

    public function show_details($ref)
    {
        // Get Details
        $today = Carbon::now();
        $todayDate = $today->toDateString('Y-m-d');
        
        $details = DB::table('visit_details')
            ->join('branches','visit_details.branch_id','=','branches.id')
            ->select('visit_details.*','branches.branch_name')
            ->where('visit_details.public_ref_no',$ref)
            ->where('visit_details.status',1)
            ->where('visit_details.action_date',$todayDate)
            ->get();
        ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th class="text-center">Ref No</th>
                <th class="text-center">Token No</th>
                <th class="text-center">Purpose</th>
                <th class="text-center">Comment</th>
                <th class="text-center">Branch</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($details as $det) 
                {
                    $today = Carbon::now();
                    $todayDate = $today->toDateString('Y-m-d');

                    $last_token = DB::table('visit_details')
                        ->where(DB::raw("(STR_TO_DATE(action_date,'%Y-%m-%d'))"), ">=", $todayDate)
                        ->where('branch_id',$det->branch_id)
                        ->orderBy('token_no','DESC')
                        ->first();
                
                        if ($last_token)
                        {
                            $token_no = $last_token->token_no;
                            $next_token = $token_no + 1;
                        }
                        else
                        {
                            $next_token = 1;
                        }

                    $is_submited = DB::table('public_details')
                        ->where('ref_no',$det->public_ref_no)
                        ->where('status',2)
                        ->first();
                    if($is_submited)
                    {
                        $is_first = 1;
                    }
                    else
                    {
                        $is_first = 0;
                    }
                ?>
                    <tr>
                        <td class="text-center"><?php echo $i; ?></td>
                        <td class="text-center"><?php echo $det->public_ref_no; ?></td>
                        <td class="text-center"><?php echo $det->token_no;?></td>
                        <td class="text-center"><?php echo $det->purpose;?></td>
                        <td class="text-center"><?php echo $det->comment;?></td>
                        <td class="text-center"><?php echo $det->branch_name;?></td>
                        <td class="text-center">
                        <?php if($is_first == 1)
                        {
                        ?>
                            <button type="button" class="btn btn-secondary openDialog" data-id="<?php echo $det->id;?>" data-token="<?php echo $next_token;?>" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#exampleModal">New Token</button>
                        <?php
                        }
                        ?>
                        </td>
                    </tr>
                <?php
                $i++;
                }
                ?>
            </tbody>
        </table>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <form action="#" method="get" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">New Token</div>
                        <div class="col-md-1"> : </div>
                        <div class="col-md-8"><p></p></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="submit" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </form>
            </div>
        </div>
        </div>
        
        <script>
            $(document).ready(function () {
                $(".openDialog").click(function () {
                    $("p").text($(this).data('token'));
                    $('#exampleModal').modal('show');

                    $.ajax({
                        type:'get',
                        url:'newToken',
                        data:{'newId':$(this).data('token'),'id':$(this).data('id')},
                        success:function(response){
                            //show_details($ref)
                        }
                    });
                });
            });
        </script>
        <?php
    }

    function newToken(Request $request)
    {
        $today = Carbon::now();
        $todayDate = $today->toDateString('Y-m-d');

        $newToken = DB::table('visit_details')
              ->where('id', $request->id)
              ->update(['token_no' => $request->newId, 'action_date'=>$todayDate]);
    }

    function actionSubmit(Request $request)
    {
        $today = Carbon::now();
        $todayDate = $today->toDateString('Y-m-d');

        if($request->actStatus == 1) //Pending
        {
            $old_record = Details::where('id', $request->actID)->first();
            $new_record = $old_record->replicate();
            $new_record->action_date = $request->actDate;
            $new_record->token_no = 0;
            $new_record->created_at = $today;
            $new_record->save();

            $actionUpdate = DB::table('visit_details')
                ->where('id', $request->actID)
                ->update(['action_comment'=>$request->actComment,'status'=>4]);
            /*
            $actionUpdate = DB::table('visit_details')
                ->where('id', $request->actID)
                ->update(['action_date'=>$request->actDate,'action_comment'=>$request->actComment,'status'=>$request->actStatus]);
            */
        }
        if($request->actStatus == 2) //Action taken
        {
            $actionUpdate = DB::table('visit_details')
                ->where('id', $request->actID)
                ->update(['action_comment'=>$request->actComment,'status'=>$request->actStatus]);
        }
        if($request->actStatus == 3) //Transferred
        {
            $last_token = DB::table('visit_details')
                ->where(DB::raw("(STR_TO_DATE(action_date,'%Y-%m-%d'))"), ">=", $todayDate)
                ->where('branch_id',$request->actBranch)
                ->orderBy('token_no','DESC')
                ->first();
        
            if ($last_token)
            {
                $token_no = $last_token->token_no;
                $new_token = $token_no + 1;
            }
            else
            {
                $new_token = 1;
            }

            $old_record = Details::where('id', $request->actID)->first();
            $new_record = $old_record->replicate();
            $new_record->branch_id = $request->actBranch;
            $new_record->created_at = $today;
            $new_record->token_no = $new_token;
            $new_record->save();

            $actionUpdate = DB::table('visit_details')
                ->where('id', $request->actID)
                ->update(['action_comment'=>$request->actComment,'status'=>$request->actStatus]);
            
        }
        
    }
}
