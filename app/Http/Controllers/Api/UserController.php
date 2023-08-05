<?php

namespace App\Http\Controllers\Api;

use App\{Http\Controllers\Controller, Models\Term, Repositories\Authentication\AuthRepository, Services\UserService};
use Illuminate\{Auth\AuthenticationException, Http\Request, Support\Facades\Validator};
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\ResponseTrait;

class UserController extends Controller
{
    use ResponseTrait;

    private AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
        $this->middleware('apiMid', ['except' => ['login', 'register', 'forgotPassword', 'term', 'resetPassword', 'checkUser']]);
    }

    /**
     * @throws AuthenticationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }
        return $this->authRepository->login($request->only('email', 'password'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|string|email|unique:admins',
            'password' => 'required|string',
            'password_confirmation' => 'same:password',
            'term' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }
        return $this->authRepository->register($request->all());
    }

    public function forgotPassword(Request $request)
    {
        $result = $this->userService->forgotPassword($request->email);
        return response()->json($result, $result['status'] == 'success' ? 200 : 404);
    }

    public function resetPassword(Request $request)
    {
        return $this->userService->resetPassword($request->all());
    }

    public function refresh(Request $request)
    {
        return $this->userService->refresh();
    }

    public function changePassword(Request $request)
    {
        return $this->userService->changePassword($request);
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function term()
    {
        return $this->responseData(Term::first());
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $result = $this->userService->handleSocialiteCallback('google');

        if ($result['status'] == 'success') {
            alert()->success(__('messages.success'));
            return redirect()->route('user.index');
        } else {
            alert()->error(__('messages.error'));
            return redirect()->back();
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function check(Request $request)
    {

    }

    public function handleFacebookCallback()
    {
        $result = $this->userService->handleSocialiteCallback('facebook');
    }
}
