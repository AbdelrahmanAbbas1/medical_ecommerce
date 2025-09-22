<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


/**
 * ProfileController
 * 
 * Handles user profile management for the medical ecommerce system.
 * This controller manages profile editing, updates, and account deletion
 * functionality for authenticated users.
 * 
 * Key Features:
 * - Profile form display
 * - Profile information updates
 * - Email verification reset on email change
 * - Account deletion with password confirmation
 * - Session management on logout
 * 
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile edit form.
     * 
     * This method shows the profile editing form with the current user's
     * information pre-populated. The user is automatically loaded from
     * the authenticated session.
     *
     * @param Request $request The HTTP request containing the authenticated user
     * @return View The profile edit view
     */
    public function edit(Request $request): View
    {
        // Show profile edit form with current user data
        return view('profile.edit', [
            'user' => $request->user(), // Get authenticated user from request
        ]);
    }

    /**
     * Update the user's profile information.
     * 
     * This method updates the user's profile with validated data from the request.
     * If the email address is changed, it resets the email verification status
     * to require re-verification of the new email address.
     *
     * @param ProfileUpdateRequest $request The validated request containing profile data
     * @return RedirectResponse Redirect back to profile edit page with status
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Update user attributes with validated data
        $request->user()->fill($request->validated());

        // If email address was changed, reset email verification status
        // This ensures users must verify their new email address
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Save the updated user information
        $request->user()->save();

        // Redirect back to profile edit page with success status
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account with password confirmation.
     * 
     * This method handles account deletion with security measures:
     * - Requires current password confirmation
     * - Logs out the user before deletion
     * - Invalidates the session and regenerates CSRF token
     * - Redirects to home page after successful deletion
     *
     * @param Request $request The HTTP request containing password confirmation
     * @return RedirectResponse Redirect to home page after account deletion
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate that the user provided their current password
        // This prevents accidental account deletion
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out the user before deleting the account
        Auth::logout();
        
        // Delete the user account (soft delete if enabled)
        $user->delete();

        // Invalidate the session and regenerate CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home page
        return Redirect::to('/');
    }
}
