@extends("layouts.app")

@section("content") 
    <h1 class="display-4">Update Article</h1>
    <form method="POST" action="{{ $article->path() }}" enctype="multipart/form-data">
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
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" id="image" name="image">
        <label class="custom-file-label" for="image">{{ $article->image }}</label>
        </div>                
        <button type="submit" class="btn btn-primary">Save</button> 
        <a class="btn btn-secondary" href="{{ $article->path() }}">Cancel</a>            
    </form>
    <!-- Change the file name when choose file -->
    <script>
        $(document).ready(function(){
            $('.custom-file-input').on('change',function(){
                let fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName);
                //alert(fileName);
            })
        });
    </script>    
@endsection