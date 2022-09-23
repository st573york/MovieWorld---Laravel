@php
    use App\Http\Controllers\NumberController;
    use App\Http\Controllers\TimeController;
    use App\Http\Controllers\PopupDialogController;
@endphp

<!-- Movie Data -->
<div id="movie_{{ $movie->movieid }}" class="movie_data">
    <div class="row">
        <div class="col movie_title">
            <span>{{ $movie->title }}</span>
        </div>
        <div class="col movie_posted text-right">
            <span>{{ __('Posted') }} {{ $movie->posted }}</span>
        </div>
    </div>
    <div class="row">
        <div class="col movie_data_middle">
            <span>{{ $movie->description }}</span>
        </div>
    </div>
    <div class="row" style="line-height: 30px;">
        <div class="col-12 col-md-4">
            @php
                $like_votes_num = NumberController::short_number( count( $movie->users_by_like ) );
                $class = ( $like_votes_num && Auth::check() && in_array( Auth::user()->username, $movie->users_by_like ) )? 'liked': '';
            @endphp
            <span class="{{ $class }}">
                @auth
                    @if( $like_votes_num )
                        <a href="" class="dropdown-link only-text" data-toggle="dropdown"><span class="movie_votes_text">{{ $like_votes_num }} {{ __('likes') }}</span></a>
                        <div class="dropdown-menu data">
                        @foreach( $movie->users_by_like as $user )
                            @php
                                $class = ( last( $movie->users_by_like ) == $user )? 'last' : '';
                            @endphp
                            <div class="user_vote_data {{ $class }}">{{ $user }}</div>
                        @endforeach
                        </div>
                    @else
                        {{ $like_votes_num }} {{ __('likes') }}
                    @endif
                @else
                    {{ $like_votes_num }} {{ __('likes') }}
                @endauth
            </span>
            <span style="padding: 0px 3px 0px 3px;">|</span>
            @php
                $hate_votes_num = NumberController::short_number( count( $movie->users_by_hate ) );
                $class = ( $hate_votes_num && Auth::check() && in_array( Auth::user()->username, $movie->users_by_hate ) )? 'hated': '';
            @endphp
            <span class="{{ $class }}">
                @auth
                    @if( $hate_votes_num )
                        <a href="" class="dropdown-link only-text" data-toggle="dropdown"><span class="movie_votes_text">{{ $hate_votes_num }} {{ __('hates') }}</span></a>
                        <div class="dropdown-menu data">
                        @foreach( $movie->users_by_hate as $user )
                            @php
                                $class = ( last( $movie->users_by_hate ) == $user )? 'last' : '';
                            @endphp
                            <div class="user_vote_data {{ $class }}">{{ $user }}</div>
                        @endforeach
                        </div>
                    @else
                        {{ $hate_votes_num }} {{ __('hates') }}
                    @endif
                @else
                    {{ $hate_votes_num }} {{ __('hates') }}
                @endauth
            </span>
            <span style="padding: 0px 3px 0px 3px;">|</span>
            <span>
                @php
                    $reviews_num = NumberController::short_number( count( $movie->reviews ) );
                @endphp
                @auth
                    @if( $reviews_num )
                        <a href="" class="dropdown-link only-text" data-toggle="dropdown"><span class="movie_votes_text">{{ $reviews_num }} {{ __('reviews') }}</span></a>
                        <div class="dropdown-menu data">
                        @foreach( $movie->reviews as $review )
                            @php
                                $class = ( last( $movie->reviews ) == $review )? 'last' : '';
                            @endphp
                            <div class="review_data {{ $class }}">
                                <div class="top_panel">By {{ $review->username }} - {{ TimeController::get_time_ago( $review->creation_date ) }}</div>
                                <div class="bottom_panel {{ $class }}">{{ $review->review }}</div>
                            </div>
                        @endforeach
                        </div>
                    @else
                        {{ $reviews_num }} {{ __('reviews') }}
                    @endif
                @else
                    {{ $reviews_num }} {{ __('reviews') }}
                @endauth
            </span>
        </div>
        <div class="col-12 col-md-4 text-center">
            @auth
                @if( Auth::user()->username != $movie->posted_by )
                    <span class="like_btn">
                        @php
                            $class = ( $like_votes_num && Auth::check() && in_array( Auth::user()->username, $movie->users_by_like ) )? 'movie_voted': '';

                            $obj = array();
                            $obj['action'] = 'like';
                            $obj['userid'] = Auth::user()->id;
                            $obj['movieid'] = $movie->movieid;

                            $like = 'javascript:processVote( '.json_encode( $obj ).' );';
                        @endphp
                        <span class="{{ $class }}"><a href="{{ $like }}" class="vote">{{ __('Like') }}</a></span>
                    </span>                    
                    <span>|</span>                    
                    <span class="hate_btn">
                        @php
                            $class = ( $hate_votes_num && Auth::check() && in_array( Auth::user()->username, $movie->users_by_hate ) )? 'movie_voted': '';

                            $obj = array();
                            $obj['action'] = 'hate';
                            $obj['userid'] = Auth::user()->id;
                            $obj['movieid'] = $movie->movieid;

                            $hate = 'javascript:processVote( '.json_encode( $obj ).' );';
                        @endphp
                        <span class="{{ $class }}"><a href="{{ $hate }}" class="vote">{{ __('Hate') }}</a></span>
                    </span>
                    <span>|</span>                    
                    @php
                        $obj = array();
                        $obj['action'] = 'add';
                        $obj['userid'] = Auth::user()->id;
                        $obj['movieid'] = $movie->movieid;
                        $obj['title'] = 'Add Review';
                        $obj['html'] = PopupDialogController::getReviewDialogHtml(); 

                        $review = 'javascript:showReviewDialog( '.json_encode( $obj ).' );';
                    @endphp
                    <a href="{{ $review }}"><img src="{{ asset('images/review.png') }}" class="review_btn" title="Add Review"/></a>                    
                @endif
            @endauth
        </div>
        <div class="col-12 col-md-4 text-right">
            <div class="movie_posted_by">{{ __('Posted by') }} <span class="movie_posted_by_user">{{ $movie->posted_by }}</div>
        </div>
    </div>
    @auth
        @if( Auth::user()->username == $movie->posted_by )
            @php
                $title = htmlspecialchars( $movie->title, ENT_QUOTES, 'UTF-8' );
                $description = htmlspecialchars( $movie->description, ENT_QUOTES, 'UTF-8' );

                $obj = array();
                $obj['action'] = 'update';
                $obj['userid'] = Auth::user()->id;
                $obj['movieid'] = $movie->movieid;
                $obj['title'] = 'Edit Movie';
                $obj['html'] = PopupDialogController::getMovieDialogHtml( $title, $description );

                $onedit = 'javascript:showMovieDialog( '.json_encode( $obj ).' );';

                $obj = array();
                $obj['action'] = 'delete';
                $obj['userid'] = Auth::user()->id;
                $obj['movieid'] = $movie->movieid;
                $obj['html'] = PopupDialogController::getConfirmDialogHtml( "Movie '$title' will be deleted. Are you sure?" ); 
            
                $ondelete = 'javascript:confirmMovieDeletion( '.json_encode( $obj ).' );';
            @endphp
            <div class="row">
                <div class="col movie_data_actions text-right">
                    <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">{{ __('Actions') }}</button>
                        <div class="dropdown-menu">                        
                            <a class="dropdown-item" href="{{ $onedit }}">{{ __('Edit') }}</a>
                            <a class="dropdown-item" href="{{ $ondelete }}">{{ __('Delete') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>
