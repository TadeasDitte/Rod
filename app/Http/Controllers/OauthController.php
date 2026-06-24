<?php

namespace App\Http\Controllers;

use App\Models\OauthProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class OauthController extends Controller
{
    /**
     * Supported OAuth providers.
     *
     * @var list<string>
     */
    private const PROVIDERS = ['google', 'github', 'discord'];

    /**
     * Redirect the user to the OAuth provider.
     */
    public function redirect(Request $request, string $provider): SymfonyRedirectResponse
    {
        $this->validateProvider($provider);

        $request->session()->put('oauth.intended', $request->query('intended'));

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the OAuth provider callback.
     */
    public function callback(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            /** @var SocialiteUser $socialUser */
            $socialUser = Socialite::driver($provider)->user();
        } catch (InvalidStateException $e) {
            return $this->redirectToLogin('sso.invalid_state');
        } catch (\Throwable $e) {
            return $this->redirectToLogin('sso.error');
        }

        // Check if this provider account is already linked
        $oauthProvider = OauthProvider::where('provider', $provider)
            ->where('provider_id', (string) $socialUser->getId())
            ->first();

        if ($request->user()) {
            // Authenticated user — link the account
            return $this->handleLinking($request->user(), $provider, $socialUser, $oauthProvider);
        }

        // Guest — log in or register
        if ($oauthProvider) {
            Auth::login($oauthProvider->user, true);

            return redirect()->intended(config('fortify.home'));
        }

        return $this->registerOrLogin($provider, $socialUser);
    }

    /**
     * Link an OAuth provider to the authenticated user.
     */
    private function handleLinking(
        User $user,
        string $provider,
        SocialiteUser $socialUser,
        ?OauthProvider $oauthProvider,
    ): RedirectResponse {
        if ($oauthProvider && $oauthProvider->user_id !== $user->id) {
            return redirect()
                ->route('security.edit')
                ->with('error', __('sso.already_linked'));
        }

        if ($oauthProvider && $oauthProvider->user_id === $user->id) {
            return redirect()->route('security.edit');
        }

        $this->createOauthProvider($user, $provider, $socialUser);

        return redirect()
            ->route('security.edit')
            ->with('status', __('sso.linked', ['provider' => ucfirst($provider)]));
    }

    /**
     * Register a new user or log in an existing one by email.
     */
    private function registerOrLogin(string $provider, SocialiteUser $socialUser): RedirectResponse
    {
        $email = $socialUser->getEmail();

        if (! $email) {
            return $this->redirectToLogin('sso.no_email');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? $email,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Str::random(64),
            ]);
        }

        $this->createOauthProvider($user, $provider, $socialUser);

        Auth::login($user, true);

        return redirect()->intended(config('fortify.home'));
    }

    /**
     * Unlink an OAuth provider from the authenticated user.
     */
    public function destroy(Request $request, string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        /** @var User $user */
        $user = $request->user();

        $user->oauthProviders()
            ->where('provider', $provider)
            ->delete();

        return redirect()
            ->route('security.edit')
            ->with('status', __('sso.unlinked', ['provider' => ucfirst($provider)]));
    }

    /**
     * Create or update an OAuth provider record.
     */
    private function createOauthProvider(User $user, string $provider, SocialiteUser $socialUser): OauthProvider
    {
        return OauthProvider::updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => (string) $socialUser->getId(),
            ],
            [
                'user_id' => $user->id,
                'avatar' => $socialUser->getAvatar(),
                'nickname' => $socialUser->getNickname(),
                'token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
                'expires_at' => $socialUser->expiresIn
                    ? now()->addSeconds($socialUser->expiresIn)
                    : null,
            ],
        );
    }

    /**
     * Validate that the provider is supported.
     */
    private function validateProvider(string $provider): void
    {
        if (! in_array($provider, self::PROVIDERS, true)) {
            abort(404);
        }
    }

    /**
     * Redirect to the login page with an error message.
     */
    private function redirectToLogin(string $message): RedirectResponse
    {
        return redirect()
            ->route('login')
            ->with('status', __($message));
    }
}
