@extends('layouts.master')

@section('content')
<div class="container" id="notPrintable">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Public Details</b></div>

                <div class="card-body">
                    <form method="post" action="{{ route('submitDetails') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="ref" class="col-md-3 col-form-label text-md-left">Ref No</label>
                            <div class="col-md-6">
                                <input id="ref" type="text" class="form-control" name="ref" value=<?php echo $nextID;?> readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nic" class="col-md-3 col-form-label text-md-left">NIC Number</label>
                            <div class="col-md-6">
                                <input id="nic" type="text" class="form-control" name="nic" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-left">Full Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-3 col-form-label text-md-left">Address</label>
                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact" class="col-md-3 col-form-label text-md-left">Contact Number</label>
                            <div class="col-md-6">
                                <input id="contact" type="text" class="form-control" name="contact" required>
                            </div>
                        </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><b>Visit Details</b></div>

                <div class="card-body">
                        <div class="form-group row">
                            <label for="purpose" class="col-md-3 col-form-label text-md-left">Purpose</label>
                            <div class="col-md-6">
                                <input id="purpose" type="text" class="form-control" name="purpose" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="comment" class="col-md-3 col-form-label text-md-left">Comment</label>
                            <div class="col-md-6">
                                <textarea  id="comment" type="text" class="form-control" name="comment" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="branch_id" class="col-md-3 col-form-label text-md-left">Branch</label>
                            <div class="col-md-6">
                                <select name="branch_id" id="branch_id" class="form-control" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="area" class="col-md-3 col-form-label text-md-left">Area</label>
                            <div class="col-md-6">
                                <input  id="area" type="text" class="form-control" name="area" required>
                            </div>
                            <div class="col-md-3">
                                <a id="add_visit" class="btn border" style="background-color: #008CBA;">ADD</a>
                            </div>
                        </div>

                        <div class="m-b-10" id="visitDetails"></div>
                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 offset-md-3">
                                <button type="submit" id="btnSubmit" class="btn btn-primary" style="background-color: #008CBA;">SUBMIT</button>
                                <a id="printToken" class="btn btn-secondary">PRINT TOKEN</a>
                                <!--<a href="{{ url()->previous() }}" id="btn_back" class="btn btn-danger">BACK</a>-->
                                <a id="btn_back" class="btn btn-danger">BACK</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="printable" style="display:none;">
    <table class="table">
        <thead>
            <tr style="border-color:white;text-align:center;">
                <th colspan="2"><h1>Divisional Secretarial</h1><h3>Vavuniya North</h3></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $("#notPrintable").show();
    $("#printable").hide();

    $("#add_visit").click(function() {
        var ref = $("#ref").val();
        var nic = $("#nic").val();
        var name = $("#name").val();
        var address = $("#address").val();
        var contact = $("#contact").val();
        var purpose = $("#purpose").val();
        var comment = $("#comment").val();
        var branch_id = $("#branch_id").val();
        var area = $("#area").val();
        $.ajax({
            type: 'get',
            url: '{!!url('saveDetails')!!}',
            data: {
                'ref': ref,
                'nic': nic,
                'name': name,
                'address': address,
                'contact': contact,
                'purpose': purpose,
                'comment': comment,
                'branch_id': branch_id,
                'area':area
            },
            success: function(response) {
                $("#visitDetails").html(response);
            }
        });
    });

    $("#printToken").click(function() {
        var nic = $("#nic").val();
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
            type: 'get',
            url: '{!!url('loadEmpDetails')!!}',
            data: {
                'nic': nic
            },
            success: function(response) {
                $('tbody').find('tr').remove().end();
                $('tbody').append('<tr>\
                        <td>Date</td>\
                        <td>'+ todayDate +'</td>\
                    </tr>\
                    <tr>\
                        <td> NIC No</td>\
                        <td>'+ nic +'</td>\
                    </tr>');
                $.each(response.details, function(key, item){
                    $('tbody').append('<tr style="border-top:solid;">\
                        <td>Token No</td>\
                        <td>'+ item.token_no +'</td>\
                    </tr>\
                    <tr>\
                        <td>Service</td>\
                        <td>'+ item.purpose +'</td>\
                    </tr>\
                    <tr>\
                        <td>Section</td>\
                        <td>'+ item.branch_name +' | '+ item.area+'</td>\
                    </tr>');
                });
                $("#notPrintable").hide();
                $("#printable").show();
                window.print();
                window.location.reload();
            }
        });
        
    });

    $("#ref").change(function(){
        var ref_no = $(this).val();
        fetch_publicDetails(0,ref_no);
        fetch_visitDetails(0,ref_no);
    });

    $("#nic").change(function(){
        var nic = $(this).val();
        fetch_visitDetails(nic,0);
        fetch_publicDetails(nic,0);
    });

    function fetch_publicDetails(nic,ref_no) 
    {
        $selNIC = 0;
        $.ajax({
            type: 'get',
            url: '{!!url('refNoExist')!!}',
            data: {
                'nic': nic,'ref_no':ref_no
            },
            success: function(response) {
                if(response != 0)
                {
                    $.each(response.detailsRef, function(key, item){
                        $("#ref").val(item.ref_no);
                        $("#nic").val(item.nic);
                        $("#name").val(item.full_name);
                        $("#address").val(item.address);
                        $("#contact").val(item.contact_no);

                        if(item.status == 1 || item.status == 2)
                        {
                            $("#btnSubmit").prop( "disabled", false );
                            $("#add_visit").css('pointer-events', 'auto');
                        }
                        else
                        {
                            $("#btnSubmit").prop( "disabled", true );
                            $("#add_visit").css('pointer-events', 'none');
                        }
                    });
                    //$("#nic").prop( "readonly", true );
                }
                else
                {
                    //if(nic != ''){ } else{ $("#nic").val(""); }
                    //if(ref_no > 0){ } else{ $("#ref").val(""); }
                    $("#name").val("");
                    $("#address").val("");
                    $("#contact").val("");
                    //$( "#nic").prop('readonly', false);
                    $("#btnSubmit").prop( "disabled", false );
                    $("#add_visit").css('pointer-events', 'auto');
                }
            }
        });

        
    }

    function fetch_visitDetails(nic,ref_no) 
    {
        $.ajax({
            type: 'get',
            url: '{!!url('loadDetails')!!}',
            data: {
                'nic': nic,'ref_no':ref_no
            },
            success: function(response) {
                $("#visitDetails").html(response);
            }
        });
    }
});
</script>
