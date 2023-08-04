<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        $this->middleware('apiMid');
        $this->user = auth('api')->authenticate();
    }

    public function add($id)
    {
        try {
            $advert = Advert::find($id);
            if ($advert) {
                if ($advert->users()->wherePivot('user_id', $this->user->id)->exists()) {
                    return response()->json([
                        'message' => 'advert-already-wishlist',
                    ], 409);
                } else {
                    $advert->users()->attach($this->user, ['created_at' => now(), 'updated_at' => now()]);
                    return response()->json([
                        'message' => 'advert-added-successfully',
                    ], 200);
                }
            } else {
                return response()->json([
                    'message' => 'advert-not-found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function remove($id)
    {
        try {
            $advert = Advert::find($id);
            if ($advert) {
                $advert->users()->detach($this->user->id);
                return response()->json([
                    'message' => 'user-detached-from-advert-successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'advert-not-found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function items()
    {
        try {
            $user = User::find($this->user->id);
            if ($user) {
                if(!$user->adverts()->exists()){
                    return response()->json(['items' => 'your-wishlist-is-empty'],404);
                }else{
                    return response()->json(['items' => $user->adverts()->get()],200);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }
}
