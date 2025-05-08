<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'terms.accepted' => 'You must accept the terms of service',
        ]);

        // Create user with role buyer
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'buyer',
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Create customer record
        Customer::create([
            'user_id' => $user->id,
            'google_id' => null,
            'google_token' => null,
        ]);

        // Login the user immediately after registration
        Auth::login($user);

        // Regenerate session
        $request->session()->regenerate();

        // Redirect to buyer home
        return redirect()->route('buyer.home')
            ->with('success', 'Registration successful! Welcome to Toko Kue Kelompok 2');
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Get authenticated user
            $user = Auth::user();
            
            // Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->role === 'seller') {
                return redirect()->intended(route('seller.dashboard'));
            } elseif ($user->role === 'buyer') {
                return redirect()->intended(route('buyer.home'));
            }
            
            // Default redirect
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
         return Socialite::driver('google')
            ->stateless()
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->redirectUrl(config('services.google.redirect'))
            ->user();
        
        // Buat password default menggunakan nama + tanggal + bulan + tahun
        $defaultPassword = $this->generateDefaultPassword($googleUser->getName());
        
        // Check if user exists
        $user = User::where('email', $googleUser->getEmail())->first();
        
        // If user doesn't exist, create new user
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make($defaultPassword),
                'role' => 'buyer', // Default role for Google login
            ]);
            
            // Create customer record
            Customer::create([
                'user_id' => $user->id,
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
            ]);
        } else {
            // Update Google ID and token if user exists but hasn't used Google login before
            $customer = Customer::where('user_id', $user->id)->first();
            
            if (!$customer) {
                Customer::create([
                    'user_id' => $user->id,
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                ]);
            } else {
                // Update Google credentials
                $customer->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                ]);
            }
            
            // Jika user sudah ada tapi rolenya bukan buyer, pastikan rolenya diupdate menjadi buyer
            if ($user->role !== 'buyer') {
                $user->update(['role' => 'buyer']);
            }
        }
        
        // Login the user
        Auth::login($user);
        
        // Regenerate session
        request()->session()->regenerate();
        
        // Langsung arahkan ke halaman buyer.products
        return redirect()->route('buyer.home')
            ->with('info', 'Password default Anda adalah: ' . $defaultPassword . '. Silakan ganti password Anda.');
    }

    /**
     * Generate default password based on name and current date
     * 
     * @param string $name
     * @return string
     */
    private function generateDefaultPassword($name)
    {
        // Hapus angka dari nama
        $nameWithoutNumbers = preg_replace('/\d+/', '', $name);
        
        // Ambil nama depan setelah membersihkan angka dan spasi
        $cleanedName = trim($nameWithoutNumbers);
        $words = explode(' ', $cleanedName);
        $firstName = $words[0];
        
        // Gunakan format: NamaDepan + tanggal + bulan + tahun
        $currentDate = now();
        $formattedPassword = $firstName . $currentDate->format('d-m-Y');
        
        return $formattedPassword;
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}