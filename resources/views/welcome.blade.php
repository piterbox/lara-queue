@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="content">
            <div class="title m-b-md">
                Laravel
            </div>

            <div class="links">
                <a href="{{route('all_files')}}">All files</a>
                <a href="https://laravel.com/docs">Documentation</a>
                <a href="https://laracasts.com">Laracasts</a>
                <a href="https://laravel-news.com">News</a>
                <a href="https://nova.laravel.com">Nova</a>
                <a href="https://forge.laravel.com">Forge</a>
                <a href="https://github.com/laravel/laravel">GitHub</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if(Session::has('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{ route('file_upload') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" name="members_data" id="" >
                    <button type="submit" >Upload</button>
                </form>
            </div>
        </div>

    </div>
@endsection
