<?php
namespace App\Repositories\Auth;

interface AuthRepositoryInterface
{
    public function login(array $credentials);
    public function refresh();
    public function register(array $data);
    public function forgotPassword(string $email);
    public function resetPassword(array $data);
    public function changePassword(array $request);
    public function logout();
}
