<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OneSyntax</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto';
        }
    </style>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="sidebar bg-white shadow-sm" id="sidebar">
            <div class="header-image-container d-flex justify-content-center align-items-center shadow-sm border-bottom">
                <img class="" style="width:70%" src="{{ asset('images/onesyntax.svg') }}" />
            </div>
            <div class="list-group m-3">
                <a href="/dashboard" class="list-group-item {{ (Request::is('dashboard')) ? 'active' : '' }} list-group-item-action"><i class="fa fa-home mr-2" aria-hidden="true"></i>Dashboard</a>
                <a href="/employees" class="list-group-item {{ (Request::is('employees')) ? 'active' : '' }} list-group-item-action"><i class="fa fa-id-card mr-2" aria-hidden="true"></i>Employee Management</a>
                <a type="button" data-toggle="collapse" href="#userManagement" aria-controls="userManagement" class=" list-group-item {{ (Request::is('/users')) ? 'active' : '' }} list-group-item-action">
                    <i class="fa fa-group mr-2" aria-hidden="true"></i>
                    User Management
                    <i class="fa fa-sort-desc m-1 float-right" aria-hidden="true"></i>
                </a>
                <div class="collapse show" id="userManagement">
                    <div class=" ml-3">
                        <a href="/users" class="list-group-item {{ (Request::is('users')) ? 'active' : '' }} list-group-item-action"><i class="fa fa-user-circle-o  mr-2" aria-hidden="true"></i>Users</a>
                        <a href="/roles" class="list-group-item {{ (Request::is('roles')) ? 'active' : '' }} list-group-item-action"><i class="fa fa-street-view mr-2" aria-hidden="true"></i>Roles</a>
                        <a href="/permissions" class="list-group-item {{ (Request::is('permissions')) ? 'active' : '' }} list-group-item-action"><i class="fa fa-wrench   mr-2" aria-hidden="true"></i>Permissions</a>
                    </div>
                </div>
                <a type="button" data-toggle="collapse" href="#systemManagement" aria-expanded="true" aria-controls="systemManagement" class=" list-group-item list-group-item-action">
                    <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>
                    System Management
                    <i class="fa fa-sort-desc m-1 float-right" aria-hidden="true"></i>
                </a>
                <div class="collapse show" id="systemManagement">
                    <div class=" ml-3">
                        <a href="/countries" class="list-group-item list-group-item-action  {{ (Request::is('countries')) ? 'active' : '' }}"><i class="fa fa-plane  mr-2" aria-hidden="true"></i>Countries</a>
                        <a href="/states" class="list-group-item list-group-item-action  {{ (Request::is('states')) ? 'active' : '' }}"><i class="fa fa-university mr-2" aria-hidden="true"></i>States</a>
                        <a href="/cities" class="list-group-item list-group-item-action {{ (Request::is('cities')) ? 'active' : '' }}"><i class="fa fa-random mr-2" aria-hidden="true"></i>Cities</a>
                        <a href="/departments" class="list-group-item list-group-item-action {{ (Request::is('departments')) ? 'active' : '' }}"><i class="fa fa-server mr-2" aria-hidden="true"></i>Departments</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <header class="d-flex fixexd-top border-bottom bg-white header text-black shadow-sm align-items-center p-2 px-md-2 mb-3 border-bottom ">
                <button id="sidebarCollapse" class="btn btn-lg btn-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
                <img class="m-2 ml-4 header-image" width="140px" src="{{ asset('images/onesyntax.svg') }}" />

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a>One Syntax</a></li>
                    <li class="breadcrumb-item active"><a>Dashboard</a></li>
                </ol>
                <div class="border shadow-sm p-2 ml-auto d-flex  align-items-center ">
                    <div>
                        <span><strong>{{Auth::user() ? Auth::user()->username : "Not Assigned"}}</strong><br />{{strtoupper( Auth::user() && sizeof(Auth::user()->getRoleNames()) > 0 ? Auth::user()->getRoleNames()[0] : "Unassigned")}}</span>
                    </div>
                    <a class="btn btn-outline-primary ml-4" href="/session/logoutAndRedirect">Logout</a>
                </div>
            </header>
            <div class="">
                <div class="container">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>
    @yield('modals')
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white d-flex justify-content-center">
                    <h4>Error Occured</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-skull-crossbones text-danger text-center mx-auto m-4" style="font-size: 48px;"></i>

                    <h4>Ooops!</h4>
                    <p id="errorText">Something went wrong. Please try again...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white d-flex justify-content-center">
                    <h4>Error Occured</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-skull-crossbones text-danger text-center mx-auto m-4" style="font-size: 48px;"></i>

                    <h4>Confirm!</h4>
                    <p id="confirmText"></p>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-md btn-danger" id="confirmButton">Confirm !</button>
                        <button class="btn btn-md btn-info ml-2" id="cancelButton">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://kit.fontawesome.com/b0a18dcd69.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
<script>

</script>

</html>