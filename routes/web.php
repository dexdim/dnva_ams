<?php

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

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/loginpr', function () {
    return Socialite::driver('laravelpassport')->redirect();
});

Route::get('auth/callback', function () {
    $user = Socialite::driver('laravelpassport')->user();
    $email = ['adimas@domainesia.com', 'kinan@domainesia.com', 'willih@domainesia.com'];
    if (in_array($user->getEmail(), $email)) {
        $checkuser = User::where('email', $user->getEmail())->firstOrFail();
        Auth::login($checkuser);
        return redirect('/home');
    }
    return 'Unauthorized';
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('home', function () {
    return redirect('/inventory');
});


Route::get('/{vue_capture?}', function () {
    return view('home');
})->where('vue_capture', '[\/\w\.-]*')->middleware('auth');
