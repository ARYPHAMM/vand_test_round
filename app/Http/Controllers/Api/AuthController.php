<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserInfo;
use App\Infrastructure\Eloquent\User\User;
use App\Infrastructure\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $userRepository;
    protected $deviceTokenRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function register(Request $request)
    {
        $validator = $this->userRepository->canValidate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required|confirmed|min:6'
        ], []);
        if ($validator)
            return apiError($validator->errors()->first(), $validator->errors(), 422);
        $data = $request->only('name', 'email', 'phone_number');
        DB::beginTransaction();
        try {
            $user = new User();
            $this->userRepository->store($user, $data);
            $user->password = Hash::make($request->password);
            $user->save();
            if ($request->has('avatar') && !is_null($request->avatar))
                if (!updateFileByKey($user, 'avatar', $request->avatar))
                    return apiError("Lỗi hình ảnh");
            DB::commit();
            return apiOk(new UserInfo($user));
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return apiError('Register fail', null, 403);
        }
    }
    public function login(Request $request)
    {
        $validator = $this->userRepository->canValidate([
            'phone_number' => 'required',
            'password' => 'required|min:6'
        ], []);
        if ($validator) {
            return apiError($validator->errors()->first(), $validator->errors(), 422);
        }
        return $this->userLogin($request);
    }
    protected function userLogin($request)
    {
        $data = $request->all();
        $user = $this->userRepository->listBy('phone_number', $data['phone_number']);
        $user = $user->first();
        if (!$user) {
            return apiError('Account not found');
        }
        $credential = $request->only('phone_number', 'password');
        if ($tokenGenerateJwt = JWTAuth::attempt(array_merge($credential, ['id' => $user->id]))) {
            $resource = new UserInfo($user);
            $reponse = $resource->additional(['token' => $tokenGenerateJwt]);
            return apiOk($reponse);
        }
        return apiError('Account password incorrect');
    }
    public function me(Request $req)
    {
        $user = auth('api')->user();
        if (!$user) {
            return apiError("Not login", null, 403);
        }
        return apiOk(new UserInfo($user));
    }
    public function logout(Request $request)
    {
        try {
            $user = auth('api')->user();
            auth('api')->logout();
            return  apiOk(true);
        } catch (\Exception $ex) {
            return apiError('Server error', null, 500);
        }
    }
    public function updatePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $validate = $this->userRepository->canValidate([
                'current_password' => 'required',
                'password' => 'required|confirmed|min:6',
            ], []);
            if ($validate) {
                return apiError($validate->errors()->first(), $validate->errors(), 422);
            }
            $current_password = @$request->current_password;
            if (!Auth::attempt(['password' => $current_password, 'id' => $user->id])) {
                return apiError("The old password is incorrect");
            }
            $password = @$request->password;
            $user->password = Hash::make($password);
            $user->save();
            DB::commit();
            return apiOk(new UserInfo($user));
        } catch (\Throwable $th) {
            DB::rollback();
            return apiError("Error");
        }
    }
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $validate = $this->userRepository->canValidate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id . ',id',
                'phone_number' => 'required|unique:users,phone_number,' . $user->id . ',id',
            ], []);
            if ($validate) {
                return apiError($validate->errors()->first(), $validate->errors(), 422);
            }
            $data = $request->only('name', 'email', 'phone_number');
            if ($user)
                $this->userRepository->store($user, $data);
            $user->save();
            if ($request->has('avatar') && !is_null($request->avatar))
                if (!updateFileByKey($user, 'avatar', $request->avatar))
                    return apiError("Lỗi hình ảnh");
            DB::commit();
            return apiOk(new UserInfo($user));
        } catch (\Throwable $th) {
            DB::rollback();
            return apiError("Có lỗi xảy ra");
        }
    }
}
