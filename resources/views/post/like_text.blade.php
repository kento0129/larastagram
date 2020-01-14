<strong>
@foreach ($post->likes as $like)
    @if ($loop->count == 1)
      {{ $like->user->user_name }} </strong> が「いいね！」しました
    @elseif ($loop->last)
      </strong> , <strong>
      {{ $like->user->user_name }}</strong> が「いいね！」しました
    @elseif (!$loop->first)
      </strong> , <strong>他{{ $loop->count - 1 }}人</strong> が「いいね！」しました
      @break
    @else
      {{ $like->user->user_name }}
    @endif
@endforeach
</strong>