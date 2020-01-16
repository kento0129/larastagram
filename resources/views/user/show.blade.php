@extends('layouts.app')
@include('navbar')
@include('footer')

@section('content')
<div class="profile-wrap">
  <div class="row">
    <div class="col-md-4 text-center">
      @if ($user->profile_photo)
        <p>
          @if (app()->isLocal() || app()->runningUnitTests())
            <img class="round-img" src="{{ asset('storage/user_images/' . $user->profile_photo ) }}"/>
          @else
            <img class="round-img" src="{{ $user->profile_photo }}"/>
          @endif
        </p>
      @else
          <img class="round-img" src="{{ asset('/images/blank_profile.png') }}"/>
      @endif
    </div>
    <div class="col-md-8">
      <div class="row">
        <h1 class="omit-letter user-name-font">{{ $user->user_name }}&nbsp;</h1>
          @if ($user->id == Auth::user()->id)
            <div class="d-flex">
              <a class="btn btn-outline-dark common-btn edit-profile-btn" href="/users/edit">プロフィールを編集</a>
              <button type="button" class="setting" data-toggle="modal" data-target="#exampleModal"></button>
            </div>
          @endif
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog setting-modal-margin" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">設定</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="list-group text-center">
                <a class="list-group-item list-group-item-action" rel="nofollow" data-method="POST" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                <a class="list-group-item list-group-item-action" href='/users/password'>パスワードを変更</a>
                <a class="list-group-item list-group-item-action" data-dismiss="modal" href="#">キャンセル</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row omit-letter">
        <p class="name-font">
          {{ $user->name }}
        </p>
      </div>
    </div>
  </div>
</div>
@endsection