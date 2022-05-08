@extends('layouts.master')

@section('content')
<div class="container">
<?php if($userLevel == 1){ ?>
<a href="{{route('addRec')}}" class="btn btn-primary border"><i class="fa fa-plus fa-2xl"></i></a>
<?php } ?>
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
                        <th style="font-size: 12x;">Ref No</th>
                        <th style="font-size: 12x;">Token No</th>
                        <th style="font-size: 12x;">NIC No</th>
                        <th style="font-size: 12x;">Full Name</th>
                        <th style="font-size: 12x;">Address</th>
                        <th style="font-size: 12x;">Contact No</th>
                        <th style="font-size: 12x;">Purpose</th>
                        <th style="font-size: 12x;">Branch</th>
                        <th style="font-size: 12x;">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employee as $emp)
                    <tr>
                        <td style="font-size: 12x !important;"> {{$emp->ref_no}}</td>
                        <td style="font-size: 12x !important;"> {{$emp->token_no}}</td>
                        <td style="font-size: 12x !important;"> {{$emp->nic}}</td>
                        <td style="font-size: 12x !important;"> {{$emp->full_name}}</td>
                        <td style="font-size: 12x !important;"> {{$emp->address}}</td>
                        <td style="font-size: 12x !important;"> {{$emp->contact_no}}</td>
                        <td style="font-size: 12x !important;"> {{$emp->purpose}}</td>
                        <td style="font-size: 12x !important;"> {{$emp->branch_name}}</td>
                        <td style="font-size: 12x !important;">
                            <?php 
                                if($emp->status == 1){ echo "Pending";} 
                                elseif($emp->status == 2){ echo "Action taken";} 
                                elseif($emp->status == 3){ echo "Transferred";} 
                                elseif($emp->status == 4){ echo "Pending for another day";} 
                            ?>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
                            <td>'+ item.address+'</td>\
                            <td>'+ item.contact_no+'</td>\
                            <td>'+ item.purpose+'</td>\
                            <td>'+ item.branch_name+'</td>\
                            <td>'+ status+'</td>\
                        </tr>');
                    });
                }
            }
        });
    }
});
</script>