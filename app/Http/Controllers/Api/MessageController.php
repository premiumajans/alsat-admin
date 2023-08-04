<?php

namespace App\Http\Controllers\Api;

use App\Events\Messages\Read;
use App\Events\Messages\Send;
use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        $this->middleware('apiMid');
        $this->user = auth('api')->authenticate();
    }

    public function create($advert_id)
    {
        dd($this->user->adverts);
        $user = User::find($this->user->id);
//        if () {
//
//        } else {
//
//        }
    }

    public function message(Request $request)
    {
        dd($this->user);
        //event(new NewMessage($request->message));
        event(new Send($request->message));
        return response([
            'message' => 'success',
        ], 200);
    }

    public function read($id)
    {
//        event(new Read($request->message));
    }
}
