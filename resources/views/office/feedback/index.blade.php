@extends('layouts.app')
@section('title','Feedback')
@section('page-title','Citizen Feedback')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">Reviews & Ratings</span>
        <div style="display:flex;align-items:center;gap:.4rem">
            <i class="bi bi-star-fill" style="color:#f59e0b;font-size:.85rem"></i>
            <span style="font-weight:800;font-size:.9rem">{{ number_format($feedback->avg('rating'), 1) }}</span>
            <span style="font-size:.75rem;color:#9ca3af">({{ $feedback->total() }} reviews)</span>
        </div>
    </div>
    <div class="card-body" style="padding:0 !important">
        @forelse($feedback as $fb)
        <div style="padding:1.1rem 1.2rem;border-bottom:1px solid #f3f4f6">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.75rem;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:.65rem">
                    <div style="width:36px;height:36px;border-radius:50%;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($fb->citizen->name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.85rem">{{ $fb->citizen->name }}</div>
                        <div style="display:flex;gap:1px">
                            @for($i=1;$i<=5;$i++)
                            <i class="bi bi-star{{ $i <= $fb->rating ? '-fill' : '' }}" style="color:{{ $i <= $fb->rating ? '#f59e0b' : '#d1d5db' }};font-size:.75rem"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <span style="font-size:.72rem;color:#9ca3af">{{ $fb->created_at->diffForHumans() }}</span>
            </div>
            @if($fb->comment)
            <p style="font-size:.82rem;color:#374151;margin:.75rem 0 .5rem;line-height:1.5">{{ $fb->comment }}</p>
            @endif

            {{-- Existing reply --}}
            @if($fb->office_reply)
            <div style="background:#f0fdf4;border-radius:8px;padding:.65rem .9rem;margin-top:.6rem;border-left:3px solid #16a34a">
                <div style="font-size:.7rem;font-weight:700;color:#16a34a;margin-bottom:.2rem">Office Reply <span style="color:#9ca3af;font-weight:400">({{ $fb->reply_is_public ? 'Public' : 'Private' }})</span></div>
                <p style="font-size:.8rem;color:#374151;margin:0">{{ $fb->office_reply }}</p>
            </div>
            @else
            {{-- Reply form --}}
            <form action="{{ route('office.feedback.reply', $fb) }}" method="POST" style="margin-top:.65rem">
                @csrf @method('PATCH')
                <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                    <input type="text" name="office_reply" class="form-control form-control-sm" placeholder="Write a reply..." required style="flex:1;min-width:180px">
                    <select name="reply_is_public" class="form-select form-select-sm" style="max-width:110px">
                        <option value="1">Public</option>
                        <option value="0">Private</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary" style="flex-shrink:0"><i class="bi bi-reply"></i> Reply</button>
                </div>
            </form>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:3rem;color:#9ca3af">
            <i class="bi bi-star" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db"></i>
            <div style="font-weight:600;font-size:.88rem">No feedback yet</div>
            <div style="font-size:.78rem;margin-top:.25rem">Citizen ratings will appear here</div>
        </div>
        @endforelse
    </div>
    @if($feedback->hasPages())
    <div style="padding:.75rem 1rem;border-top:1px solid #f3f4f6">{{ $feedback->links() }}</div>
    @endif
</div>
@endsection
