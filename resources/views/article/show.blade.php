@extends("layouts.app")

@section("content") 
    <a class="btn btn-info" href="/article">Go Back</a>
    @auth
        <a class="btn btn-primary" href="{{ $article->path() }}/edit">Update Article</a>
        <!-- TODO: improve confirm -->
        <input type="submit" 
                class="btn btn-danger" 
                value="Delete" 
                onclick="event.preventDefault();
                            confirm('Confirm to delete article.');
                            document.getElementById('delete-form').submit();"
        >
        <br>
        <form id="delete-form" action="{{ route('article.destroy', $article) }}" 
            method="POST" style="display: none;"
        >
            @csrf
            @method('DELETE')
        </form>
    @endauth
    <br><br>
    <h1>{{ $article->title }}</h1>
    @if($article->image !== "noimage.jpeg")
        <img style="width:80%" class="pt-3 pb-3" src="/storage/images/{{ $article->image }}">
    @endif
    <p>{{ $article->excerpt }}</p>
    <p>{{ $article->body }}</p>
    <p>
        @foreach($article->tags as $tag)
            <a href="{{ route('article.index', ['tag'=>$tag->id]) }}">{{ $tag->name }}</a>
        @endforeach
    </p>
@endsection