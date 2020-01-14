@extends('layouts.app')
@include('navbar')
@include('footer')

@section('content')
@include('common.errors')
<div class="col-md-offset-2 mb-4 mt-3">
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="profile-form-wrap">
        <form class="change_password" enctype="multipart/form-data" action="/users/password/change" accept-charset="UTF-8" method="post">
          <input name="utf8" type="hidden" value="✓" />
          <input type="hidden" name="id" value="{{ $user->id }}" />
          {{csrf_field()}}
          
          <div class="form-group">
            <label for="old_password">現在のパスワード</label>
            <input autofocus="autofocus" class="form-control" type="password" name="old_password" />
          </div>
          
          <div class="form-group">
            <label for="new_password">新しいパスワード</label>
            <input autofocus="autofocus" class="form-control" type="password" name="new_password" />
          </div>

          <div class="form-group">
            <label for="new_password_confirmation">新しいパスワードの確認</label>
            <input autofocus="autofocus" class="form-control" type="password" name="new_password_confirmation" />
          </div>

          <input type="submit" name="commit" value="変更する" class="btn btn-primary" data-disable-with="変更する" />
        </div>
      </form>
    </div>
  </div>
</div>
@endsection