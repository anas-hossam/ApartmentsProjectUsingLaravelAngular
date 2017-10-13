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
use App\User;
use App\Apartment;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('spa');
});

/**
 * Registers a new user and returns a auth token
 */
Route::post('/signup', function (Request $request) {
    try {
        $user = new User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = sha1($request->input('password'));
        $user->save();
//        return Response::json($user, HttpResponse::HTTP_UNAUTHORIZED);

    } catch (Exception $e) {
        return Response::json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
    }

    $token = JWTAuth::fromUser($user);

    return Response::json(compact('token'));
});

/**
 * Signs in a user using JWT
 */
Route::post('/signin', function (Request $request) {

    $credentials = Input::only('email', 'password');

    $user = User::select('id','name','email','updated_at','created_at')
                ->where('email',$credentials['email'])
                ->where('password',sha1($credentials['password']))
                ->first();
//    return Response::json($user, HttpResponse::HTTP_UNAUTHORIZED);

    if (! $user) {
        return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
    }
    $token = JWTAuth::fromUser($user);

    return Response::json(compact('token'));
});


/**
 * Fetches a restricted resource from the same domain used for user authentication
 */
Route::get('/restricted', [
    'before' => 'jwt-auth',
    function () {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        return Response::json([
            'data' => [
                'email' => $user->email,
                'name' => $user->name,
                'registered_at' => $user->created_at->toDateTimeString()
            ]
        ]);
    }
]);

/*
 * Resource for Apartment
 * */

Route::resource('apartments','ApartmentController');

/*
 * Delete apartment*/
Route::get('/apartment/delete/{id}',['before' => 'jwt-auth', function($id){

//    $token = JWTAuth::getToken();
//    $user = JWTAuth::toUser($token);

    $apartment = Apartment::findOrFail($id);
    $apartment->delete();
    return redirect('/#/apartments');
}]);

