@extends('layouts.app')
@include('footer')
@section('content')
<div class="main">
  <div class="card devise-card">
    <div class="form-wrap">
      <div class="form-group text-center">
        <h2 class="logo-img mx-auto"></h2>
        <p class="text-secondary">友達の写真や動画をチェックしよう</p>
      </div>
      <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}
        <div class="form-group">
          <input class="form-control @if(!empty($errors->has('email'))) is-invalid @endif" placeholder="メールアドレス" autocomplete="email" type="email" name="email" value="{{ old('email') }}" required>
          @if ($errors->has('email'))
            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
          @endif
        </div>
        
        <div class="form-group is-invalid">
          <input class="form-control @if(!empty($errors->has('name'))) is-invalid @endif" placeholder="名前" type="text" name="name" value="{{ old('name') }}" required autofocus>
          @if ($errors->has('name'))
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
          @endif
        </div>
        
        <div class="form-group is-invalid">
          <input class="form-control @if(!empty($errors->has('user_name'))) is-invalid @endif" placeholder="ユーザーネーム(半角英数字-_のみ)" type="text" name="user_name" value="{{ old('user_name') }}" required autofocus>
          @if ($errors->has('user_name'))
            <div class="invalid-feedback">{{ $errors->first('user_name') }}</div>
          @endif
        </div>

        <div class="form-group is-invalid">
          <input class="form-control @if(!empty($errors->has('password'))) is-invalid @endif" placeholder="パスワード" autocomplete="off" type="password" name="password" required>
          @if ($errors->has('password'))
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
          @endif
        </div>

        <div class="form-group is-invalid">
          <input class="form-control @if(!empty($errors->has('password'))) is-invalid @endif" placeholder="パスワードの確認" autocomplete="off" type="password" name="password_confirmation" required>
        </div>

        <div class="form-group actions">
          <input type="submit" name="commit" value="登録する" class="btn btn-primary w-100">
        </div>
      </form>
      <br>

      <p class="devise-link">
        アカウントをお持ちですか？
        <a href="/">サインインする</a>
      </p>
    </div>
  </div>
</div>
@endsection