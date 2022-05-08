@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="m-b-10 col-sm-4">
        <input class="form-control" id="date" type="date" name="date" placeholder="Date" value="">
        <input class="form-control" id="userLevel" type="hidden" value="<?php echo $userLevel;?>">
        <input class="form-control" id="userBranch" type="hidden" value="<?php echo $userBranch;?>">
    </div>
    <br><br><br>
        <div class="col-md-12">
            <table class="table" id="example" width="100%">
                <thead>
                    <tr>
                        <th>Ref No</th>
                        <th>Token No</th>
                        <th>NIC Number</th>
                        <th>Full Name</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employee as $emp)
                    <tr>
                        <td> &nbsp; {{$emp->ref_no}}</td>
                        <td> &nbsp; {{$emp->token_no}}</td>
                        <td> &nbsp; {{$emp->nic}}</td>
                        <td> &nbsp; {{$emp->full_name}}</td>
                        <td> &nbsp; {{$emp->purpose}}</td>
                        <td> &nbsp; 
                            <?php 
                                if($emp->status == 1){ echo "Pending";} 
                                elseif($emp->status == 2){ echo "Action taken";} 
                                elseif($emp->status == 3){ echo "Transferred";} 
                                elseif($emp->status == 4){ echo "Pending for another day";} 
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary openDialog" data-comments="<?php echo $emp->action_comment;?>" data-status="<?php echo $emp->status;?>" data-id="<?php echo $emp->id;?>" data-token="<?php echo $emp->token_no;?>" data-nic="<?php echo $emp->nic;?>" data-name="<?php echo $emp->full_name;?>" data-purpose="<?php echo $emp->purpose;?>" data-comment="<?php echo $emp->comment;?>" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-eye"></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="#" method="get" >
            <div class="modal-body">
                <input class="form-control" type="hidden" name="selID" id="selID">
                <div class="form-group row">
                    <label for="ref" class="col-md-4 col-form-label text-md-left">Token No</label>
                    <div class="col-md-6">
                        : <label id="diaToken" class="col-md-8 col-form-label text-md-left"></label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nic" class="col-md-4 col-form-label text-md-left">NIC</label>
                    <div class="col-md-6">
                        : <label id="diaNIC" class="col-md-8 col-form-label text-md-left"></label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-left">Full Name</label>
                    <div class="col-md-6">
                        : <label id="diaName" class="col-md-8 col-form-label text-md-left"></label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="purpose" class="col-md-4 col-form-label text-md-left">Purpose</label>
                    <div class="col-md-6">
                        : <label id="diaPurpose" class="col-md-8 col-form-label text-md-left"></label>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="comment" class="col-md-4 col-form-label text-md-left">Comment</label>
                    <div class="col-md-6">
                        : <label id="diaComment" class="col-md-8 col-form-label text-md-left"></label>
                    </div>
                </div>
                
                <hr>

                <div class="form-group row">
                    <div class="col-md-4" style="text-align:center;">
                        <a id="actionPending" class="btn btn-danger">Pending</a>
                    </div>
                    <div class="col-md-4" style="text-align:center;">
                        <a id="actionDone" class="btn btn-primary">Action Taken</a>
                    </div>
                    <div class="col-md-4" style="text-align:center;">
                        <a id="actionTrans" class="btn btn-secondary">Transfer</a>
                    </div>
                </div>
                <div class="form-group row" id="actCommentDiv">
                    <label for="actCommentVal" class="col-md-4 col-form-label text-md-left">Action Comment</label>
                    <div class="col-md-6">
                        : <label id="actCommentVal" class="col-md-8 col-form-label text-md-left"></label>
                    </div>
                </div>
                <hr>
                <input class="form-control" type="hidden" name="actStatus" id="actStatus">
                <div class="form-group row" id="actionDate" style="display:none;">
                    <label for="date" class="col-md-3 col-form-label text-md-left">Date</label>
                    <div class="col-md-8">
                        <input class="form-control" type="date" name="date" id="actDate">
                    </div>
                </div>
                <div class="form-group row" id="actionBranch" style="display:none;">
                    <label for="branch" class="col-md-3 col-form-label text-md-left">Branch</label>
                    <?php
                        use App\Http\Controllers\Receptionist;
                    ?>
                    <div class="col-md-8">
                        <?php
                            $branches = Receptionist::getBranches();
                        ?>                    
                        <select name="branch" class="form-control" id="actBranch">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="actionComment" style="display:none;">
                    <label for="comment" class="col-md-3 col-form-label text-md-left">Comment</label>
                    <div class="col-md-8">
                        <textarea type="text" id="actComment" class="form-control" name="comment"></textarea>
                    </div>
                </div>
                
                <hr>

                <div class="form-group row" style="display:none;" id="btnSubmit">
                    <div class="col-md-12" style="text-align:center;">
                        <a id="actionSubmit" class="btn btn-primary">Submit</a>
                    </div>
                </div>

            </div>
        </form>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<script>
$(document).ready(function() {
    $('#example').dataTable({
        "ordering": false
    });

    $(document).on('change','#date',function(){
        var selDate = $(this).val();
        var userLevel = $("#userLevel").val();
        var userBranch = $("#userBranch").val();
        fetchdata(selDate, userLevel, userBranch)
    });

    function fetchdata(selDate, userLevel, userBranch)
    {    
        var dateObj = new Date();
        var month = dateObj.getUTCMonth() + 1; //months from 1-12
        if(month < 10)
        {
            month = "0" + month;
        }
        var day = dateObj.getUTCDate();
        if(day < 10)
        {
            day = "0" + day;
        }
        var year = dateObj.getUTCFullYear();
        var todayDate = year + "-" + month + "-" + day;

        $.ajax({
            type:'get',
            url:'{!!url('loadData')!!}',
            data:{'selDate':selDate, 'userLevel':userLevel,'userBranch':userBranch},
            success:function(response){
                if(todayDate == selDate)
                {
                    window.location.reload();
                }
                else
                {
                    $('tbody').find('tr').remove().end();
                    $.each(response.ds_report, function(key, item){
                        var status = '';
                        if(item.status == 1){ status = "Pending";} 
                        else if(item.status == 2){ status = "Action taken";} 
                        else if(item.status == 3){ status = "Transferred";} 
                        else if(item.status == 4){ status = "Pending for another day";} 
                        
                        $('tbody').append('<tr>\
                            <td>'+ item.ref_no+'</td>\
                            <td>'+ item.token_no+'</td>\
                            <td>'+ item.nic+'</td>\
                            <td>'+ item.full_name+'</td>\
                            <td>'+ item.purpose+'</td>\
                            <td>'+ status+'</td>\
                            <td><button type="button" class="btn btn-primary disabled"><i class="fa fa-eye"></i></button></td>\
                        </tr>');
                    });
                }
            }
        });
    }

    $(".openDialog").click(function () {
        $("#diaToken").text($(this).data('token'));
        $("#diaNIC").text($(this).data('nic'));
        $("#diaName").text($(this).data('name'));
        $("#diaPurpose").text($(this).data('purpose'));
        $("#diaComment").text($(this).data('comment'));
        $("#selID").val($(this).data('id'));

        if($(this).data('status') == 1)
        {
            $('#actionPending').show();
            $('#actionDone').show();
            $('#actionTrans').show();
            $('#actCommentDiv').hide();
            $("#actCommentVal").text("");
        }
        else
        {
            $('#actionPending').hide();
            $('#actionDone').hide();
            $('#actionTrans').hide();
            $('#actCommentDiv').show();
            $("#actCommentVal").text($(this).data('comments'));
        }

        $('#exampleModal').modal('show');

        $('#actionDate').hide();
        $('#actionBranch').hide();
        $('#actionComment').hide();
        $('#btnSubmit').hide();

    });

    $("#actionPending").click(function () {
        $('#actionDate').show();
        $('#actionBranch').hide();
        $('#actionComment').show();
        $('#btnSubmit').show();
        $('#actDate').val('');
        $('#actBranch').val('');
        $('#actComment').val('');
        $("#actStatus").val(1);
    });
    $("#actionDone").click(function () {
        $('#actionDate').hide();
        $('#actionBranch').hide();
        $('#actionComment').show();
        $('#btnSubmit').show();
        $('#actDate').val('');
        $('#actBranch').val('');
        $('#actComment').val('');
        $("#actStatus").val(2);
    });
    $("#actionTrans").click(function () {
        $('#actionDate').hide();
        $('#actionBranch').show();
        $('#actionComment').show();
        $('#btnSubmit').show();
        $('#actDate').val('');
        $('#actBranch').val('');
        $('#actComment').val('');
        $("#actStatus").val(3);
    });

    $("#actionSubmit").click(function () {
        actDate = '';
        actBranch = '';
        actComment = '';

        actID = $("#selID").val();
        actDate = $("#actDate").val();
        actBranch = $("#actBranch").val();
        actComment = $("#actComment").val();
        actStatus = $("#actStatus").val();
        
        $.ajax({
            type: 'get',
            url: '{!!url('actionSubmit')!!}',
            data: {
                'actID':actID,
                'actDate': actDate,
                'actBranch': actBranch,
                'actComment': actComment,
                'actStatus':actStatus
            },
            success: function(response) {
                //$('#exampleModal').modal('hide');
                window.location.reload();
            }
        });
    });
});
</script>