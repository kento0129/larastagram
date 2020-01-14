@extends('layouts.app')
@include('navbar')
@include('footer')

@section('content')
<!-- バリデーションエラーの場合に表示 --> 
@include('common.errors')

<div class="d-flex flex-column align-items-center mt-3">
  <div class="col-xl-7 col-lg-8 col-md-10 col-sm-11 post-card">
    <div class="card">
      <div class="card-header">
        投稿画面
      </div>
      <div class="card-body">
        <form class="upload-images p-0 border-0" id="new_post" enctype="multipart/form-data" action="{{ url('posts')}}" accept-charset="UTF-8" method="POST">
        {{csrf_field()}} 
          <div class="form-group row mt-2">
            <div class="col-auto pr-0">
              @if (Auth::user()->profile_photo)
                @if (app()->isLocal() || app()->runningUnitTests())
                  <img class="post-profile-icon round-img" src="{{ asset('storage/user_images/' . Auth::user()->profile_photo ) }}"/>
                @else
                  <img class="round-img" src="{{ Auth::user()->profile_photo }}"/>
                @endif
              @else
                <img class="post-profile-icon round-img" src="{{ asset('/images/blank_profile.png') }}"/>
              @endif
            </div>
            <div class="col pl-0">
              <input class="form-control border-0" placeholder="キャプションを書く" type="text" name="caption" value="{{ old('list_name') }}"/>
            </div>
          </div>
          <div class="mb-3">
            <input type="file" name="post_photo" accept="image/jpeg,image/gif,image/png,image/jpg" />
          </div>
          <input type="submit" name="commit" value="投稿する" class="btn btn-primary" data-disable-with="投稿する" />
        </form>      
      </div>
    </div>
  </div>
</div>
@endsection