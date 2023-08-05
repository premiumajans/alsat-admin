<?php

namespace App\Http\Controllers\Api;

use App\Http\Enums\CauserEnum;
use App\Models\User;
use App\Repositories\Advert\AdvertRepository;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Enums\AdvertEnum;
use App\Models\Advert;
use App\Models\AdvertDescription;
use App\Models\PremiumAdvert;
use App\Models\VipAdvert;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AdvertController extends Controller
{
    use ResponseTrait;

    protected AdvertRepository $advertRepository;

    /**
     * @throws AuthenticationException
     */
    public function __construct(AdvertRepository $advertRepository)
    {
        $this->advertRepository = $advertRepository;
//        $this->middleware('apiMid', ['except' => ['index', 'show', 'all','vip','toVip']]);
    }

    public function index()
    {
        return $this->responseData($this->advertRepository->getAdverts());
    }

    public function all()
    {
        return response()->json([
            'active' => Advert::active(),
            'pending' => Advert::pending(),
            'declined' => Advert::declined(),
            'de-active' => Advert::deactive(),
            'counts' => [
                'active' => Advert::countAccepted(),
                'pending' => Advert::countPending(),
                'declined' => Advert::countDeclined(),
                'de-active' => Advert::countDeactivated(),
            ]
        ]);
    }

    /**
     * @throws AuthenticationException
     */
    public function adverts()
    {
        $id = auth('api')->authenticate()->id;
        return response()->json([
            'active' => Advert::active($id),
            'pending' => Advert::pending($id),
            'declined' => Advert::declined($id),
            'de-active' => Advert::deactive($id),
            'counts' => [
                'active' => Advert::countAccepted($id),
                'pending' => Advert::countPending($id),
                'declined' => Advert::countDeclined($id),
                'de-active' => Advert::countDeactivated($id),
            ]
        ]);
    }

    public function show($id)
    {
//        if (Advert::where('id', $id)->where('end_time', '>', Carbon::now()) and Advert::where('id', $id)->exists()) {
//            $advert = Advert::find($id);
//            $advert->increment('view_count');
//            return response()->json([
//                'advert' => $advert,
//            ], 200);
//        } else {
//            return response()->json([
//                'advert' => 'advert-not-found',
//            ], 404);
//        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'salary' => 'required',
            'phone' => 'required',
            'owner' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }
        $owner = ['user' => User::find($request->user_id), 'owner_type' => CauserEnum::USER];
        return $this->responseData($this->advertRepository->createAdvert($request, $owner));
    }

    public function premium($id)
    {
        return $this->advertRepository->getPremiumAdverts();
    }

    public function toPremium($id)
    {
        try {
            $advert = Advert::find($id);
            $premium = new PremiumAdvert();
            $premium->premium = AdvertEnum::PREMIUM;
            $premium->start_time = \Carbon\Carbon::now();
            $premium->end_time = Carbon::now()->addMonths(1);
            $advert->premium()->save($premium);
            return response()->json(['advert' => $advert], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function vip()
    {
        return $this->responseData($this->advertRepository->getVipAdverts());
    }

    public function toVip($id)
    {
        return $this->responseData($this->advertRepository->makeVipAdvert($id));
    }

    public function delete($id)
    {
        try {
            if (Advert::where('id', $id)->exists()) {
                Advert::find($id)->delete();
                return response()->json([
                    'message' => 'advert-successfully-deleted',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'advert-not-found',
                ], 404);
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
