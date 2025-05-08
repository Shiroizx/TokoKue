<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\ProfileImage; // Tambahkan baris ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user profile page
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        // Debug to check table structure
        $tableColumns = DB::getSchemaBuilder()->getColumnListing('customers');
        \Illuminate\Support\Facades\Log::info('Customer Table Columns:', $tableColumns);
        
        $customer = $user->customer ?? Customer::firstOrCreate(['user_id' => $user->id]);
        
        return view('buyer.profile.show', compact('user', 'customer'));
    }

    /**
     * Show the profile edit form
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        $customer = $user->customer ?? Customer::firstOrCreate(['user_id' => $user->id]);
        
        // Ambil gambar profil terbaru
        $latestProfileImage = $user->profileImages()->latest()->first();
        
        return view('user.profile.edit', compact('user', 'customer', 'latestProfileImage'));
    }

    /**
     * Update user and customer profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'pos' => 'nullable|string|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Validasi password
            'current_password' => [
                'nullable', 
                'required_with:password', 
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Password lama tidak sesuai.');
                    }
                }
            ],
            'password' => [
                'nullable', 
                'string', 
                'min:8', 
                'confirmed',
                'different:current_password'
            ],
        ], [
            // Pesan error kustom
            'password.different' => 'Password baru harus berbeda dengan password lama.',
            'current_password.required_with' => 'Harap masukkan password lama jika ingin mengubah password.',
        ]);

        // Update data user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->pos = $validated['pos'] ?? null;
        
        // Update password jika disediakan
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        // Get or create customer record
        $customer = Customer::firstOrCreate(['user_id' => $user->id]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Validasi ulang file gambar
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Hapus gambar profil lama
            $oldProfileImage = ProfileImage::where('user_id', $user->id)->latest()->first();
            if ($oldProfileImage) {
                // Hapus file fisik
                if (Storage::disk('public')->exists($oldProfileImage->image_path)) {
                    Storage::disk('public')->delete($oldProfileImage->image_path);
                }
                
                // Hapus record lama
                $oldProfileImage->delete();
            }

            // Simpan gambar baru
            $path = $request->file('image')->store('profile-images', 'public');
            
            // Buat record baru di profile_images
            ProfileImage::create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'image_path' => $path
            ]);

            // Hapus gambar lama di customer jika ada
            if ($customer->image && Storage::disk('public')->exists($customer->image)) {
                Storage::disk('public')->delete($customer->image);
            }
            
            // Kosongkan field image di tabel customer
            $customer->image = null;
        }
        
        try {
            $customer->save();
        } catch (\Exception $e) {
            Log::error('Error saving customer: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan profil');
        }
        
        return redirect()->route('buyer.profile.edit')
            ->with('success', 'Profile berhasil diperbarui!');
    }
}