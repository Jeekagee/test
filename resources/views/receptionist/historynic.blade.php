@extends('layouts.master')

@section('content')
<div class="container">
    <div class="col-md-8 justify-content-center">
        <div class="row">
            <div class="col-md-8">
                <input class="form-control" id="nic" type="text" placeholder="Enter your NIC No..." name="search">
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-md-8">
                <input class="form-control" id="ref" type="text" placeholder="Enter your Ref No..." name="search">
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $("#ref").change(function(){
        var ref_no = $(this).val();
        $("#nic").val('');
        var nic = $("#nic").val();
        fetch_historyData(nic,ref_no);
    });

    $("#nic").change(function(){
        var nic = $(this).val();
        $("#ref").val('');
        var ref_no = $("#ref").val();
        fetch_historyData(nic,ref_no);
    });

    function fetch_historyData(nic,ref_no) 
    {
        
    }
});
</script>