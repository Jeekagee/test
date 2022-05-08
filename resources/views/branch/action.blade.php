@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Public Details</b></div>

                <div class="card-body">
                    <form method="post" action="#">
                        @csrf
                        <div class="form-group row">
                            <label for="ref" class="col-md-3 col-form-label text-md-left">Ref No</label>
                            <div class="col-md-6">
                                <input id="ref" type="text" class="form-control" name="ref" required>
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
                                    
                                </select>
                                </div>
                            <div class="col-md-3">
                                <a id="add_visit" class="btn btn-primary">ADD</a>
                            </div>
                        </div>
                        <div class="m-b-10" id="visitDetails"></div>
                        <hr>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" id="btnSubmit" class="btn btn-primary">SUBMIT</button>
                                <!--<a href="{{ url()->previous() }}" class="btn btn-danger">BACK</a>-->
                                <a href="#" class="btn btn-danger">BACK</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    
});
</script>
