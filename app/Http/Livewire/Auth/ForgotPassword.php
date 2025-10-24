<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function recoverPassword()
    {
        if (env('IS_DEMO')) {
            return back()->with('demo', "You are in a demo version, resetting password is disabled.");
        }

        $this->validate();

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'We have emailed your password reset link!');
        }

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
