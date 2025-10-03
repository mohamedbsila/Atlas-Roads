<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;

class ForgotPassword extends Component
{
    use Notifiable;
    public $email = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function routeNotificationForMail()
    {
        return $this->email;
    }
    public function recoverPassword()
    {
        if (env('IS_DEMO')) {
            return back()->with('demo', "You are in a demo version, resetting password is disabled.");
        }

        $this->validate();
        $user = User::where('email', $this->email)->first();
        
        if ($user) {
            $status = Password::sendResetLink(
                ['email' => $this->email]
            );

            if ($status === Password::RESET_LINK_SENT) {
                $this->notify(new ResetPassword($user->id));
                return back()->with('status', "We have emailed your password reset link!");
            }
        }

        return back()->with('email', "We could not find any user with that email address.");
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
