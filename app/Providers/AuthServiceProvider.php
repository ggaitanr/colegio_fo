<?php

namespace App\Providers;

use App\Menu;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addMinutes(350));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(15));

        $menus = Menu::all(); 
        $scopes = [];

        foreach ($menus as $menu) {
            $name = strtolower($menu->name);
            $scope = [$name=>''];
            $scopes = array_merge($scopes, $scope);
        }
        //dd($scopes);
        Passport::tokensCan($scopes);
    }
}
