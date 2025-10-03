<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ResetPassword extends Component
{
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';
    public $token = '';
    public $urlID = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8|same:passwordConfirmation'
    ];

    public function mount($token = null, $id = null)
    {
        if ($token) {
            $this->token = $token;
            $this->email = request()->query('email', '');
        } elseif ($id) {
            $existingUser = User::find($id);
            if ($existingUser) {
                $this->urlID = intval($existingUser->id);
            }
        }
    }

    public function resetPassword()
    {
        $this->validate();

        if ($this->token) {
            $status = Password::reset(
                [
                    'email' => $this->email,
                    'password' => $this->password,
                    'password_confirmation' => $this->passwordConfirmation,
                    'token' => $this->token
                ],
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();
                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('login')->with('status', 'Your password has been reset successfully!');
            }
            return back()->with('email', __($status));
        } else {
            $existingUser = User::where('email', $this->email)->first();
            if ($existingUser && $existingUser->id == $this->urlID) {
                $existingUser->update([
                    'password' => Hash::make($this->password)
                ]);
                return redirect('login')->with('status', 'Your password has been reset!');
            } else {
                return back()->with('email', "We could not find any user with that email address.");
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
