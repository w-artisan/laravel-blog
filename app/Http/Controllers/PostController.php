<?php

namespace App\Http\Controllers;

use \App\BlogPost;
use Illuminate\Http\Request;
use \App\Http\Requests\StorePost;
// use Illuminate\Support\Facades\DB;

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

        // $blogPost = new BlogPost();
        $blogPost = BlogPost::create($validatedData);
        // $blogPost->title = $request->input('title', '');
        // $blogPost->content = $request->input('content', '');
        // $blogPost->save();

        $request->session()->flash('status', 'Blog post was created!');

        // return redirect()->route('posts.index');
        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);

        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $blogPost = BlogPost::findOrFail($id);
        $validatedData = $request->validated();

        $blogPost->fill($validatedData);
        $blogPost->save();
        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    public function destroy(Request $request, $id)
    {
        $blogPost = BlogPost::findOrFail($id);
        $blogPost->delete();

        // BlogPost::destroy($id);
        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }


}
