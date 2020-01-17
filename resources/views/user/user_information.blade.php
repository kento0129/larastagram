<ul class="information-ul">
  <li>
    <span>投稿<span class="information-span">{{ count($user->posts) }}</span>件</span>
  </li>
  <li>
    <a>フォロワー<span class="information-span">{{ count($followed_list) }}</span>人</a>
  </li>
  <li>
    <a><span class="information-span">{{ count($following_list) }}</span>人をフォロー中</a>
  </li>
</ul>
