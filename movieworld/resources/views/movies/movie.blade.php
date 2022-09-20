@php
    use App\Http\Controllers\NumberController;
    use App\Http\Controllers\TimeController;
    use App\Http\Controllers\PopupDialogController;
@endphp

<!-- Movie Data -->
<div id="movie_{{ $movie->movieid }}" class="movie_data">
    <div class="movie_data_top">
        <span class="movie_title">{{ $movie->title }}</span>
        <span class="movie_posted">{{ __('Posted') }} {{ $movie->posted }}</span>
    </div>
    <div class="movie_data_middle">
        <span>{{ $movie->description }}</span>
    </div>
    <div class="movie_data_bottom">
        <div class="movie_num_container">
            <div id="movie_votes_num_{{ $movie->movieid }}" class="movie_votes_num">
                @php
                    $like_votes_num = NumberController::short_number( count( $movie->users_by_like ) );
                    $class = ( $like_votes_num && Auth::check() && in_array( Auth::user()->username, $movie->users_by_like ) )? 'liked': '';
                @endphp
                <div class="movie_likes {{ $class }}">
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
                </div>
                <div class="num_separator">|</div>
                @php
                    $hate_votes_num = NumberController::short_number( count( $movie->users_by_hate ) );
                    $class = ( $hate_votes_num && Auth::check() && in_array( Auth::user()->username, $movie->users_by_hate ) )? 'hated': '';
                @endphp
                <div class="movie_hates {{ $class }}">
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
                </div>
                <div class="num_separator">|</div>
                <div class="movie_reviews">
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
                </div>
            </div>
        </div>
        @auth
            @if( Auth::user()->username != $movie->posted_by )
                <div class="movie_btn_container">
                    <div id="movie_votes_btn_{{ $movie->movieid }}" class="movie_votes_btn">
                        <span id="like_btn_{{ $movie->movieid }}" class="like_btn">
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
                        <span class="btn_separator">|</span>
                        <span id="hate_btn_{{ $movie->movieid }}" class="hate_btn">
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
                        <span class="btn_separator">|</span>
                        @php
                            $obj = array();
                            $obj['action'] = 'add';
                            $obj['userid'] = Auth::user()->id;
                            $obj['movieid'] = $movie->movieid;
                            $obj['title'] = 'Add Review';
                            $obj['html'] = PopupDialogController::getReviewDialogHtml(); 

                            $review = 'javascript:showReviewDialog( '.json_encode( $obj ).' );';
                        @endphp
                        <span id="review_btn_{{ $movie->movieid }}" class="review_btn" onclick="{{ $review }}" title="Add Review"></span>
                    </div>
                </div>
            @endif
        @endauth
        <div class="movie_posted_by">{{ __('Posted by') }} <span class="movie_posted_by_user">{{ $movie->posted_by }}</div>
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
            <div class="movie_data_actions">
                <div class="btn-group">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">{{ __('Options') }}</button>
                    <div class="dropdown-menu">                        
                        <a class="dropdown-item" href="{{ $onedit }}">{{ __('Edit') }}</a>
                        <a class="dropdown-item" href="{{ $ondelete }}">{{ __('Delete') }}</a>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>
       
