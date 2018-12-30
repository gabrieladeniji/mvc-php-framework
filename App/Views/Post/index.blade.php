@extends('layouts.app')


@section('title')
    <title> Post </title>
@endsection


@section('body')

    <div class="card mt-4">
        <div class="card-header">
            All Articles Post
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach( $posts as $post )
                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{$post->title}}</h5>
                            <small>3 days ago</small>
                        </div>
                        <p class="mb-1">{{$post->content}}</small> </p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection