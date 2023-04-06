<?php
    namespace App\Facades;

use Illuminate\Support\Facades\Facade;

    class ItDateClassFacade extends Facade
    {
        protected static function getFacadeAccessor()
        {
            return 'itdateclass';
        }
    }
?>