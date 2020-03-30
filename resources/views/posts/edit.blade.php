@extends('layouts.app')

@section('title')
    Edit Blog Post
@endsection

@section('content')
    <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="post">
        @csrf
        @method('PUT')

        @include('posts._form')

        <button type="submit" class="btn btn-primary btn-block">Update!</button>
    </form>
@endsection
