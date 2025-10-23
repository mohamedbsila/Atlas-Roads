<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Models\User;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required',
    ];

    public function mount()
    {
        $this->fill(['email' => 'admin@softui.com', 'password' => 'secret']);
    }

    public function login()
    {
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $user = User::where(["email" => $this->email])->first();
            auth()->login($user, $this->remember_me);
            
            // Rediriger vers la page d'accueil si l'utilisateur n'est pas administrateur
            if (!$user->is_admin) {
                return redirect()->intended('http://localhost:8000');
            }
            
            // Sinon (si admin), rediriger vers le tableau de bord
            return redirect()->intended('/dashboard');
        } else {
            return $this->addError('email', trans('auth.failed'));
        }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.app');
    }
}
