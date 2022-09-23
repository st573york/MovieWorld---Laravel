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
        <!-- Fixed Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">            
            <div class="container">               
                <a href="/"><img src="{{ asset('images/movies-icon.jpeg') }}" class="logo"/></a>
                <a class="navbar-brand" href="/">{{ env('APP_NAME') }}</a>
                 <!-- Navbar Toggler -->
                 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse" id="navbarContent">
                    <!-- Navbar Search -->
                    <form class="mr-auto pt-2 pt-lg-0">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                    </form>                
                    @auth
                        @php
                            $obj = array();
                            $obj['action'] = 'delete';
                            $obj['userid'] = Auth::user()->id;
                            $obj['html'] = PopupDialogController::getConfirmDialogHtml( "User will be deleted. Are you sure?" ); 

                            $ondelete = 'javascript:confirmUserDeletion( '.json_encode( $obj ).' );';
                        @endphp
                        <span class="navbar-text pt-2 pb-0 pb-lg-2">
                            {{ __('Welcome Back') }}
                        </span>                    
                        <!-- Navbar User -->
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle pb-0 pb-lg-2" href="#" data-toggle="dropdown">
                                    {{ Auth::user()->username }}
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('profile.show', [ Auth::user()->id ]) }}">{{ __('Profile') }}</a>
                                    <a class="dropdown-item red" href="{{ $ondelete }}">{{ __('Delete') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById( 'logout-form' ).submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    @else
                        <!-- Navbar User Actions -->
                        <div class="pt-2 pt-lg-0">
                            <button class="btn btn-link" type="button" onclick="location.href='/login'">{{ __('Log in') }}</button>
                            <button class="btn btn-light btn-sm" type="button" onclick="location.href='/register'">{{ __('Sign up') }}</button>
                        </div>
                    @endauth
                </div>
            </div>    
        </nav>            
        <!-- Movie Container -->
        <div class="container movie_container">
            <div class="row">
                <!-- Found Movies -->
                <div class="col-6 found_movies">
                    Found <span class="found_movies_count">{{ count( $movies ) }}</span> {{ __('movies') }}
                </div>
                <div class="col-6 add_sort_movies text-right">
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
                    <!-- Sort Movies -->
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
            </div>
            @if( count( $movies ) )
                <!-- Movie Content -->
                <div class="movie_content">
                    @foreach( $movies as $movie )
                        @includeIf('movies.movie')
                    @endforeach
                </div>
            @endif
        </div>
        <nav class="navbar fixed-bottom navbar-dark bg-dark">
            <div class="container justify-content-md-center">
                <span class="navbar-text">
                    &copy; {{ FooterController::show() }}
                </span>
            </div>
        </nav>
    </body>
</html>