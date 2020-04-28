@extends("layouts.app")

@section("content")  
    <h1 class="display-4">Create Article</h1>
    <form method="POST" action="/article" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" 
                    class="form-control" 
                    id="title" 
                    name="title"
                    value="{{ old('title') }}">
        </div>
        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea class="form-control" 
                        id="excerpt" 
                        name="excerpt">
                {{ old('excerpt') }}
            </textarea>
        </div>    
        <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" 
                        id="body" 
                        name="body">
                {{ old('body') }}
            </textarea>
        </div>  
        <div class="form-group">
            <label for="tags">Tags</label>
            <select class="form-control" id="tags" name="tags[]" multiple>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div> 
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" id="image" name="image">
            <label class="custom-file-label" for="image">Choose file</label>
        </div>               
        <button type="submit" class="btn btn-primary">Save</button> 
        <a class="btn btn-secondary" href="/article">Cancel</a>        
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