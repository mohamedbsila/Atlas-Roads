<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email:rfc,dns|unique:users',
        'password' => 'required|min:6'
    ];

    public function register()
    {
        $this->validate();
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
<<<<<<< HEAD
            'password' => Hash::make($this->password)
=======
            'password' => Hash::make($this->password),
            'is_admin' => false // New users are not admin by default
>>>>>>> origin/complet
        ]);

        auth()->login($user);

<<<<<<< HEAD
        return redirect('/dashboard');
=======
        // Regular users go to home page
        return redirect('/');
>>>>>>> origin/complet
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
