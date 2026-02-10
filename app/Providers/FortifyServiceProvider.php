<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\LoginResponse; // ADDED: Your custom response file
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract; // ADDED: Contract to bind our custom response to
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(callback: CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(callback: UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(callback: UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(callback: ResetUserPassword::class);

        // ADDED: This binds your custom LoginResponse logic to the Fortify system
        $this->app->singleton(
            LoginResponseContract::class,
            LoginResponse::class
        );

        Fortify::redirectUserForTwoFactorAuthenticationUsing(callback: RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for(name: 'login', callback: function (Request $request): Limit {
            $throttleKey = Str::transliterate(string: Str::lower(value: $request->input(key: Fortify::username())) . '.' . $request->ip());

            return Limit::perMinute(maxAttempts: 5)->by(key: $throttleKey);
        });

        RateLimiter::for(name: 'two-factor', callback: function (Request $request): Limit {
            return Limit::perMinute(maxAttempts: 5)->by(key: $request->session()->get(key: 'login.id'));
        });
    }
}
