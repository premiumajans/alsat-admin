<?php
namespace App\Repositories\Authentication;

use App\Models\User;
use App\Traits\ResponseTrait;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository implements AuthRepositoryInterface
{
    use ResponseTrait;

    protected JWTAuth $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
        $this->user = Auth::guard('api')->user();
    }

    public function login(array $credentials): JsonResponse|array
    {
        if (!Auth::attempt($credentials)) {
            return $this->errorResponse('unauthorized', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        if (!$user) {
            return $this->responseDataNotFound('user-not-found');
        }

        $token = JWTAuth::fromUser($user);
        $data = [
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ];

        return $this->responseData($data);
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = $this->jwtAuth->parseToken()->refresh();
        } catch (TokenExpiredException $e) {
            return $this->tokenErrorResponse('token_expired');
        } catch (TokenInvalidException $e) {
            return $this->tokenErrorResponse('token_invalid');
        } catch (JWTException $e) {
            return $this->tokenErrorResponse('token_absent');
        }

        $user = $this->jwtAuth->user();
        $responseData = [
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'company' => '',
                'type' => 'bearer',
            ],
        ];

        return $this->responseData($responseData);
    }

    public function register(array $data): JsonResponse
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'current_ad_count' => 1,
            'password' => Hash::make($data['password']),
        ]);
        $user->save();
        $token = JWTAuth::fromUser($user);
        $responseData = [
            'user' => $user,
            'company' => false,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ];
        return response()->json($responseData, JsonResponse::HTTP_OK);
    }

    public function forgotPassword(string $email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'user-not-found',
                'statusCode' => 404,
            ];
        }
        $resetToken = md5($email);
        $user->reset_token = $resetToken;
        $user->save();
        $this->sendResetEmail($user);
        return [
            'status' => 'success',
            'data' => [
                'token' => $resetToken,
                'email' => $user->email,
            ],
            'statusCode' => 200,
        ];
    }

    protected function sendResetEmail($user): void
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'reset_token' => $user->reset_token,
        ];
        Mail::send('backend.mail.forget-password', $data, function ($message) use ($user) {
            $message->to($user->email);
            $message->subject(__('backend.confirm-your-password'));
        });
    }

    public function resetPassword(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return $this->errorResponse('email-not-found', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($data['token'] !== $user->reset_token) {
            return $this->errorResponse('token-is-not-match-email', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $validator = $this->validatePassword($data);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'password-validation-failed',
                'errors' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->password = Hash::make($data['new_password']);
        $user->reset_token = null;
        $user->save();

        return response()->json([
            'message' => 'password-updated-successfully',
        ], JsonResponse::HTTP_OK);
    }

    public function changePassword(array $data)
    {
        if (!$this->user->passwordIsBeingUpdated($data)) {
            return $this->changeName($data);
        } else {
            return $this->changePasswordInternal($data);
        }
    }

    protected function changeName($data)
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->user->name = $data->input('name');
        $this->user->save();

        return $this->successResponse('name-changed-successfully');
    }

    protected function changePasswordInternal($data)
    {
        $validator = Validator::make($data->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$this->user->passwordMatches($data->input('current_password'))) {
            return $this->errorResponse('current-password-mismatch', JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->user->password = Hash::make($data->input('new_password'));
        $this->user->save();

        return $this->successResponse('password-changed-successfully');
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json([
            'message' => 'logged-out-successfully',
        ], JsonResponse::HTTP_OK);
    }
    protected function tokenErrorResponse($errorType): JsonResponse
    {
        return response()->json(['error' => $errorType], Response::HTTP_UNAUTHORIZED);
    }
}
