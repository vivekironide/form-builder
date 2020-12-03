<?php

    use App\Http\Controllers\LoginController;
    use App\Http\Controllers\TicketController;
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

Route::get('/', [ TicketController::class, 'show'])->name( 'ticket.show');

Route::get('/list', [ TicketController::class, 'index'])->name( 'ticket.list')->middleware('auth');

Route::get('/showAgent/{id}', [ TicketController::class, 'showAgent'])->name( 'ticket.show.agent')->middleware('auth');

Route::post('/showAgent/{id}', [ TicketController::class, 'updateAgent'])->name( 'ticket.update.agent')->middleware('auth');

Route::get('/create', [ TicketController::class, 'create'])->name( 'ticket.create');

Route::post('/store', [ TicketController::class, 'store'])->name( 'ticket.store');

Route::get('/datatable', [ TicketController::class, 'datatable'])->name( 'ticket.datatable');

Route::get('/login', [ LoginController::class, 'index'])->name( 'login');

Route::post('/login', [ LoginController::class, 'logged'])->name( 'logged');
