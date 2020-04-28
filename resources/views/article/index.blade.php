@extends("layouts.app")

@section("content") 
    <!--br-->
    @auth
        <a class="btn btn-primary" href="/article/create">New Article</a>
        <br><br>
    @endauth
    @if (count($articles) > 0)
        @foreach ($articles as $article) 
        <div class="card">
            <div class="card-header">
                {{ $article->title }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm|md|lg|xl-4 m-3">
                        <img style="width:100%" src="/storage/images/{{ $article->image }}">
                    </div>
                    <div class="col-sm|md|lg|xl-8 m-3">
                        <h5 class="card-title">{{ $article->excerpt }}</h5>
                        <p class="card-text">Written on {{ $article->created_at }}</p>
                        <a href="{{ $article->path() }}" class="btn btn-secondary">See Full Article</a>
                    </div>                    
                </div>
            </div>
        </div>
        <br>
        @endforeach
        <div class="d-flex justify-content-center">
            {{ $articles->links() }}
        </div>
    @else 
        <p>No posts yet!</p>
    @endif
@endsection