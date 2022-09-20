<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumberController extends Controller
{
    // Convert large number into short e.g. 1000 to 1K 10000 to 10K etc
    public static function short_number( $num ) 
    {
        $units = [ '', 'K', 'M', 'B' ];
        for( $i = 0; $num >= 1000; $i++ ) {
            $num /= 1000;
        }

        return round( $num, 1 ) . $units[ $i ];
    }
}
