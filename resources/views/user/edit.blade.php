@extends('layouts.app')
@include('navbar')
@include('footer')

@section('content')
@include('common.errors')
<div class="col-md-offset-2 mb-4 mt-3">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="profile-form-wrap">
        <form class="edit_user" enctype="multipart/form-data" action="/users/update" accept-charset="UTF-8" method="post">
          <input name="utf8" type="hidden" value="✓" />
          <input type="hidden" name="id" value="{{ $user->id }}" />
          {{csrf_field()}} 
          <div class="form-group">
            <label for="profile_photo">プロフィール写真</label><br>
              @if ($user->profile_photo)
                <p>
                  @if (app()->isLocal() || app()->runningUnitTests())
                    <img class="round-img" src="{{ asset('storage/user_images/' . $user->profile_photo ) }}" alt="avatar" />
                  @else
                    <img class='round-img' src="{{ $user->profile_photo }}" alt="avatar" />
                  @endif
                </p>
              @endif
            <input type="file" name="profile_photo"  value="{{ old('user_profile_photo',$user->id) }}" accept="image/jpeg,image/gif,image/png,image/jpg" />
          </div>

          <div class="form-group">
            <label for="name">名前</label>
            <input autofocus="autofocus" class="form-control" type="text" value="{{ old('name',$user->name) }}" name="name" />
          </div>

          <div class="form-group">
            <label for="user_name">ユーザーネーム</label>
            <input autofocus="autofocus" class="form-control" type="text" value="{{ old('user_name',$user->user_name) }}" name="user_name" />
          </div>
          
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input autofocus="autofocus" class="form-control" type="email" value="{{ old('email',$user->email) }}" name="email" />
          </div>

          <input type="submit" name="commit" value="変更する" class="btn btn-primary" data-disable-with="変更する" />
        </div>
      </form>
    </div>
  </div>
</div>
@endsection