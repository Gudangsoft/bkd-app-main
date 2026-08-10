<?php

use App\Models\Setting;

if( !function_exists('site') )
{
    function site()
    {
         return Setting::first();
    }
}
