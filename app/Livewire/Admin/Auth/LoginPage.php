<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginPage extends Component
{
    #[Title('Admin Login | Web Gizi')]
    
    #[Rule('required|email')]
    public $email = '';
    
    #[Rule('required|min:6')]
    public $password = '';
    
    public $remember = false;

    public function mount()
    {
        // Redirect if already authenticated as admin
        if (Auth::check() && Auth::user()->role === 'admin' && Auth::user()->is_active) {
            return redirect()->route('admin.dashboard');
        }
    }

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            $user = Auth::user();
            
            // Check if user is admin and active
            if ($user->role !== 'admin') {
                Auth::logout();
                session()->flash('error', 'Access denied. Admin privileges required.');
                return;
            }

            if (!$user->is_active) {
                Auth::logout();
                session()->flash('error', 'Your account has been deactivated.');
                return;
            }

            session()->regenerate();
            session()->flash('success', 'Welcome back, ' . $user->name . '!');
            return redirect()->intended(route('admin.dashboard'));
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    #[Layout('layouts.auth')]
    public function render()
    {
        return view('livewire.admin.auth.login-page');
    }
}
