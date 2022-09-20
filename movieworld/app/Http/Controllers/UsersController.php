<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Delete user.
     * 
     * @param Auth $user
     */
    public function user_delete( $user )
    {          
        $user = User::findOrFail( $user );
        
        Auth::user()->where( 'id', $user->id )->delete();
        Auth::user()->movies()->where( 'user_id', $user->id )->delete();
        Auth::user()->votes()->where( 'user_id', $user->id )->delete();
        Auth::user()->reviews()->where( 'user_id', $user->id )->delete();
    }
}
