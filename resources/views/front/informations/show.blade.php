@extends('front.layouts.app')

@push('style')
<style>
.comment-card, .reply-card {
    border: 1px solid #e3e3e3;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: #fff;
}
.reply-card {
    margin-left: 3rem;
    background-color: #f8f9fa;
}
.comment-card .author, .reply-card .author { font-weight: bold; }
.comment-card .timestamp, .reply-card .timestamp { font-size: 0.85rem; color: #6c757d; }
.comment-form textarea { resize: none; }
.thumbnail-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 0.75rem; }
</style>
@endpush

@section('content')
@include('front.partials.breadcumb')
<div class="container py-5">
    <small class="text-muted">Dipublikasikan: {{ format_datetime($information->published_at) }} | <strong> <i class="fa fa-user"></i> {{ $information->author->name }}</strong></small><br>
    <!-- <img src="{{ is_img(asset('front/images/information/'.$information->image)) }}" class="img-fluid my-3 w-100" alt="{{ $information->title }}"> -->
    <img src="{{ first_image_or_default($information->content, asset('front/img/no-image.webp')) }}" 
     class="img-fluid my-3 w-100" alt="{{ $information->title }}">
    <div class="mb-4">{!! $information->content !!}</div>
    <hr>
    <h4>
    <i class="fa fa-comments text-primary me-2"></i>
    Komentar ({{ $information->comments_count ?? 0 }})
</h4>
    <div id="commentsList" data-information-id="{{ $information->id }}">
    @foreach($information->comments as $comment)
        @include('front.informations.comment', ['comment' => $comment])
    @endforeach
</div>

    @auth
    <div class="comment-form mt-4">
        <h5>Tulis Komentar</h5>
        <div class="input-group">
            <textarea id="commentInput" class="form-control" rows="3" placeholder="Tulis komentar..."></textarea>
            <button class="btn btn-primary mt-2" id="submitComment">
                <i class="fa fa-paper-plane me-1"></i> Kirim
            </button>
        </div>
    </div>
    @else
    <p class="text-muted">Silakan login untuk memberikan komentar.</p>
    @endauth
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const infoId = document.getElementById('commentsList').dataset.informationId;
    const submitBtn = document.getElementById('submitComment');
    const input = document.getElementById('commentInput');

    submitBtn.addEventListener('click', async () => {
        const comment = input.value.trim();
        if(!comment) return alert('Komentar tidak boleh kosong!');

        const res = await fetch(`/informasi/${infoId}/comment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ comment })
        });
        const data = await res.json();
        if(data.success) location.reload();
    });

    // Reply logic
    const commentsList = document.getElementById('commentsList');
    commentsList.addEventListener('click', e => {
        if(e.target.classList.contains('reply-btn')){
            const parentId = e.target.dataset.id;
            let form = document.getElementById(`reply-form-${parentId}`);
            if(form){ form.remove(); return; }

            const html = `<div class="mt-2" id="reply-form-${parentId}">
                <textarea class="form-control mb-2" rows="2" placeholder="Tulis balasan..."></textarea>
                <button class="btn btn-sm btn-primary send-reply">Kirim</button>
            </div>`;
            e.target.insertAdjacentHTML('afterend', html);

            const replyForm = document.getElementById(`reply-form-${parentId}`);
            const sendBtn = replyForm.querySelector('.send-reply');
            const textarea = replyForm.querySelector('textarea');

            sendBtn.addEventListener('click', async () => {
                const reply = textarea.value.trim();
                if(!reply) return alert('Balasan tidak boleh kosong!');
                const res = await fetch(`/informasi/${infoId}/reply`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ comment: reply, parent_id: parentId })
                });
                const data = await res.json();
                if(data.success) location.reload();
            });
        }
    });
});
</script>
@endpush
