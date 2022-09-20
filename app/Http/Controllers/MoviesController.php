<?php

namespace App\Http\Controllers;

use App\User;
use App\Vote;
use App\Review;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    var $request = array( 'action' => 'sort_by_date_most_recent' );

    public function has_user_liked( $movieid )
    {           
        $userid = Auth::user()->id;

        return DB::select("SELECT * FROM votes
                           WHERE movie_id = $movieid AND user_id = $userid AND vote_like IS TRUE");
    }

    public function has_user_hated( $movieid )
    {           
        $userid = Auth::user()->id;

        return DB::select("SELECT * FROM votes
                           WHERE movie_id = $movieid AND user_id = $userid AND vote_hate IS TRUE");
    }

    public function get_users_by_like( $movieid )
    {           
        return DB::table('votes')
            ->selectRaw('username')
            ->leftJoin('users', 'votes.user_id', '=', 'users.id')
            ->where('movie_id', '=', $movieid)
            ->where('vote_like', '=', TRUE)
            ->orderBy('username', 'ASC')
            ->get()
            ->pluck('username')
            ->toArray();
    }

    public function get_users_by_hate( $movieid )
    {           
        return DB::table('votes')
            ->selectRaw('username')
            ->leftJoin('users', 'votes.user_id', '=', 'users.id')
            ->where('movie_id', '=', $movieid)
            ->where('vote_hate', '=', TRUE)
            ->orderBy('username', 'ASC')
            ->get()
            ->pluck('username')
            ->toArray();
    }

    public function get_reviews( $movieid )
    {          
        return DB::select("SELECT UNIX_TIMESTAMP( created_at ) AS creation_date, username, review 
                           FROM reviews 
                           LEFT JOIN users ON reviews.user_id = users.id
                           WHERE movie_id = $movieid
                           ORDER BY creation_date DESC");
    }

    public function get_movies( $request )
    {   
        $select = "movies.id AS movieid, movies.title, movies.description, 
                   DATE_FORMAT( movies.created_at, '%d/%m/%Y' ) AS posted, 
                   users.username AS posted_by";

         return DB::table('movies')
            ->when( $request, function( $query ) use( &$select, $request )
            {   
                switch( $request['action'] )
                {
                    case 'sort_by_text':
                        $query->where('movies.title', 'LIKE', '%' . $request['searchtext'] . '%');
                        $query->orWhere('movies.description', 'LIKE', '%' . $request['searchtext'] . '%');
                        $query->orWhere('users.username', 'LIKE', '%' . $request['searchtext'] . '%');
                        $query->orderBy('movies.created_at', 'DESC');

                        break;
                    case 'sort_by_title':
                        $query->orderBy('movies.title', 'ASC');

                        break;
                    case 'sort_by_likes':
                        $select = "COUNT( votes.movie_id ) AS total_likes, $select";
                        $query->leftJoin('votes', function( $join ) 
                        {
                            $join->on('votes.movie_id', '=', 'movies.id')->where('votes.vote_like', '=', true);
                        });
                        $query->orderBy('total_likes', 'DESC');
                        
                        break;
                    case 'sort_by_hates':
                        $select = "COUNT( votes.movie_id ) AS total_hates, $select";
                        $query->leftJoin('votes', function( $join ) 
                        {
                            $join->on('votes.movie_id', '=', 'movies.id')->where('votes.vote_hate', '=', true);
                        });
                        $query->orderBy('total_hates', 'DESC');
                            
                        break;
                    case 'sort_by_reviews':
                        $select = "COUNT( reviews.movie_id ) AS total_reviews, $select";
                        $query->leftJoin('reviews', function( $join ) 
                        {
                            $join->on('reviews.movie_id', '=', 'movies.id');
                        });
                        $query->orderBy('total_reviews', 'DESC');
                                
                        break;
                    case 'sort_by_author':
                        $query->orderBy('users.username', 'ASC');
                        $query->orderBy('movies.created_at', 'DESC');

                        break;
                    case 'sort_by_date_oldest':
                        $query->orderBy('movies.created_at', 'ASC');

                        break;
                    default:
                        $query->orderBy('movies.created_at', 'DESC');

                        break;
                }

                return $query;
            })
            ->selectRaw($select)
            ->leftJoin('users', 'movies.user_id', '=', 'users.id')
            ->groupBy('movies.id')
            ->get()
            ->toArray();
    }

    public function get_movies_data( $request )
    {   
        $movies = $this->get_movies( $request );
        
        foreach( $movies as $movie )
        {
            $movie->users_by_like = $this->get_users_by_like( $movie->movieid );
            $movie->users_by_hate = $this->get_users_by_hate( $movie->movieid );
            $movie->reviews = $this->get_reviews( $movie->movieid );            
        }
        
        return $movies;
    }

    /**
     * Show the list of movies.
     */
    public function index()
    {           
        return view('movies.index', [
            'movies' => $this->get_movies_data( $this->request )
        ]);
    }

    /**
     * Show the list of movies per user.
     * 
     * @param Auth $user
     */
    public function show( $user )
    {           
        $user = User::findOrFail( $user );

        return view('movies.index', [
            'movies' => $this->get_movies_data( $this->request )
        ]);
    }

    /**
     * Sort movies.
     * 
     * @param Request $request
     */
    public function movie_sort( Request $request )
    {   
        $this->request['action'] = $request->action;
        if( $request->has( 'searchtext' ) ) {
            $this->request['searchtext'] = $request->searchtext;
        }

        $movies = $this->get_movies_data( $this->request );

        $movies_data = '';
        foreach( $movies as $movie ) {
            $movies_data .= view( 'movies.movie', [ 'movie' => $movie ] )->render();
        }
        
        return response()->json([
            'movies_data' => $movies_data
        ]);
    }

    /**
     * Validate movie.
     * 
     * @param Request $request
     */
    public function movie_validate( Request $request )
    {          
        $popupDialogData = array();
        parse_str( $request->popupDialogData, $popupDialogData );

        $unique = ( $request->movieid )? 'unique:movies,title,' . $request->movieid : 'unique:movies';

        $validator = validator( $popupDialogData, [
            'title' => ['required', $unique],
            'description' => 'required',
        ]);

        if( $validator->fails() ) {
            return response()->json( $validator->messages() );
        }        

        return response()->json( array( 'resp' => 'success' ) );
    }

    /**
     * Store new movie.
     * 
     * @param Request $request
     */
    public function movie_store( Request $request )
    {          
        $popupDialogData = array();
        parse_str( $request->popupDialogData, $popupDialogData );

        Auth::user()->movies()->create( $popupDialogData );

        $sort_request = new Request( [ 'action' => $request->sort_by ] );
        return $this->movie_sort( $sort_request );
    }

    /**
     * Update movie.
     * 
     * @param Request $request
     */
    public function movie_update( Request $request )
    {          
        $popupDialogData = array();
        parse_str( $request->popupDialogData, $popupDialogData );

        Auth::user()->movies()->where( 'id', $request->movieid )->update( $popupDialogData );

        $sort_request = new Request( [ 'action' => $request->sort_by ] );
        return $this->movie_sort( $sort_request );
    }

    /**
     * Delete movie.
     * 
     * @param Request $request
     */
    public function movie_delete( Request $request )
    {          
        Auth::user()->votes()->where( 'movie_id', $request->movieid )->delete();
        Auth::user()->reviews()->where( 'movie_id', $request->movieid )->delete();
        Auth::user()->movies()->where( 'id', $request->movieid )->delete();

        $sort_request = new Request( [ 'action' => $request->sort_by ] );
        return $this->movie_sort( $sort_request );
    }

    /**
     * Vote movie.
     * 
     * @param Request $request
     */
    public function movie_vote( Request $request )
    {          
        $has_user_liked = ( !empty( $this->has_user_liked( $request->movieid ) ) );
        $has_user_hated = ( !empty( $this->has_user_hated( $request->movieid ) ) );

        $data = array();
        $data['movie_id'] = $request->movieid;

        Auth::user()->votes()->where( 'movie_id', $request->movieid )->delete( $data );
        
        if( $request->action == 'like' ) 
        {
            if( !$has_user_liked ) 
            {    
                $data['vote_like'] = true;
                Auth::user()->votes()->create( $data );
            }
        }
        else if( $request->action == 'hate' ) 
        {
            if( !$has_user_hated ) 
            {
                $data['vote_hate'] = true;
                Auth::user()->votes()->create( $data );
            }
        }

        $sort_request = new Request( [ 'action' => $request->sort_by ] );
        return $this->movie_sort( $sort_request );
    }

    /**
     * Validate review.
     * 
     * @param Request $request
     */
    public function review_validate( Request $request )
    {          
        $popupDialogData = array();
        parse_str( $request->popupDialogData, $popupDialogData );

        $validator = validator( $popupDialogData, [
            'review' => 'required'
        ]);

        if( $validator->fails() ) {
            return response()->json( $validator->messages() );
        }        

        return response()->json( array( 'resp' => 'success' ) );
    }

    /**
     * Store new review.
     * 
     * @param Request $request
     */
    public function review_store( Request $request )
    {          
        $data = array();
        parse_str( $request->popupDialogData, $data );

        $data['movie_id'] = $request->movieid;

        Auth::user()->reviews()->create( $data );

        $sort_request = new Request( [ 'action' => $request->sort_by ] );
        return $this->movie_sort( $sort_request );
    }
}
