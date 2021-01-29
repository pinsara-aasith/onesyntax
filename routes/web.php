<?php

use App\Http\Controllers\Api\CitiesController;
use App\Http\Controllers\Api\CountriesController;
use App\Http\Controllers\Api\DepartmentsController;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\StatesController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['permission:manage users']], function () {
    Route::get('/api/permissions/all/', [PermissionsController::class, 'getAllPermissions'])->name('getAllPermissions');
    Route::post('/api/permissions/create/', [PermissionsController::class, 'createPermission'])->name('createPermission');
    Route::get('/api/permissions/read/{id}', [PermissionsController::class, 'getPermission'])->name('getPermission');
    Route::put('/api/permissions/update/{id}', [PermissionsController::class, 'updatePermission'])->name('updatePermission');

    Route::get('/api/roles/all/', [RolesController::class, 'getAllRoles'])->name('getAllRoles');
    Route::post('/api/roles/create/', [RolesController::class, 'createRole'])->name('createRole');
    Route::get('/api/roles/read/{id}', [RolesController::class, 'getRole'])->name('getRole');
    Route::put('/api/roles/update/{id}', [RolesController::class, 'updateRole'])->name('updateRole');

    Route::get('/api/users/all/', [UsersController::class, 'getAllUsers'])->name('getAllUsers');
    Route::post('/api/users/create/', [UsersController::class, 'createUser'])->name('createUser');
    Route::get('/api/users/read/{id}', [UsersController::class, 'getUser'])->name('getUser');
    Route::put('/api/users/update/{id}', [UsersController::class, 'updateUser'])->name('updateUser');
    Route::delete('/api/users/delete/{id}', [UsersController::class, 'deleteUser'])->name('deleteUser');
});


Route::group(['middleware' => ['permission:manage countries']], function () {
    Route::post('/api/countries/create/', [CountriesController::class, 'createCountry'])->name('createCountry');
    Route::put('/api/countries/update/{id}', [CountriesController::class, 'updateCountry'])->name('updateCountry');
    Route::delete('/api/countries/delete/{id}', [CountriesController::class, 'deleteCountry'])->name('deleteCountry');
});

Route::group(['middleware' => ['permission:view countries']], function () {
    Route::get('/api/countries/all/', [CountriesController::class, 'getAllCountries'])->name('getAllCountries');
    Route::get('/api/countries/read/{id}', [CountriesController::class, 'getCountry'])->name('getCountry');
});

Route::group(['middleware' => ['permission:manage states']], function () {
    Route::post('/api/states/create/', [StatesController::class, 'createState'])->name('createState');
    Route::put('/api/states/update/{id}', [StatesController::class, 'updateState'])->name('updateState');
    Route::delete('/api/states/delete/{id}', [StatesController::class, 'deleteState'])->name('deleteState');
});

Route::group(['middleware' => ['permission:view states']], function () {
    Route::get('/api/states/all/', [StatesController::class, 'getAllStates'])->name('getAllStates');
    Route::get('/api/states/allByCountry/{id}', [StatesController::class, 'getAllStatesByCountry'])->name('getAllStatesByCountry');
    Route::get('/api/states/read/{id}', [StatesController::class, 'getState'])->name('getState');
});

Route::group(['middleware' => ['permission:manage cities']], function () {
    Route::post('/api/cities/create/', [CitiesController::class, 'createCity'])->name('createCity');
    Route::put('/api/cities/update/{id}', [CitiesController::class, 'updateCity'])->name('updateCity');
    Route::delete('/api/cities/delete/{id}', [CitiesController::class, 'deleteCity'])->name('deleteCity');
});

Route::group(['middleware' => ['permission:view cities']], function () {
    Route::get('/api/cities/all/', [CitiesController::class, 'getAllCities'])->name('getAllCities');
    Route::get('/api/cities/read/{id}', [CitiesController::class, 'getCity'])->name('getCity');
    Route::get('/api/cities/allByState/{id}', [CitiesController::class, 'getAllCitiesByState'])->name('getAllCitiesByState');
    
});

Route::group(['middleware' => ['permission:manage departments']], function () {
    Route::post('/api/departments/create/', [DepartmentsController::class, 'createDepartment'])->name('createDepartment');
    Route::put('/api/departments/update/{id}', [DepartmentsController::class, 'updateDepartment'])->name('updateDepartment');
    Route::delete('/api/departments/delete/{id}', [DepartmentsController::class, 'deleteDepartment'])->name('deleteDepartment');
});

Route::group(['middleware' => ['permission:view departments']], function () {
    Route::get('/api/departments/all/', [DepartmentsController::class, 'getAllDepartments'])->name('getAllDepartments');
    Route::get('/api/departments/read/{id}', [DepartmentsController::class, 'getDepartment'])->name('getDepartment');
});

Route::group(['middleware' => ['permission:manage employees']], function () {
    Route::get('/api/employees/allByDepartment/{id}', [EmployeesController::class, 'getAllEmployeesByDepartment'])->name('getAllEmployeesByDepartment');
    Route::get('/api/employees/allBySearchName/', [EmployeesController::class, 'getAllEmployeesBySearchName'])->name('getAllEmployeesBySearchName');
    Route::get('/api/employees/all/', [EmployeesController::class, 'getAllEmployees'])->name('getAllEmployees');
    Route::get('/api/employees/read/{id}', [EmployeesController::class, 'getEmployee'])->name('getEmployee');

    Route::post('/api/employees/create/', [EmployeesController::class, 'createEmployee'])->name('createEmployee');
    Route::put('/api/employees/update/{id}', [EmployeesController::class, 'updateEmployee'])->name('updateEmployee');
    Route::delete('/api/employees/delete/{id}', [EmployeesController::class, 'deleteEmployee'])->name('deleteEmployee');

});

Route::middleware('auth')->group(function () {
    Route::view('/employees', 'employees');
    Route::view('/permissions', 'permissions');
    Route::view('/roles', 'roles');
    Route::view('/users', 'users');
    Route::view('/countries', 'countries');
    Route::view('/states', 'states');
    Route::view('/cities', 'cities');
    Route::view('/departments', 'departments');

    Route::get('/session/logoutAndRedirect', [UsersController::class, 'manualLogoutAndRedirect'])->name('manualLogoutAndRedirect');
});
