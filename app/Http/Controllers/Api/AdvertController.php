<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enums\AdvertEnum;
use App\Http\Enums\CompanyEnum;
use App\Http\Enums\PremiumEnum;
use App\Models\Advert;
use App\Models\AdvertDescription;
use App\Models\Company;
use App\Models\PremiumAdvert;
use App\Models\PremiumCompany;
use App\Models\PremiumCompanyHistory;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\VipAdvert;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdvertController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        $this->middleware('apiMid', ['except' => ['index', 'show', 'all']]);
    }

    public function index()
    {
        return response()->json(['adverts' => Advert::where('end_time', '>', Carbon::now())->with('description')->get()], 200);
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
        if (Advert::where('id', $id)->where('end_time', '>', Carbon::now()) and Advert::where('id', $id)->exists()) {
            $advert = Advert::find($id);
            $advert->increment('view_count');
            return response()->json([
                'advert' => $advert,
            ], 200);
        } else {
            return response()->json([
                'advert' => 'advert-not-found',
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $advert = new Advert();
            $advert->save();
            $aDes = new AdvertDescription();
            $aDes->title = $request->title;
            $aDes->short_description = $request->short_description;

            $advert->description()->save($aDes);
            return response()->json(['advert' => $advert]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function premium($id)
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

    public function vip($id)
    {
        try {
            $advert = Advert::find($id);
            $vip = new VipAdvert();
            $vip->vip = AdvertEnum::VIP;
            $vip->start_time = \Carbon\Carbon::now();
            $vip->end_time = Carbon::now()->addMonths(1);
            $advert->premium()->save($vip);
            return response()->json(['advert' => $advert], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
