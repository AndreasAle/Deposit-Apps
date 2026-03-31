<div class="comment">
    <div class="c-head">
        <div class="c-user">{{ $comment->user->name ?? '-' }}</div>
        <div class="c-time">{{ optional($comment->created_at)->format('d M Y H:i') }}</div>
    </div>
    <div class="content">{!! nl2br(e((string)$comment->content)) !!}</div>

    @if($comment->children && $comment->children->count() > 0)
        <div class="children">
            @foreach($comment->children as $child)
                @include('admin.forum._comment', ['comment' => $child])
            @endforeach
        </div>
    @endif
</div>
