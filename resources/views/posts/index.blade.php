@extends('layouts.app')

@section('title')
    List of Blog Posts
@endsection

@section('content')
    @if (session()->has('status'))
        <p style="color:green">
            {{ session()->get('status') }}
        </p>
    @endif
    @forelse ($posts as $post)
            <h3>
                <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
            </h3>
            <p>
                {{ $post->content }}
            </p>
            <p class="text-muted">
                Added {{ $post->created_at->diffForHumans() }},
                by {{ $post->user->name }}
            </p>
            @if ($post->comments_count)
                <p>{{ $post->comments_count }} comments</p>
            @else
                <p>No Comments yet!</p>
            @endif
            @guest
                @else
                    <div class="row">
                        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">
                            Edit
                        </a>
                        <form action="{{ route('posts.destroy', ['post' => $post->id]) }}"
                            method="post"
                            class="">
                            @csrf
                            @method('DELETE')

                            <input type="submit" value='Delete!' class="btn btn-default"/>
                        </form>
                    </div>
            @endguest
                    <br>
        @empty
        <p>No blog posts yet!</p>
    @endforelse

@endsection('content')

