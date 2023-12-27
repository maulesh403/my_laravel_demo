<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportContactController;
use App\Http\Controllers\ImportContactController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WelcomeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Bydefault method call if not given any method
Route::get('/', WelcomeController::class);

// When not found any method than fallback method call
Route::fallback(function () {
    return 'Fallback Route';
});

// Route::controller(ContactController::class)->group(function (){
    
//     Route::get('/contacts', 'index')->name('contacts.index');
//     Route::get('/contacts/create', 'create')->name('contacts.create');
//     Route::get('/contacts/{id}', 'show')->name('contacts.show');
// });

Route::middleware(['auth', 'verified'])->group(function(){

    //Route::resource('/dashboard', DashboardController::class)->middleware(['auth']);
    Route::get('/dashboard', DashboardController::class);
    Route::get('/settings/profile-information', ProfileController::class)->name('user-profile-information.edit');
    Route::get('/settings/password', PasswordController::class)->name('user-password.edit');
    Route::get('/sample-contacts', function () {
        return response()->download(Storage::path('contacts-sample.csv'));
    })->name('sample-contacts');
    Route::get('/contacts/import', [ImportContactController::class, 'create'])->name('contacts.import.create');
    Route::post('/contacts/import', [ImportContactController::class, 'store'])->name('contacts.import.store');
    
    Route::get('/contacts/export', [ExportContactController::class, 'create'])->name('contacts.export.create');
    Route::post('/contacts/export', [ExportContactController::class, 'store'])->name('contacts.export.store');
    
    Route::resource('/contacts', ContactController::class);

    Route::delete('/contacts/{contact}/restore', [ContactController::class, 'restore'])->name('contacts.restore')->withTrashed();
    Route::delete('/contacts/{contact}/force-delete', [ContactController::class, 'forceDelete'])->name('contacts.force-delete')->withTrashed();
    
    Route::resource('/companies', CompanyController::class);

    Route::delete('/companies/{company}/restore', [CompanyController::class, 'restore'])->name('companies.restore')->withTrashed();
    Route::delete('/companies/{company}/force-delete', [CompanyController::class, 'forceDelete'])->name('companies.force-delete')->withTrashed();

    // multiple resources from 1 resource
    Route::resources([
        '/tags'     => TagController::class,
        '/tasks'    => TaskController::class
    ]);

    // to change default resource name change
    Route::resource('/activities', ActivityController::class)->parameters([
        'activities' => 'active'
    ]);

    //Shallow the parent name because child name is unique
    Route::resource('/contacts.notes', ContactNoteController::class)->shallow();
});

Route::get('/download', function(){
    return Storage::download('ai.png', 'custom_name.png');
});

Route::get('/eagerload-multiple', function () {

    $users = User::with('companies','contacts')->get();
    foreach ($users as $key => $value) {
        # code...
        echo $value->name.": ";
        echo $value->companies->count()." companies,". $value->contacts->count()." contacts<br/>";
    }
});

Route::get('/eagerload-nested', function () {

    $users = User::with('companies','companies.contacts')->get();
    foreach ($users as $key => $value) {
        # code...
        echo $value->name."<br/>";
        foreach ($value->companies as $key2 => $company) {
            echo $company->name." has". $company->contacts->count()." contacts<br/>";
        }
        echo "<br/>";
    }
});

Route::get('/eagerload-constraint', function () {

    $users = User::with(['companies' => function ($query) {
        $query->where('email', 'like', '%.org');
    }])->get();
    
    foreach ($users as $key => $value) {
        # code...
        echo $value->name."<br/>";
        foreach ($value->companies as $key2 => $company) {
            echo $company->email." <br/>";
        }
        echo "<br/>";
    }
});

Route::get('/eagerload-lazy', function () {

    $users = User::get();
    $users->load(['companies' => function ($query){
        $query->orderBy('name');
    }]);
    foreach ($users as $key => $value) {
        # code...
        echo $value->name."<br/>";
        foreach ($value->companies as $key2 => $company) {
            echo $company->name." <br/>";
        }
        echo "<br/>";
    }
});

Route::get('/eagerload-default', function () {

    // $users = User::get();
    $users = User::without('contacts')->get();

    foreach ($users as $key => $value) {
        # code...
        echo $value->name."<br/>";
        foreach ($value->companies as $key2 => $company) {
            echo $company->email." <br/>";
        }
        echo "<br/>";
    }
});

Route::get('/count-models', function () {

    // OTHER AGGREGATE FUNCTIONS
        // - withMin($relationship, $column)
        // - withMax($relationship, $column)
        // - withAvg($relationship, $column)
        // - withSum($relationship, $column)
        // - withExists($relationship)
    // $users = User::select(['name','email'])
    //                 ->withCount([
    //                     'contacts as c_count',
    //                     'companies' => function ($query){
    //                         $query->where('email', 'like', '%@gmail.com');
    //                     }
    //                 ])->get();

    // foreach ($users as $key => $value) {
    //     # code...
    //     echo $value->name."<br/>";
    //     echo $value->companies_count." companies<br/>";
    //     echo $value->c_count." contacts<br/>";
    //     echo "<br/>";
    // }

    $users = User::get();
    $users->loadCount(['companies' => function ($query){
                                $query->where('email', 'like', '%@gmail.com');
                            }]);

    foreach ($users as $key => $value) {
        # code...
        echo $value->name."<br/>";
        echo $value->companies_count." companies<br/>";
        echo "<br/>";
    }
});

// Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
// Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
// Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
// Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
// Route::get('/contacts/{id}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
// Route::put('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
// Route::delete('/contacts/{id}', [ContactController::class, 'destory'])->name('contacts.destory');

// // to change default function name to custom function name
// Route::resource('/activities', ActivityController::class)->names([
//     'index' => 'activities.all',
//     'show' => 'activities.view'
// ]);

// For only given function call in controller
// Route::resource('/activities', ActivityController::class)->only([
//     'create', 'store', 'edit', 'update', 'destroy'
// ]);

// Except given function from controller
// Route::resource('/activities', ActivityController::class)->except([
//     'create', 'store', 'edit', 'update', 'destroy'
//     //'index', 'show'
// ]);

