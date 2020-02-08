<?php
namespace Jaguar\Core\Security;

use Illuminate\Support\ServiceProvider;

class MainProvider extends ServiceProvider{

    public function boot(){
    }

    public function register(){    	
    	$this->app->register('Jaguar\Core\Security\Support\Providers\AuthServiceProvider');
    }

}
