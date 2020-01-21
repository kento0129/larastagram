@section('navbar')
<nav class="navbar navbar-expand navbar-light">
  <div class="container">
    <a class="navbar__brand navbar__mainLogo" href="/"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="ナビゲーションの切替">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-md-auto align-items-center">
        <li>
          <a class="nav-link commonNavIcon far fa-plus-square fa-lg" href="{{ route('posts.new') }}"></a>
        </li>
        <li>
          <a class="nav-link commonNavIcon far fa-user fa-lg" href="{{ route('users', ['user_id' => Auth::user()->id]) }}"></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
@endsection