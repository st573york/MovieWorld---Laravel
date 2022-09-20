@php
    use App\Http\Controllers\PopupDialogController;
    use App\Http\Controllers\FooterController;

    $dialogs = PopupDialogController::$dialogs;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ env('APP_NAME') }}</title>

        <!-- Scripts -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/popup-dialog-widget.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/sort.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/movie.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/vote.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/review.js') }}"></script>

        <script>
            var dialogs = {!! json_encode( $dialogs ) !!};

            jQuery(function ($) {
                
                initPopupDialogs();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            });
        </script>

        <!-- Styles -->
        <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/popup-dialog.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/movie.css') }}" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div id="loader"></div>
        @if( !empty( $dialogs ) )
            @foreach( $dialogs as $dialog ) 
                <div id="popup-dialog-{{ $dialog }}" style="display: none;"></div>
            @endforeach
        @endif
        <!-- Left panel -->
        <div class="fixed_left_panel"></div>
        <!-- Right panel -->
        <div class="fixed_right_panel"></div>
        <!-- Top panel -->
        <div class="fixed_top_panel">
            <img src="{{ asset('images/movies-icon.jpeg') }}"/>
            <div class="title_panel">{{ env('APP_NAME') }}</div>
            <div class="searchtext_panel"><input type="text" class="form-control" id="searchtext" name="searchtext" autocomplete="off" placeholder="Search..."/></div>
            @auth
                @php
                    $obj = array();
                    $obj['action'] = 'delete';
                    $obj['userid'] = Auth::user()->id;
                    $obj['html'] = PopupDialogController::getConfirmDialogHtml( "User will be deleted. Are you sure?" ); 

                    $ondelete = 'javascript:confirmUserDeletion( '.json_encode( $obj ).' );';
                @endphp
                <div class="user_panel">{{ __('Welcome Back') }}
                    <div class="loggedin_user dropright">
                        <a href="" class="dropdown-link with-caret" data-toggle="dropdown">{{ Auth::user()->username }}<span class="caret-right"></span></a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('profile.show', [ Auth::user()->id ]) }}">{{ __('Profile') }}</a>
                            <a class="dropdown-item red" href="{{ $ondelete }}">{{ __('Delete') }}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById( 'logout-form' ).submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="user_actions_panel">
                    <button class="btn btn-link" type="button" onclick="location.href='/login'">{{ __('Log in') }}</button>
                    <button class="btn btn-primary btn-sm" type="button" onclick="location.href='/register'">{{ __('Sign up') }}</button>
                </div>
            @endauth
            <div class="movie_actions_panel">
                @auth
                    @php
                        $obj = array();
                        $obj['action'] = 'store';
                        $obj['userid'] = Auth::user()->id;
                        $obj['title'] = 'New Movie';
                        $obj['html'] = PopupDialogController::getMovieDialogHtml();

                        $onadd = 'this.blur(); showMovieDialog( '.json_encode( $obj ).' );';
                    @endphp
                    <button class="btn btn-success btn-sm" type="button" onclick="{{ $onadd }}">{{ __('New Movie') }}</button>
                @endauth
                <div class="btn-group">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">{{ __('Options') }}</button>
                    <div class="dropdown-menu">
                        <h6 class="dropdown-header">Sort by</h6>
                        <a id="sort_by_title" class="dropdown-item" href="javascript:sortMovies( { 'action': 'sort_by_title' } )">{{ __('Title') }}</a>
                        <a id="sort_by_likes" class="dropdown-item" href="javascript:sortMovies( { 'action': 'sort_by_likes' } )">{{ __('Likes') }}</a>
                        <a id="sort_by_hates" class="dropdown-item" href="javascript:sortMovies( { 'action': 'sort_by_hates' } )">{{ __('Hates') }}</a>
                        <a id="sort_by_reviews" class="dropdown-item" href="javascript:sortMovies( { 'action': 'sort_by_reviews' } )">{{ __('Reviews') }}</a>
                        <a id="sort_by_author" class="dropdown-item" href="javascript:sortMovies( { 'action': 'sort_by_author' } )">{{ __('Author') }}</a>
                        <a id="sort_by_date_most_recent" class="dropdown-item" href="javascript:sortMovies( { 'action': 'sort_by_date_most_recent' } )">{{ __('Date: Most recent') }}</a>
                        <a id="sort_by_date_oldest" class="dropdown-item" href="javascript:sortMovies( { 'action': 'sort_by_date_oldest' } )">{{ __('Date: Oldest') }}</a>
                    </div>
                </div>
            </div>
            <div class="fade-top"></div>
        </div>
        <!-- Movie Container -->
        <div class="movie_container">
            <!-- Found Movies -->
            <div class="found_movies">Found <span class="found_movies_count">{{ count( $movies ) }}</span> {{ __('movies') }}</div>
            @if( count( $movies ) )
                <!-- Movie Content -->
                <div class="movie_content">
                    @foreach( $movies as $movie )
                        @includeIf('movies.movie')
                    @endforeach
                </div>
            @endif
        </div>
        <div class="fixed_bottom_panel">
            <div class="fade-bottom"></div>
            <div class="footer">&copy; {{ FooterController::show() }}</div>
        </div>
    </body>
</html>
