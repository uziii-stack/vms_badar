<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;
use Laratrust\Models\Role;
use Laratrust\Models\Permission;

class SignUpController extends Controller
{

    protected function badge($characters, $prefix)
    {
        $possible = '0123456789';
        $code = $prefix;
        $i = 0;
        while ($i < $characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if ($i < $characters - 1) {
                $code .= "";
            }
            $i++;
        }
        return $code;
    }
    protected function basicRolesAndTeams($user)
    {
        $role = Role::where('name', 'user')->first();
        $permission = Permission::where('name', 'read')->first();
        $user->addRole($role);
        $user->givePermission($permission);
    }

    public function signUp(Request $req)
    {
        $uid = (string) Str::uuid();
        $user = new User();
        $user->uid = $uid;
        $user->name = $req->username;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->activation_code = $this->badge(8, "");
        $user->status = 1;
        $user->activated = 1;
        $savedUser = 0;
        try {
            $savedUser = $user->save();
            $this->basicRolesAndTeams($user);
            if ($savedUser) {
                $emailSent = (new MailOtpController)->html_email($uid);
                return $emailSent ? redirect()->route("accountActivation") : redirect()->back()->with('error', 'Email Address already Exist error : ');
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[2]) {
                return  redirect()->back()->with('error', 'Email Address already Exist error : ' . $exception->errorInfo[2]);
            } else {
                return  redirect()->back()->with('error', $exception->errorInfo[2]);
            }
        }
    }

    public function signUpAPI(Request $req)
    {
        // ✅ validate request first
        $validator = Validator::make($req->all(), [
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // ✅ create user
            $user = new User();
            $user->uid             = (string) Str::uuid();
            $user->name            = $req->username ?? explode('@', $req->email)[0];
            $user->email           = $req->email;
            $user->password        = Hash::make($req->password);
            $user->activation_code = $this->badge(8, "");
            $user->status          = 1;
            $user->activated       = 1;
            $user->save();

            // ✅ assign roles/teams if needed
            $role = Role::where('name', 'web_user')->first();
            if ($role == null) {
                $role = new Role();
                $role->name = 'web_user';
                $role->display_name = 'WebUser'; // optional
                $role->description = 'Web User'; // optional
                $role->save();
            }
            $permission = Permission::where('name', 'create')->first();
            $user->addRole($role);
            $user->givePermission($permission);

            // ✅ create Sanctum token
            $token = $user->createToken('API Token')->plainTextToken;

            // ✅ optional: send activation mail
            //(new MailOtpController)->html_email($user->uid);

            return response()->json([
                'status'  => true,
                'message' => 'User registered successfully',
                'user'    => $user,
                'token'   => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Registration failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
};
