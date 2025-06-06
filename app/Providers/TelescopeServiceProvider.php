<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\TelescopeServiceProvider as BaseTelescopeServiceProvider;

class TelescopeServiceProvider extends BaseTelescopeServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        // Filter what gets logged
        Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
            return $isLocal ||
                   $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });

        // Telescope access control
        Telescope::auth(function ($request) {
            $user = auth('admin')->user(); // Use custom 'admin' guard

            return $user instanceof Admin &&
                   in_array($user->email, ['admin@encounter.com']);
        });
    }

    /**
     * Hide sensitive request data from Telescope.
     */
    
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Define who can view Telescope (used with Gate::allows).
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return $user instanceof Admin &&
                   in_array($user->email, ['admin@encounter.com']);
        });
    }
}
