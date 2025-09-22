<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication Routes for Medical Ecommerce System
 * 
 * This file defines all authentication-related routes for the medical ecommerce
 * application. Routes are organized by middleware groups to ensure proper
 * access control and security.
 * 
 * Route Groups:
 * - Guest routes: Registration, login, password reset (accessible only to non-authenticated users)
 * - Authenticated routes: Email verification, password confirmation, logout (accessible only to authenticated users)
 * 
 * Security Features:
 * - Guest middleware prevents authenticated users from accessing login/register pages
 * - Auth middleware ensures only authenticated users can access protected routes
 * - Signed URLs for email verification to prevent tampering
 * - Throttling to prevent brute force attacks
 * - CSRF protection on all forms
 * 
 * @package Routes
 */

// Guest Routes - Only accessible to non-authenticated users
Route::middleware('guest')->group(function () {
    // User Registration Routes
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');                                          // Show registration form

    Route::post('register', [RegisteredUserController::class, 'store']); // Process registration

    // User Login Routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');                                             // Show login form

    Route::post('login', [AuthenticatedSessionController::class, 'store']); // Process login

    // Password Reset Routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');                                  // Show forgot password form

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');                                    // Send password reset email

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');                                    // Show password reset form

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');                                    // Process password reset
});

// Authenticated Routes - Only accessible to authenticated users
Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');                               // Show email verification notice

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])                    // Signed URL + throttling
        ->name('verification.verify');                               // Verify email address

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')                                // Throttle resend requests
        ->name('verification.send');                                 // Resend verification email

    // Password Confirmation Routes
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');                                  // Show password confirmation form

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']); // Process password confirmation

    // Password Update Route
    Route::put('password', [PasswordController::class, 'update'])->name('password.update'); // Update user password

    // Logout Route
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');                                            // Process user logout
});
