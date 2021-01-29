@extends('layouts.layout')

@section('content')
<div class="p-3 bg-white border rounded">
    <button class="btn btn-md btn-primary" id="btnOpenNewRole" type="button" class="btn btn-primary">New Role<i class="ml-2 fa fa-plus" aria-hidden="true"></i></button>
    <button class="btn btn-md btn-success" id="btnRefreshRoles">Refresh All<i class="ml-2 fa fa-refresh" aria-hidden="true"></i></button>
</div>
<div class="card p-2 mt-2 bg-white container">
    <table class="table" id="rolesTable"></table>
</div>

@endsection


@section('modals')
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Role Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <div class="form-group">
                        <label for="roleName">Role Name</label>
                        <input type="text" required class="form-control" id="roleName">
                    </div>
                </form>
                <div id="permissions">
                    <div class="row cols-md-2">
                        <div class="col">
                            <h5>Ungained Permissions</h5>
                            <ul class="list-group" id="leftPermissions">
                                <li class="list-group-item d-flex flex-row align-items-center"><span>Cras justo odio</span> <button class="btn btn-md font-weight-bold btn-primary ml-auto"><i class="fa fa-hand-o-right"></i></button></li>
                                <li class="list-group-item">Dapibus ac facilisis in<button><i class="fa fa-hand-o-left"></i></button></li>
                            </ul>
                        </div>
                        <div class="col">
                            <h5>Gained Permissions</h5>
                            <ul class="list-group" id="rightPermissions">
                                <li class="list-group-item active"><button><i class="fa fa-hand-o-left"></i></button>Cras justo odio</li>
                                <li class="list-group-item active"><button><i class="fa fa-hand-o-left"></i></button>Dapibus ac facilisis in</li>
                                <li class="list-group-item active"><button><i class="fa fa-hand-o-left"></i></button>Morbi leo risus</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnController">New Role</button>
            </div>
        </div>
    </div>
</div>
@endsection