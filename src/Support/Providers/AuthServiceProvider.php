<?php 
namespace Jaguar\Core\Security\Support\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

use Jaguar\Core\Security\Auth\UserAuthProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Auth::provider('jaguar-auth-driver', function ($app, array $config) {
            return new UserAuthProvider($app['hash'], $config['model'], $config['request']);
        }); 
    }
}