<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'locales' => config('app.available_locales'),
            'oauthProviders' => $this->enabledOauthProviders(),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * Get the list of OAuth providers that have credentials configured.
     *
     * @return list<string>
     */
    private function enabledOauthProviders(): array
    {
        $providers = ['google', 'github', 'discord'];

        return array_values(array_filter($providers, function (string $provider): bool {
            return config("services.{$provider}.client_id") && config("services.{$provider}.client_secret");
        }));
    }
}
