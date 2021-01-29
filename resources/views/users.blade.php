@extends('layouts.layout')

@section('content')
<div class="p-3 bg-white border rounded">
    <button class="btn btn-md btn-primary" id="btnOpenNewUser" type="button" class="btn btn-primary">New User<i class="ml-2 fa fa-plus" aria-hidden="true"></i></button>
    <button class="btn btn-md btn-success" id="btnRefreshUsers">Refresh All<i class="ml-2 fa fa-refresh" aria-hidden="true"></i></button>
</div>
<div class="card p-2 mt-2 bg-white container">
    <table class="table" id="usersTable"></table>
</div>

@endsection


@section('modals')
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">User Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">User Name</label>
                        <input type="text" id="username" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="row cols-md-2">
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">First Name</label>
                                <input id="firstname"  type="text" class="form-control" id="exampleFormControlInput1">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Last Name</label>
                                <input id="lastname" type="text" class="form-control" id="exampleFormControlInput1">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Email</label>
                        <input id="email" type="email" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Password</label>
                        <input id="password" type="password" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Role</label>
                        <select id="role" class="form-control">
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnController">New User</button>
            </div>
        </div>
    </div>
</div>
@endsection