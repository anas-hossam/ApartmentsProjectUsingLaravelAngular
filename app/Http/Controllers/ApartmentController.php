<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Mail\ApartmentSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Enforce middleware.
     */
    public function __construct()
    {
//        $this->middleware('jwt-auth', ['only' => ['update', 'store', 'edit', 'delete']]);
    }

    public function index()
    {
        // get all apartments
        $apartments = Apartment::all();
        return Response::json([
            'data' => [
                'apartments' => $apartments,
//                'registered_at' => $user->created_at->toDateTimeString()
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'street' => 'required',
            'town' => 'required',
            'country' => 'required',
            'postcode' => 'required',
            'contact_email' => 'required',
            'move_in' => 'required',
        ]);
        // add new apartment

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        $apartment = new Apartment($request->all());
        $apartment->user_id = $user->id;
        $moveInDate = $request->input('move_in');
        if (strlen($moveInDate) <= 10)
            $apartment->move_in .= " 00:00:00";
        if (!$apartment->save()) {
            return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
        }
        // send email to user to update or delete apartment
        \Mail::to($user)->send(new ApartmentSettings($user, $apartment));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // edit partment

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        $apartment = Apartment::find($id);

        return Response::json([
            'data' => ["apartment" => $apartment]
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'street' => 'required',
            'town' => 'required',
            'country' => 'required',
            'postcode' => 'required',
            'contact_email' => 'required',
            'move_in' => 'required',
        ]);
        // update  apartment
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        $apartment = Apartment::findOrFail($id);
        $apartment->street = $request->input('street');
        $apartment->user_id = $user->id;
        $apartment->country = $request->input('country');
        $apartment->town = $request->input('town');
        $apartment->move_in = $request->input('move_in');
        $apartment->contact_email = $request->input('contact_email');
        $apartment->postcode = $request->input('postcode');
        $moveInDate = $request->input('move_in');
        if (strlen($moveInDate) <= 10)
            $apartment->move_in .= " 00:00:00";
        if ($apartment->save()) {
            redirect('apartments');
        } else {
            return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete apartment
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        $apartment = Apartment::findOrFail($id);
        $apartment->delete();
//        return redirect('apartments');


    }
}
