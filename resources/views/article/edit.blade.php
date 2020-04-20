@extends("layouts.app")

@section("content") 
    <h1 class="display-4">Update Article</h1>
    <form method="POST" action="{{ $article->path() }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Title</label>
            <input 
                type="text" 
                class="form-control" 
                id="title" 
                name="title" 
                value="{{ $article->title }}">
        </div>
        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea class="form-control" 
                        id="excerpt" 
                        name="excerpt">
                {{ $article->excerpt }}
            </textarea>
        </div>    
        <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" 
                        id="body" 
                        name="body">
                {{ $article->body }}
            </textarea>
        </div>
        <div class="form-group">
            <label for="tags">Tags</label>
            <select class="form-control" id="tags" name="tags[]" multiple>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}"
                        @if(in_array($tag->id, $article_tags))
                            selected
                        @endif
                    >
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>                 
        <button type="submit" class="btn btn-primary">Save</button> 
        <a class="btn btn-secondary" href="{{ $article->path() }}">Cancel</a>            
    </form>
@endsection