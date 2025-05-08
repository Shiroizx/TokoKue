<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfileImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,seller,buyer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,seller,buyer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validasi file gambar
        ]);

        // Update password jika ada
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }

        // Update data user
        $user->update($validatedData);

        // Logika untuk mengupdate foto profil
        if ($request->hasFile('photo')) {
            // Hapus foto profil lama jika ada
            $userPhoto = ProfileImage::where('user_id', $user->id)->first();
            if ($userPhoto && file_exists(storage_path('app/public/' . $userPhoto->image_path))) {
                // Hapus foto lama dari storage
                unlink(storage_path('app/public/' . $userPhoto->image_path));
            }

            // Simpan foto baru
            $photo = $request->file('photo');
            $photoPath = $photo->store('profile-images', 'public');  // Menyimpan foto di folder public/storage/profile-images

            // Update data gambar di tabel profile_images
            if ($userPhoto) {
                $userPhoto->update(['image_path' => $photoPath]);  // Update path gambar di tabel profile_images
            } else {
                // Jika tidak ada gambar, simpan sebagai entri baru
                ProfileImage::create([
                    'user_id' => $user->id,
                    'image_path' => $photoPath,
                ]);
            }
        }

        // Redirect ke halaman index users dengan pesan sukses
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }


    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}