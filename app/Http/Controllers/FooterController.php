<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FooterController extends Controller
{
    private static function getRevision( $product_path )
    {
        $status = @shell_exec( "svnversion $product_path" );
        if( preg_match( '/(\d+)M/', $status, $matches ) ) {
            return $matches[1];
        }

        return 0;
    }

    public static function show()
    {           
        $product_path = env('APP_PATH');
        $product_name = env('APP_NAME');
        $product_version = env('APP_VERSION');
        $author = env('APP_AUTHOR');

        return "$author - $product_name v. {$product_version}.".self::getRevision( $product_path );
    }
}
