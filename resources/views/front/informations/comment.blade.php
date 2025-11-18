<div class="{{ $comment->parent_id ? 'reply-card' : 'comment-card' }}" id="comment-{{ $comment->id }}">
    <div class="d-flex align-items-center mb-1">
        <img src="{{ is_user($comment->user->image) }}" class="thumbnail-avatar">
        <div>
            <div class="author">{{ $comment->user->name ?? 'Anonymous' }}</div>
            <div class="timestamp">{{ $comment->created_at->diffForHumans() }}</div>
        </div>
    </div>
    
    <div class="comment">{{ $comment->comment }}</div>
    @if(!$comment->parent_id)
        <button class="btn btn-sm btn-link reply-btn" data-id="{{ $comment->id }}">Balas</button>
    @endif
    @if($comment->replies)
    <i class="fa fa-comment text-primary me-2"></i> {{ count($comment->replies) }}
    <div class="replies mt-2">
        @foreach($comment->replies as $reply)
            @include('front.informations.comment', ['comment' => $reply])
        @endforeach
    </div>
    @endif
</div>
