@extends('layouts.layout')

@section('content')
<div class="p-3 bg-white border rounded">
    <button class="btn btn-md btn-primary" id="btnOpenNewCity" type="button" class="btn btn-primary">New City<i class="ml-2 fa fa-plus" aria-hidden="true"></i></button>
    <button class="btn btn-md btn-success" id="btnRefreshCities">Refresh All<i class="ml-2 fa fa-refresh" aria-hidden="true"></i></button>
</div>
<div class="card p-2 mt-2 bg-white container">
    <table class="table" id="citiesTable"></table>
</div>

@endsection


@section('modals')
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">City Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <div class="form-group">
                        <label for="cityName">City Name</label>
                        <input type="text" required class="form-control" id="cityName">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">State</label>
                        <select id="state" class="form-control">
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnController">New City</button>
            </div>
        </div>
    </div>
</div>
@endsection