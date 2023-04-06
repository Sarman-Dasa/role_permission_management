<?php

namespace App\Facades;
  
use Carbon\Carbon;
  
class ItDateClass {
  
    public function dateFormatYMD($date){
        return Carbon::createFromFormat('m/d/Y', $date)
                ->format('Y-m-d');
    }
  
    public function dateFormatMDY($date){
        return Carbon::createFromFormat('Y-m-d', $date)
                ->format('m/d/Y');
    }

    public function logout()
    {
        $user = auth()->user()->tokens();
        $user->delete();
        return ok("User logout successfullt");
    }
}

?>