<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Shop;

class ProfileController extends Controller
{
    /**
     * Display the seller profile page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $seller = Auth::user();
        $shop = Shop::where('user_id', $seller->id)->first();
        return view('seller.profile.index', compact('seller', 'shop'));
    }

    /**
     * Show the password reset form.
     *
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm()
    {
        return view('seller.profile.reset-password');
    }

    /**
     * Show the combined credentials edit form.
     *
     * @return \Illuminate\View\View
     */
    public function editCredentials()
    {
        return view('seller.profile.edit-credentials');
    }

    /**
     * Handle password reset request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $seller = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $seller->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        // Update the password
        $seller->password = Hash::make($request->password);
        $seller->save();

        return redirect()->route('seller.profile.edit-credentials')
            ->with('success', 'Password has been reset successfully.');
    }

    /**
     * Show the email change form.
     *
     * @return \Illuminate\View\View
     */
    public function showChangeEmailForm()
    {
        return view('seller.profile.change-email');
    }

    /**
     * Handle email change request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeEmail(Request $request)
    {
        $seller = Auth::user();

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($seller->id),
            ],
            'password' => 'required',
        ]);

        // Check if password matches
        if (!Hash::check($request->password, $seller->password)) {
            return back()->withErrors(['password' => 'The password is incorrect.'])
                ->withInput();
        }

        // Save the old email for confirmation message
        $oldEmail = $seller->email;

        // Update the email
        $seller->email = $request->email;
        $seller->save();

        // Optionally send verification email if you're using email verification
        // $seller->sendEmailVerificationNotification();

        return redirect()->route('seller.profile.edit-credentials')
            ->with('success', "Email has been changed successfully from {$oldEmail} to {$request->email}.");
    }

    /**
     * Show the full profile edit form.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $seller = Auth::user();
        return view('seller.profile.edit', compact('seller'));
    }

    /**
     * Update the seller's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $seller = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update profile information
        $seller->name = $request->name;
        $seller->phone = $request->phone;
        $seller->address = $request->address;

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($seller->avatar && file_exists(public_path('storage/' . $seller->avatar))) {
                unlink(public_path('storage/' . $seller->avatar));
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $seller->avatar = $avatarPath;
        }

        $seller->save();

        return redirect()->route('seller.profile.index')
            ->with('success', 'Profile has been updated successfully.');
    }
}