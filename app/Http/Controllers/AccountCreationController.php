<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AccountCreationController extends Controller
{
    public function html_email(string $id, string $pass = '')
    {
        $pass = $pass == '' ? Str::password(12, true, true, true, false) : $pass;
        try {
            $user = User::where("uid", $id)->firstOrFail();
            $hash = Hash::make($pass);
            $updatedUser = User::where("uid", $id)->update(['password'=>$hash]);
            $user = json_decode($user);
            $host = request()->getHttpHost();
            if ($user) {
                $data = array('user' => $user, 'host' => $host, 'pass' => $pass);
                $myEmail = $user->email;
                Mail::send('pages.mails.accountDetails', $data, function ($message) use ($myEmail) {
                    $message->to($myEmail, "VMS")->subject('Your Account Details');
                    // $message->cc('atif.shabeeh@badarexpo.com', "VMS")->subject('Your Account Details');
                    $message->bcc("abdul.moeid@badarexpo.com", "VMS")->subject('Your Account Details');
                    $message->from("noreply@ideaspakistan.gov.pk", "VMS");
                });
                return true;
            }
        } catch (QueryException $exception) {
            print_r($exception->errorInfo);
        }
    }
}
