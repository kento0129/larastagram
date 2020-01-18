<ul class="information-ul" id="resizeInformationUl">
  <li>
    <span class="information-span1 resize-information-span1">投稿<span class="information-span2 resize-information-span2">{{ count($user->posts) }}</span>件</span>
  </li>
  <li>
    <span class="information-span1 resize-information-span1" href="/">フォロワー<span class="information-span2 resize-information-span2">{{ count($user->followers) }}</span>人</span>
  </li>
  <li>
    <span class="information-span1 resize-information-span1" href="/"><span class="information-span2 resize-information-span2">{{ count($user->follows) }}</span>人をフォロー中</span>
  </li>
</ul>
