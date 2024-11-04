<?php

namespace App\Filament\Student\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class Register extends BaseRegister
{
    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                ]))
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $user = $this->getUserModel()::create($data);

        // Assign the student role immediately after creation
        $user->assignRole('student');

        event(new Registered($user));

        // Use Laravel's Auth facade instead of authMiddleware
        Auth::login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
