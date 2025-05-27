<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

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
Route::get('/give-all-permissions', function () {
    $user = Admin::first();
    if ($user) {

        $permissions = [
            'viewPermission',
            'createPermission',
            'editPermission',
            'updatePermission',
            'deletePermission',

            'viewPermission',
            'createPermission',
            'editPermission',
            'deletePermission',
            'updatePermission',


            'viewRole',
            'createRole',
            'editRole',
            'updateRole',
            'deleteRole',

        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin' , 'group' => 'Role']);
        }
        
        $permissions = Permission::all();

        $user->syncPermissions($permissions);

        return response()->json([
            'message' => 'All permissions have been assigned to the first user.',
            'user' => $user,
            'permissions' => $user->permissions,
        ]);
    } else {
        return response()->json(['message' => 'No users found.']);
    }
});

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('dashboard/dashboard');
});

Route::group([ 'namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'admin' ], function(){

    // login routes
    Route::get('login', 'AuthController@login')->name('admin/login');
    Route::post('login', 'AuthController@check_login')->name('admin/check-login');

    Route::get('/categories', 'HomeController@categories')->name('get/categories');

    Route::group(['middleware' => ['adminLogin', 'limitReq']],function(){

        Route::get('/home', 'HomeController@home')->name('admin/index');
        Route::get('logout', 'AuthController@logout')->name('admin/logout');

        // admin routes
        Route::get('admins/info', 'AdminController@info')->name('admin/admins/info');
        Route::post('admins/info-update', 'AdminController@info_update')->name('admin/admins/info-update');
        Route::post('admins/change-password', 'AdminController@change_password')->name('admin/admins/change-password');

        Route::get('admins/index/{offset?}/{limit?}', 'AdminController@index')->name('admin/admins/index');
        Route::get('admins/create', 'AdminController@create')->name('admin/admins/create');
        Route::post('admins/create', 'AdminController@store')->name('admin/admins/store');
        Route::get('admins/activate', 'AdminController@activate')->name('admin/admins/activate');
        Route::get('admins/delete', 'AdminController@delete')->name('admin/admins/delete');
        Route::post('admins/pagination/{offset?}/{limit?}', 'AdminController@pagination')->name('admin/admins/pagination');
        Route::post('admins/search', 'AdminController@search')->name('admin/admins/search');
        Route::post('admins/search/byColumn', 'AdminController@searchByColumn')->name('admin/admins/search/byColumn');
        Route::get('admins/archives/{offset?}/{limit?}', 'AdminController@archives')->name('admin/admins/archives');
        Route::get('admins/back', 'AdminController@back')->name('admin/admins/back');
        Route::post('admins/pagination/archives/{offset?}/{limit?}', 'AdminController@archivesPagination')->name('admin/admins/pagination/archives');
        Route::post('admins/search/archives', 'AdminController@archivesSearch')->name('admin/admins/search/archives');
        Route::post('admins/search/byColumn/archives', 'AdminController@archivesSearchByColumn')->name('admin/admins/search/byColumn/archives');



        // category routes
        Route::get('categories/index/{offset?}/{limit?}', 'CategoryController@index')->name('admin/categories/index');
        Route::get('categories/create', 'CategoryController@create')->name('admin/categories/create');
        Route::post('categories/create', 'CategoryController@store')->name('admin/categories/store');
        Route::get('categories/edit/{id?}', 'CategoryController@edit')->name('admin/categories/edit');
        Route::post('categories/edit/{id}', 'CategoryController@update')->name('admin/categories/update');
        Route::get('categories/activate', 'CategoryController@activate')->name('admin/categories/activate');
        Route::get('categories/delete', 'CategoryController@delete')->name('admin/categories/delete');
        Route::post('categories/pagination/{offset?}/{limit?}', 'CategoryController@pagination')->name('admin/categories/pagination');
        Route::post('categories/search', 'CategoryController@search')->name('admin/categories/search');
        Route::post('categories/search/byColumn', 'CategoryController@searchByColumn')->name('admin/categories/search/byColumn');
        Route::get('categories/archives/{offset?}/{limit?}', 'CategoryController@archives')->name('admin/categories/archives');
        Route::get('categories/back', 'CategoryController@back')->name('admin/categories/back');
        Route::post('categories/pagination/archives/{offset?}/{limit?}', 'CategoryController@archivesPagination')->name('admin/categories/pagination/archives');
        Route::post('categories/search/archives', 'CategoryController@archivesSearch')->name('admin/categories/search/archives');
        Route::post('categories/search/byColumn/archives', 'CategoryController@archivesSearchByColumn')->name('admin/categories/search/byColumn/archives');



        // paymentMethod routes
        Route::get('paymentMethods/index/{offset?}/{limit?}', 'PaymentMethodController@index')->name('admin/paymentMethods/index');
        Route::get('paymentMethods/create', 'PaymentMethodController@create')->name('admin/paymentMethods/create');
        Route::post('paymentMethods/create', 'PaymentMethodController@store')->name('admin/paymentMethods/store');
        Route::get('paymentMethods/edit/{id?}', 'PaymentMethodController@edit')->name('admin/paymentMethods/edit');
        Route::post('paymentMethods/edit/{id}', 'PaymentMethodController@update')->name('admin/paymentMethods/update');
        Route::get('paymentMethods/activate', 'PaymentMethodController@activate')->name('admin/paymentMethods/activate');
        Route::get('paymentMethods/delete', 'PaymentMethodController@delete')->name('admin/paymentMethods/delete');
        Route::post('paymentMethods/pagination/{offset?}/{limit?}', 'PaymentMethodController@pagination')->name('admin/paymentMethods/pagination');
        Route::post('paymentMethods/search', 'PaymentMethodController@search')->name('admin/paymentMethods/search');
        Route::post('paymentMethods/search/byColumn', 'PaymentMethodController@searchByColumn')->name('admin/paymentMethods/search/byColumn');
        Route::get('paymentMethods/archives/{offset?}/{limit?}', 'PaymentMethodController@archives')->name('admin/paymentMethods/archives');
        Route::get('paymentMethods/back', 'PaymentMethodController@back')->name('admin/paymentMethods/back');
        Route::post('paymentMethods/pagination/archives/{offset?}/{limit?}', 'PaymentMethodController@archivesPagination')->name('admin/paymentMethods/pagination/archives');
        Route::post('paymentMethods/search/archives', 'PaymentMethodController@archivesSearch')->name('admin/paymentMethods/search/archives');
        Route::post('paymentMethods/search/byColumn/archives', 'PaymentMethodController@archivesSearchByColumn')->name('admin/paymentMethods/search/byColumn/archives');



        // user routes
        Route::get('users/index/{offset?}/{limit?}', 'UserController@index')->name('admin/users/index');
        Route::get('users/create', 'UserController@create')->name('admin/users/create');
        Route::post('users/create', 'UserController@store')->name('admin/users/store');
        Route::get('users/edit/{id?}', 'UserController@edit')->name('admin/users/edit');
        Route::post('users/edit/{id}', 'UserController@update')->name('admin/users/update');
        Route::get('users/activate', 'UserController@activate')->name('admin/users/activate');
        Route::get('users/delete', 'UserController@delete')->name('admin/users/delete');
        Route::post('users/pagination/{offset?}/{limit?}', 'UserController@pagination')->name('admin/users/pagination');
        Route::post('users/search', 'UserController@search')->name('admin/users/search');
        Route::post('users/search/byColumn', 'UserController@searchByColumn')->name('admin/users/search/byColumn');
        Route::get('users/archives/{offset?}/{limit?}', 'UserController@archives')->name('admin/users/archives');
        Route::get('users/back', 'UserController@back')->name('admin/users/back');
        Route::post('users/pagination/archives/{offset?}/{limit?}', 'UserController@archivesPagination')->name('admin/users/pagination/archives');
        Route::post('users/search/archives', 'UserController@archivesSearch')->name('admin/users/search/archives');
        Route::post('users/search/byColumn/archives', 'UserController@archivesSearchByColumn')->name('admin/users/search/byColumn/archives');



        // branch routes
        Route::get('branches/index/{offset?}/{limit?}', 'BranchController@index')->name('admin/branches/index');
        Route::get('branches/create', 'BranchController@create')->name('admin/branches/create');
        Route::post('branches/create', 'BranchController@store')->name('admin/branches/store');
        Route::get('branches/edit/{id?}', 'BranchController@edit')->name('admin/branches/edit');
        Route::post('branches/edit/{id}', 'BranchController@update')->name('admin/branches/update');
        Route::get('branches/activate', 'BranchController@activate')->name('admin/branches/activate');
        Route::get('branches/publish', 'BranchController@publish')->name('admin/branches/publish');
        Route::get('branches/delete', 'BranchController@delete')->name('admin/branches/delete');
        Route::post('branches/pagination/{offset?}/{limit?}', 'BranchController@pagination')->name('admin/branches/pagination');
        Route::post('branches/search', 'BranchController@search')->name('admin/branches/search');
        Route::post('branches/search/byColumn', 'BranchController@searchByColumn')->name('admin/branches/search/byColumn');
        Route::get('branches/archives/{offset?}/{limit?}', 'BranchController@archives')->name('admin/branches/archives');
        Route::get('branches/back', 'BranchController@back')->name('admin/branches/back');
        Route::post('branches/pagination/archives/{offset?}/{limit?}', 'BranchController@archivesPagination')->name('admin/branches/pagination/archives');
        Route::post('branches/search/archives', 'BranchController@archivesSearch')->name('admin/branches/search/archives');
        Route::post('branches/search/byColumn/archives', 'BranchController@archivesSearchByColumn')->name('admin/branches/search/byColumn/archives');
        Route::get('branches/verify', 'BranchController@verify')->name('admin/branches/verify');
        // Route::get('branches/edit/{id}', 'CategoryController@edit')->name('admin.branches.edit');
        // Route::put('branches/edit/{id}', 'CategoryController@update')->name('admin.branches.update');



        // blog routes
        Route::get('blogs/index/{offset?}/{limit?}', 'BlogController@index')->name('admin/blogs/index');
        Route::get('blogs/create', 'BlogController@create')->name('admin/blogs/create');
        Route::post('blogs/create', 'BlogController@store')->name('admin/blogs/store');
        Route::get('blogs/edit/{id?}', 'BlogController@edit')->name('admin/blogs/edit');
        Route::post('blogs/edit/{id}', 'BlogController@update')->name('admin/blogs/update');
        Route::get('blogs/activate', 'BlogController@activate')->name('admin/blogs/activate');
        Route::get('blogs/delete', 'BlogController@delete')->name('admin/blogs/delete');
        Route::post('blogs/pagination/{offset?}/{limit?}', 'BlogController@pagination')->name('admin/blogs/pagination');
        Route::post('blogs/search', 'BlogController@search')->name('admin/blogs/search');
        Route::post('blogs/search/byColumn', 'BlogController@searchByColumn')->name('admin/blogs/search/byColumn');
        Route::get('blogs/archives/{offset?}/{limit?}', 'BlogController@archives')->name('admin/blogs/archives');
        Route::get('blogs/back', 'BlogController@back')->name('admin/blogs/back');
        Route::post('blogs/pagination/archives/{offset?}/{limit?}', 'BlogController@archivesPagination')->name('admin/blogs/pagination/archives');
        Route::post('blogs/search/archives', 'BlogController@archivesSearch')->name('admin/blogs/search/archives');
        Route::post('blogs/search/byColumn/archives', 'BlogController@archivesSearchByColumn')->name('admin/blogs/search/byColumn/archives');



        // about routes
        Route::get('abouts/index/{offset?}/{limit?}', 'AboutController@index')->name('admin/abouts/index');
        Route::get('abouts/create', 'AboutController@create')->name('admin/abouts/create');
        Route::post('abouts/create', 'AboutController@store')->name('admin/abouts/store');
        Route::get('abouts/edit/{id?}', 'AboutController@edit')->name('admin/abouts/edit');
        Route::post('abouts/edit/{id}', 'AboutController@update')->name('admin/abouts/update');
        Route::get('abouts/activate', 'AboutController@activate')->name('admin/abouts/activate');
        Route::get('abouts/delete', 'AboutController@delete')->name('admin/abouts/delete');
        Route::post('abouts/pagination/{offset?}/{limit?}', 'AboutController@pagination')->name('admin/abouts/pagination');
        Route::post('abouts/search', 'AboutController@search')->name('admin/abouts/search');
        Route::post('abouts/search/byColumn', 'AboutController@searchByColumn')->name('admin/abouts/search/byColumn');
        Route::get('abouts/archives/{offset?}/{limit?}', 'AboutController@archives')->name('admin/abouts/archives');
        Route::get('abouts/back', 'AboutController@back')->name('admin/abouts/back');
        Route::post('abouts/pagination/archives/{offset?}/{limit?}', 'AboutController@archivesPagination')->name('admin/abouts/pagination/archives');
        Route::post('abouts/search/archives', 'AboutController@archivesSearch')->name('admin/abouts/search/archives');
        Route::post('abouts/search/byColumn/archives', 'AboutController@archivesSearchByColumn')->name('admin/abouts/search/byColumn/archives');



        // contact routes
        Route::get('contacts/index/{offset?}/{limit?}', 'ContactController@index')->name('admin/contacts/index');
        Route::get('contacts/create', 'ContactController@create')->name('admin/contacts/create');
        Route::post('contacts/create', 'ContactController@store')->name('admin/contacts/store');
        Route::get('contacts/edit/{id?}', 'ContactController@edit')->name('admin/contacts/edit');
        Route::post('contacts/edit/{id}', 'ContactController@update')->name('admin/contacts/update');
        Route::get('contacts/activate', 'ContactController@activate')->name('admin/contacts/activate');
        Route::get('contacts/delete', 'ContactController@delete')->name('admin/contacts/delete');
        Route::post('contacts/pagination/{offset?}/{limit?}', 'ContactController@pagination')->name('admin/contacts/pagination');
        Route::post('contacts/search', 'ContactController@search')->name('admin/contacts/search');
        Route::post('contacts/search/byColumn', 'ContactController@searchByColumn')->name('admin/contacts/search/byColumn');
        Route::get('contacts/archives/{offset?}/{limit?}', 'ContactController@archives')->name('admin/contacts/archives');
        Route::get('contacts/back', 'ContactController@back')->name('admin/contacts/back');
        Route::post('contacts/pagination/archives/{offset?}/{limit?}', 'ContactController@archivesPagination')->name('admin/contacts/pagination/archives');
        Route::post('contacts/search/archives', 'ContactController@archivesSearch')->name('admin/contacts/search/archives');
        Route::post('contacts/search/byColumn/archives', 'ContactController@archivesSearchByColumn')->name('admin/contacts/search/byColumn/archives');


      //ROUTEFROMCOMMANDLINE

    });
});
