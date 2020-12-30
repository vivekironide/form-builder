<?php

    use App\Http\Controllers\FormBuilderController;
    use App\Http\Controllers\LoginController;
    use App\Http\Controllers\FieldController;
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

    Route::prefix('field')->middleware( 'auth')->group(function () {
        Route::get( '/list', [ FieldController::class, 'index' ] )->name( 'field.list' );

        Route::get( '/create', [ FieldController::class, 'create' ] )->name( 'field.create' );

        Route::post( '/store', [ FieldController::class, 'store' ] )->name( 'field.store' );

        Route::get( '/edit/{id}', [ FieldController::class, 'edit' ] )->name( 'field.edit' );

        Route::put( '/edit/{id}/update', [ FieldController::class, 'update' ] )->name( 'field.update' );

        Route::delete( '/delete/{id}', [ FieldController::class, 'destroy' ] )->name( 'field.destroy' );

        Route::get( '/datatable', [ FieldController::class, 'datatable' ] )->name( 'field.datatable' );
    });

    Route::prefix('form-builder')->middleware( 'auth')->group(function () {
        Route::get( '/', [ FormBuilderController::class, 'index' ] )->name( 'form.builder.index' );

        Route::get( '/create', [ FormBuilderController::class, 'create' ] )->name( 'form.builder' );

        Route::post( '/store', [ FormBuilderController::class, 'store' ] )->name( 'form.builder.store' );

        Route::get( '/show/{id}', [ FormBuilderController::class, 'show' ] )->name( 'form.show' );

        Route::get( '/datatable', [ FormBuilderController::class, 'datatable' ] )->name( 'form.datatable' );

        Route::delete( '/delete/{id}', [ FormBuilderController::class, 'destroy' ] )->name( 'form.destroy' );
    });

    Route::get( '/', [ FieldController::class, 'index' ] )->name( 'field.show' )->middleware( 'auth');

    Route::get( '/login', [ LoginController::class, 'index' ] )->name( 'login' )->middleware( 'guest' );

    Route::get( '/logout', [ LoginController::class, 'logout' ] )->name( 'logout' )->middleware( 'auth' );

    Route::post( '/login', [ LoginController::class, 'logged' ] )->name( 'logged' )->middleware( 'guest' );
