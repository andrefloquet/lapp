<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \App\Article;
use \App\Tag;
use \App\Auth;

class ArticleController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create','edit']);
    }

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pagination: quantity per page
        $pag = 6;
        // Improve code below
        if(request('tag')){
            if(auth()->check()) {
                $articles = Tag::find(request('tag'))->articles()->where('user_id', auth()->user()->id)->paginate($pag);
            } else {
                $articles = Tag::find(request('tag'))->articles()->paginate($pag);
            }
        } else {
            if(auth()->check()) {
                $articles = auth()->user()->articles()->latest()->paginate($pag);
            } else {
                $articles = Article::latest()->paginate($pag);
            }
        }
       
        return view('article.index', [
            'articles' =>  $articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        //$this->middleware('auth');

        return view('article.create', [ 
            "tags" => Tag::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validateArticle();

        if ($request->hasFile('image')) {
            // Avoid file names duplicated
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            // Just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Just file name
            $extension = $request->file('image')->extension();
            // Name to Store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            // Store on disk
            $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
        } else {
            $fileNameToStore = "noimage.jpeg";
        }

        $article = new Article(request(["title","excerpt","body"]));        
        $article->user_id = auth()->user()->id;
        $article->image = $fileNameToStore;
        $article->save();

        $article->tags()->attach(request('tags'));

        return redirect(route('article.index'))->with('success', 'Article Inserted');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //dd($article);
        return view('article.show', compact('article'));       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //return view('article.edit', compact('article'));
        return view('article.edit', [
            'article' => $article,
            'tags' => Tag::all(),
            'article_tags' => $article->tags->pluck('id')->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //$article->update($this->validateArticle());

        $this->validateArticle();
        $article->update(request(["title","excerpt","body"])); 

        if ($request->hasFile('image')) {
            // Avoid file names duplicated
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            // Just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Just file name
            $extension = $request->file('image')->extension();
            // Name to Store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            // Delete on disk the old image
            if($article->image != "noimage.jpeg"){
                Storage::delete('public/images/'.$article->image);  
            }
            // Store on disk the new one
            $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
            // Update with new image name
            $article->image = $fileNameToStore;
        } 

        // Persist update
        $article->save();

        // (Delete or detach) Tags
        $article->tags()->detach();
        // Attach new tags
        $article->tags()->attach(request('tags'));

        return redirect($article->path())->with('success', 'Article Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if($article->image != "noimage.jpeg") {
            // Delete image on disk
            Storage::delete('public/images/'.$article->image);
        }
        
        $article->delete();
        return redirect(route('article.index'))->with('success', 'Article Deleted');
    }

    protected function validateArticle()
    {
        return request()->validate([
            'title' => 'required|max:255',
            'excerpt' => 'required',
            'body' => 'required',
            'tags' => 'exists:tags,id',
            'image' => 'image|nullable|max:1999'
        ]);
    }    
}
