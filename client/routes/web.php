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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '1',
        'redirect_uri' => route('callback'),
        'response_type' => 'code',
        'scope' => ''
    ]);

    return redirect(env('AUTH_SERVER_URL') . '/oauth/authorize?'.$query);
})->name('redirect');

Route::get('/callback', function (Illuminate\Http\Request $request) {
    $http = new \GuzzleHttp\Client;

    $response = $http->post(env('AUTH_SERVER_URL') . '/oauth/token', [
        'form_params' => [
            'client_id' => '1',
            'client_secret' => env('AUTH_SERVER_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => route('callback'),
            'code' => $request->code,
        ],
    ]);
    return json_decode((string) $response->getBody(), true);
})->name('callback');
