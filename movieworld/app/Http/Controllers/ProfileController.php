<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show( $user )
    {
        $user = User::findOrFail( $user );
        
        return view('profiles.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update profile.
     * 
     * @param Request $request
     */
    public function update( Request $request )
    {      
        $user = User::findOrFail( $request->user );
        
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique( 'users', 'username' )->ignore( $user->id ) ],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique( 'users', 'email' )->ignore( $user->id ) ],
            'password' => ( $request->password || $request->password_confirmation )? ['required', 'string', 'min:4', 'confirmed'] : '',
        ]);

        $data = array(
            'username' => $request->username,
            'email' => $request->email
        );
        if( $request->password ) {
            $data['password'] = Hash::make( $request->password );
        }

        Auth::user()->where( 'id', $user->id )->update( $data );
        
        return redirect()->route('movies.user', [ $user->id ]);
    }
}
