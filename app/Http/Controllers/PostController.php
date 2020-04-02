<?php

namespace App\Http\Controllers;

use \App\BlogPost;
use Illuminate\Http\Request;
use \App\Http\Requests\StorePost;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        // DB::connection()->enableQueryLog();

        // $posts = BlogPost::with('comments')->get();

        // foreach ($posts as $post) {
        //     foreach ($post->comments as $comment) {
        //         echo $comment->content;
        //         echo '<br>';
        //     }
        // }

        // dd(DB::getQueryLog());

        // comments_count
        return view(
            'posts.index',
            ['posts' => BlogPost::withCount('comments')->get()]);
    }


    public function show($id)
    {
        return view('posts.show', [
            'post' => BlogPost::with('comments')->findOrFail($id)
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validatedData = $request->validated();

        // dd($validatedData);

        // $post = new BlogPost();
        $post = BlogPost::create($validatedData);
        // $post->title = $request->input('title', '');
        // $post->content = $request->input('content', '');
        // $post->save();

        $request->session()->flash('status', 'Blog post was created!');

        // return redirect()->route('posts.index');
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);

        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize('update-post', $post);

        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize('update-post', $post);

        $validatedData = $request->validated();

        $post->fill($validatedData);
        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        // if (Gate::denies('delete-post', $post)) {
        //     abort(403, "You can't delete this blog post!");
        // }
        $this->authorize('delete-post', $post);

        // BlogPost::destroy($id);
        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }


}
