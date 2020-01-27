@extends('layouts.app')
@include('navbar')
@include('footer')

@section('content')
<div class="col-md-8 col-md-2 mx-auto">
 <div class="card-wrap">
  <div class="card">
    <div class="card-header align-items-center d-flex">
      <a class="no-text-decoration" href="{{ route('users', ['user_id' => $post->user->id]) }}">
        @if ($post->user->profile_photo)
          @if (app()->isLocal() || app()->runningUnitTests())
            <img class="post-profile-icon" src="{{ asset('storage/user_images/' . $post->user->profile_photo) }}"/>
          @else
            <img class="post-profile-icon" src="{{ $post->user->profile_photo }}"/>
          @endif
        @else
            <img class="post-profile-icon" src="{{ asset('/images/blank_profile.png') }}"/>
        @endif
      </a>
      <a class="black-color no-text-decoration omit-letter" title="{{ $post->user->user_name }}" href="{{ route('users', ['user_id' => $post->user->id]) }}">
        <strong>{{ $post->user->user_name }}</strong>
      </a>
      @if ($post->user->id == Auth::user()->id)
      	<a class="ml-auto mx-0 my-auto" rel="nofollow">
          <div class="delete-post-icon" data-toggle="modal" data-target="#deleteModal">
          </div>
      	</a>
      @endif
    </div>
    <a>
      @if (app()->isLocal() || app()->runningUnitTests())
        <img src="{{ asset('storage/post_images/' . $post->post_photo) }}" class="card-img-top" />
      @else
        <img src="{{ $post->post_photo }}" class="card-img-top" />
      @endif
    </a>
    
    <div class="card-body">
      <div class="row parts">
        <div id="like-icon-post-{{ $post->id }}">
          @if ($post->likedBy(Auth::user())->count() > 0)
            <a class="loved hide-text" data-remote="true" rel="nofollow" data-method="DELETE" href="{{ route('likes.delete', ['like_id' => $post->likedBy(Auth::user())->firstOrFail()->id]) }}">いいねを取り消す</a>
          @else
            <a class="love hide-text" data-remote="true" rel="nofollow" data-method="POST" href="{{ route('likes.posts', ['post_id' => $post->id]) }}">いいね</a>
          @endif
        </div>
        <a class="comment"></a>
      </div>
      <div id="like-text-post-{{ $post->id }}">
        @include('post.like_text')
      </div>
      <div>
        <span>
          <strong>
            <a class="no-text-decoration black-color" href="{{ route('users', ['user_id' => $post->user->id ]) }}">{{ $post->user->user_name }}</a>
          </strong>
        </span>
        <span>{{ $post->caption }}</span>
      </div>
      
      <div id="comment-post-{{ $post->id }}">
        @include('post.comment_list')
      </div>
      <a class="light-color post-time no-text-decoration">{{ $post->created_at }}</a>
      <hr>
      <div class="row actions" id="comment-form-post-{{ $post->id }}">
        <form class="w-100" action="/comments/posts/{{ $post->id }}" accept-charset="UTF-8" data-remote="true" method="post"><input name="utf8" type="hidden" value="✓" />
        {{csrf_field()}} 
          <input value="{{ Auth::user()->id }}" type="hidden" name="user_id" />
          <input value="{{ $post->id }}" type="hidden" name="post_id" />
          <input class="form-control comment-input border-0" placeholder="コメント ..." autocomplete="off" type="text" name="comment" />
        </form>
      </div>
    </div>
  </div>
 </div>
</div>

<!--投稿削除モーダル-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">投稿を削除しますか？</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="list-group text-center">
        <a class="list-group-item list-group-item-action delete-post-yes" href="{{ route('posts.delete', ['post_id' => $post->id]) }}">はい</a>
        <a class="list-group-item list-group-item-action" data-dismiss="modal" href="#">いいえ</a>
      </div>
    </div>
  </div>
</div>
@endsection