<ul class="information-ul">
  <li>
    <span class="information-tag1">投稿<span class="information-tag2">{{ count($user->posts) }}</span>件</span>
  </li>
  <li>
    <div class="information-tag1" @if(count($user->followers) !== 0) data-toggle="modal" data-target="#modalFollowersList" @endif>フォロワー<span class="information-tag2">{{ count($user->followers) }}</span>人</span></div>
  </li>
  <li>
    <div class="information-tag1" @if(count($user->follows) !== 0) data-toggle="modal" data-target="#modalFollowsList" @endif><span class="information-tag2">{{ count($user->follows) }}</span>人をフォロー中</span></div>
  </li>
</ul>

<!--フォロワーモーダル-->
<div class="modal fade" id="modalFollowersList" tabindex="-1" role="dialog" aria-labelledby="modalFollowersListTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable followers-modal-margin" role="document">
    <div class="modal-content followers-modal-content">
      <div class="modal-header">
        <div class="margin-0-auto">
          <h1 class="modal-title followers-modal-title" id="modalFollowersListTitle">フォロワー</h5>
        </div>
        <div>
          <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span aria-hidden="true">&times;</span>
        </button>        
        </div>
      </div>
      <div class="modal-body followers-modal-body">
        <ul class="followers-ul">
          @foreach($user->followers as $follower)
            <div>
              <li class="followers-li d-flex" value="{{ $follower->id }}">
                <div class="d-flex align-items-center">
                  <a class="no-text-decoration" href="{{ route('users', ['user_id' => $follower->id]) }}">
                    @if ($follower->profile_photo)
                      @if (app()->isLocal() || app()->runningUnitTests())
                        <img class="followers-img" src="{{ asset('storage/user_images/' . $follower->profile_photo ) }}"/>
                      @else
                        <img class="followers-img" src="{{ $follower->profile_photo }}"/>
                      @endif
                    @else
                        <img class="followers-img" src="{{ asset('/images/blank_profile.png') }}"/>
                    @endif
                  </a>
                </div>
                <div class="margin-left-10">
                  <div class="d-flex">
                    <div style="list-style: none;">
                      <a class="followers-user-name no-text-decoration" href="{{ route('users', ['user_id' => $follower->id]) }}">{{ $follower->user_name}}</a>
                    </div>
                  </div>
                  <div>
                    <a class="followers-name no-text-decoration" href="{{ route('users', ['user_id' => $follower->id]) }}">{{ $follower->name }}</a>
                  </div>
                </div>
                <div class="followers-status">
                  @if(Auth::user()->follows->isEmpty())
                    @if($follower->id !== Auth::user()->id)
                      <button class="btn btn-primary common-btn follow-btn follow-ajax" value="{{ $follower->id }}">フォローする</button>
                    @else
                    @endif
                  @else
                    @foreach(Auth::user()->follows as $follow)
                      @if ($follower->id === $follow->pivot->followed_id)
                        <button class="btn btn-outline-dark common-btn follow-now-btn follow-now-ajax" value="{{ $follower->id }}">フォロー中</button>
                        @break
                      @elseif ($follower->id === Auth::user()->id)
                        @break
                      @elseif ($loop->last)
                        <button class="btn btn-primary common-btn follow-btn follow-ajax" value="{{ $follower->id }}">フォローする</button>
                        @break
                      @endif
                    @endforeach
                  @endif
                </div>
              </li>
            </div>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>

<!--フォロー中モーダル-->
<div class="modal fade" id="modalFollowsList" tabindex="-1" role="dialog" aria-labelledby="modalFollowsListTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable followers-modal-margin" role="document">
    <div class="modal-content followers-modal-content">
      <div class="modal-header">
        <div class="margin-0-auto">
          <h1 class="modal-title followers-modal-title" id="modalFollowsListTitle">フォロー中</h5>
        </div>
        <div>
          <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span aria-hidden="true">&times;</span>
        </button>        
        </div>
      </div>
      <div class="modal-body followers-modal-body">
        <ul class="followers-ul">
          @foreach($user->follows as $follow)
            <div>
              <li class="followers-li d-flex" value="{{ $follow->id }}">
                <div class="d-flex align-items-center">
                  <a class="no-text-decoration" href="{{ route('users', ['user_id' => $follow->id]) }}>
                    @if ($follow->profile_photo)
                      @if (app()->isLocal() || app()->runningUnitTests())
                        <img class="followers-img" src="{{ asset('storage/user_images/' . $follow->profile_photo ) }}"/>
                      @else
                        <img class="followers-img" src="{{ $follow->profile_photo }}"/>
                      @endif
                    @else
                        <img class="followers-img" src="{{ asset('/images/blank_profile.png') }}"/>
                    @endif
                  </a>
                </div>
                <div class="margin-left-10">
                  <div class="d-flex">
                    <div>
                      <a class="followers-user-name no-text-decoration" href="{{ route('users', ['user_id' => $follow->id]) }}">{{ $follow->user_name}}</a>
                    </div>
                  </div>
                  <div>
                    <a class="followers-name no-text-decoration" href="{{ route('users', ['user_id' => $follow->id]) }}">{{ $follow->name }}</a>
                  </div>
                </div>
                <div class="followers-status">
                  @if(Auth::user()->follows->isEmpty())
                    @if($follow->id !== Auth::user()->id)
                      <button class="btn btn-primary common-btn follow-btn follow-ajax" value="{{ $follow->id }}">フォローする</button>
                    @else
                    @endif
                  @else
                    @foreach(Auth::user()->follows as $auth_follow)
                      @if ($follow->id === $auth_follow->pivot->followed_id)
                        <button class="btn btn-outline-dark common-btn follow-now-btn follow-now-ajax" value="{{ $follow->id }}">フォロー中</button>
                        @break
                      @elseif ($follow->id === Auth::user()->id)
                        @break
                      @elseif ($loop->last)
                        <button class="btn btn-primary common-btn follow-btn follow-ajax" value="{{ $follow->id }}">フォローする</button>
                        @break
                      @endif
                    @endforeach
                  @endif
                </div>
              </li>
            </div>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>