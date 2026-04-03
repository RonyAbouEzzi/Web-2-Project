@extends('layouts.app')
@section('title', 'Request ' . $serviceRequest->reference_number)
@section('page-title', 'Request Details')

@section('content')

{{-- Header --}}
<div class="card mb-3">
    <div class="card-body">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.75rem;flex-wrap:wrap">
            <div style="min-width:0">
                <h5 style="font-size:.95rem;font-weight:800;margin:0 0 .2rem;color:#111827">{{ $serviceRequest->service->name }}</h5>
                <div style="font-size:.78rem;color:#6b7280;margin-bottom:.3rem">{{ $serviceRequest->office->name }}</div>
                <code>{{ $serviceRequest->reference_number }}</code>
            </div>
            <span class="sbadge s-{{ $serviceRequest->status }}" style="font-size:.78rem;padding:.3rem .8rem;flex-shrink:0">
                {{ ucfirst(str_replace('_',' ',$serviceRequest->status)) }}
            </span>
        </div>
    </div>
</div>

{{-- Mobile: stacked, Desktop: two cols --}}
<div style="display:grid;grid-template-columns:1fr;gap:1rem" class="detail-grid">

    {{-- Left column --}}
    <div style="display:flex;flex-direction:column;gap:1rem" class="left-col">

        {{-- Status timeline --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-clock-history me-2" style="color:var(--primary)"></i>Status History</span></div>
            <div class="card-body">
                @php $statColors = ['pending'=>'#d97706','in_review'=>'#2563eb','missing_documents'=>'#dc2626','approved'=>'#16a34a','rejected'=>'#dc2626','completed'=>'#065f46']; @endphp
                @forelse($serviceRequest->statusLogs as $log)
                <div style="display:flex;gap:.75rem;margin-bottom:.9rem">
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                        <div style="width:26px;height:26px;border-radius:50%;background:{{ $statColors[$log->to_status] ?? '#6b7280' }}20;color:{{ $statColors[$log->to_status] ?? '#6b7280' }};display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        @if(!$loop->last)
                        <div style="width:1px;flex:1;background:#e5eaf0;margin-top:3px"></div>
                        @endif
                    </div>
                    <div style="padding-bottom:{{ $loop->last ? '0' : '.5rem' }}">
                        <div style="font-size:.82rem;font-weight:600">{{ ucfirst(str_replace('_',' ',$log->to_status)) }}</div>
                        <div style="font-size:.72rem;color:#9ca3af">{{ $log->created_at->diffForHumans() }}</div>
                        @if($log->comment)<div style="font-size:.78rem;color:#374151;margin-top:2px">{{ $log->comment }}</div>@endif
                    </div>
                </div>
                @empty
                <p style="color:#9ca3af;font-size:.82rem;margin:0">No updates yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Documents --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-paperclip me-2" style="color:var(--primary)"></i>Documents</span></div>
            <div class="card-body" style="padding:0 !important">
                @forelse($serviceRequest->documents as $doc)
                <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem 1.2rem;border-bottom:1px solid #f3f4f6">
                    <div style="width:34px;height:34px;border-radius:8px;background:#f3f4f6;color:#6b7280;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">
                        <i class="bi bi-file-earmark"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $doc->original_name }}</div>
                        <div style="font-size:.7rem;color:#9ca3af">By {{ $doc->uploaded_by }}</div>
                    </div>
                    <a href="{{ route('citizen.documents.download', [$serviceRequest, $doc->id]) }}"
                        class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151;flex-shrink:0">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
                @empty
                <div style="padding:1.25rem;color:#9ca3af;font-size:.82rem;text-align:center">No documents uploaded.</div>
                @endforelse
            </div>
        </div>

        {{-- Chat --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-chat-dots me-2" style="color:var(--primary)"></i>Messages</span></div>
            <div class="chat-box" id="chatBox">
                @if($serviceRequest->messages->isEmpty())
                    <div data-empty-chat style="padding:1rem;text-align:center;color:#9ca3af;font-size:.82rem">
                        No messages yet. Start the conversation.
                    </div>
                @else
                    @foreach($serviceRequest->messages as $msg)
                    @php $mine = $msg->sender_id === auth()->id(); @endphp
                    <div class="msg {{ $mine ? 'mine' : 'theirs' }}" data-message-id="{{ $msg->id }}" data-read-at="{{ $msg->read_at }}">
                        <div class="msg-av {{ $mine ? 'av-me' : 'av-other' }}">
                            {{ strtoupper(substr($msg->sender->name,0,1)) }}
                        </div>
                        <div class="msg-bubble">
                            <div style="font-size:.7rem;font-weight:700;margin-bottom:.2rem;color:#6b7280">
                                {{ $mine ? 'You' : $msg->sender->name }}
                            </div>
                            <p>{{ $msg->body }}</p>
                            <div class="msg-time">
                                {{ $msg->created_at->format('H:i') }}
                                @if($msg->sender_id === auth()->id())
                                    · {{ $msg->read_at ? 'Seen' : 'Sent' }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <div style="border-top:1px solid #f3f4f6;padding:.75rem 1rem">
                <div style="display:flex;gap:.5rem">
                    <input type="text" id="chatInput" class="form-control form-control-sm" placeholder="Type a message..." style="flex:1">
                    <button id="sendBtn" class="btn btn-primary btn-sm" style="flex-shrink:0"><i class="bi bi-send"></i></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:1rem" class="right-col">

        {{-- QR Code --}}
        @if($serviceRequest->qr_code)
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:.83rem;font-weight:700;margin-bottom:.75rem">Track via QR Code</div>
                <img src="{{ Storage::url($serviceRequest->qr_code) }}" alt="QR Code"
                    style="max-width:160px;border-radius:8px;border:1px solid #e5eaf0">
                <div style="font-size:.72rem;color:#9ca3af;margin-top:.6rem">Scan to check request status</div>
            </div>
        </div>
        @endif

        {{-- Payment --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Payment</span></div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.6rem">
                    <span style="font-size:.8rem;color:#6b7280">Amount Due</span>
                    <span style="font-size:.88rem;font-weight:700">${{ number_format($serviceRequest->service->price, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.9rem">
                    <span style="font-size:.8rem;color:#6b7280">Status</span>
                    <span class="sbadge s-{{ $serviceRequest->payment_status }}">{{ ucfirst($serviceRequest->payment_status) }}</span>
                </div>
                @if($serviceRequest->payment_status !== 'paid')
                <a href="{{ route('citizen.payment', $serviceRequest) }}" class="btn btn-primary btn-block">
                    <i class="bi bi-credit-card"></i> Pay Now
                </a>
                @else
                <div style="text-align:center;color:#16a34a;font-size:.8rem;font-weight:600"><i class="bi bi-check-circle me-1"></i>Payment complete</div>
                @endif
            </div>
        </div>

        {{-- Appointment --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Appointment</span></div>
            <div class="card-body">
                @if($serviceRequest->appointment)
                    <div style="font-size:.82rem;margin-bottom:.4rem"><strong>Date:</strong> {{ $serviceRequest->appointment->appointment_date }}</div>
                    <div style="font-size:.82rem;margin-bottom:.6rem"><strong>Time:</strong> {{ $serviceRequest->appointment->appointment_time }}</div>
                    <span class="sbadge s-{{ $serviceRequest->appointment->status }}">{{ ucfirst($serviceRequest->appointment->status) }}</span>
                @else
                    <p style="font-size:.8rem;color:#9ca3af;margin-bottom:.75rem">No appointment scheduled.</p>
                    <button class="btn btn-sm" style="background:var(--primary-light);color:var(--primary);border:none;width:100%"
                            data-bs-toggle="modal" data-bs-target="#aptModal">
                        <i class="bi bi-calendar-plus"></i> Book Appointment
                    </button>
                @endif
            </div>
        </div>

        @if($serviceRequest->status === 'completed')
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="bi bi-star me-2" style="color:var(--primary)"></i>Feedback
                </span>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger" style="font-size:.8rem">
                        <ul style="margin:0;padding-left:1rem">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('citizen.feedback.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="office_id" value="{{ $serviceRequest->office_id }}">
                    <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">

                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select" required>
                            <option value="">Select rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Very Good</option>
                            <option value="3">3 - Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="1">1 - Poor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Write your feedback..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-send"></i> Submit Feedback
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Appointment modal --}}
<div class="modal fade" id="aptModal" tabindex="-1" aria-labelledby="aptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content" style="border:none;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.2)">
            <div class="modal-header" style="border:none;padding:1.25rem 1.25rem .5rem">
                <h6 class="modal-title" id="aptModalLabel" style="font-weight:800">Book Appointment</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('citizen.appointments.book') }}" method="POST">
                @csrf
                <input type="hidden" name="office_id" value="{{ $serviceRequest->office_id }}">
                <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                <div class="modal-body" style="padding:1rem 1.25rem">
                    <div class="mb-3">
                        <label class="form-label">Preferred Date</label>
                        <input type="date" name="appointment_date" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preferred Time</label>
                        <input type="time" name="appointment_time" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any specific reason or notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border:none;padding:.75rem 1.25rem 1.25rem;gap:.5rem">
                    <button type="button" class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
@media (min-width: 768px) {
    .detail-grid { grid-template-columns: 1fr 320px !important; }
}
</style>
@endpush

@push('scripts')
<script>
    const chatBox = document.getElementById('chatBox');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');

    const renderedMessageIds = new Set(
        Array.from(chatBox.querySelectorAll('[data-message-id]'))
            .map(el => String(el.dataset.messageId))
    );

    function appendMessage(msg) {
        if (msg.id && renderedMessageIds.has(String(msg.id))) {
            return;
        }

        const mine = msg.sender_id === {{ auth()->id() }};

        const emptyState = chatBox.querySelector('[data-empty-chat]');
        if (emptyState) {
            emptyState.remove();
        }

        const div = document.createElement('div');
        div.className = 'msg ' + (mine ? 'mine' : 'theirs');

        div.innerHTML = `
            <div class="msg-av ${mine ? 'av-me' : 'av-other'}">
                ${(msg.sender?.name || '?').charAt(0).toUpperCase()}
            </div>
            <div class="msg-bubble">
                <div style="font-size:.7rem;font-weight:700;margin-bottom:.2rem;color:#6b7280">
                    ${mine ? 'You' : (msg.sender?.name || 'Unknown')}
                </div>
                <p></p>
                <div class="msg-time">
                    ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                    ${msg.sender_id === {{ auth()->id() }}
                        ? (msg.read_at ? ' · Seen' : ' · Sent')
                        : ''}
                </div>
            </div>
        `;

        div.querySelector('p').textContent = msg.body;

        if (msg.id) {
            div.dataset.messageId = String(msg.id);
            renderedMessageIds.add(String(msg.id));
        }

        if (msg.read_at) {
            div.dataset.readAt = msg.read_at;
        }

        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    async function sendMsg() {
        const body = chatInput.value.trim();
        if (!body) return;

        chatInput.disabled = true;
        sendBtn.disabled = true;

        try {
            const response = await fetch('{{ route('citizen.messages.send', $serviceRequest) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ body })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                alert(data.message || 'Failed to send message');
                return;
            }

            chatInput.value = '';
            appendMessage(data.message);
        } catch (error) {
            alert('Something went wrong while sending the message.');
        } finally {
            chatInput.disabled = false;
            sendBtn.disabled = false;
            chatInput.focus();
        }
    }

    async function markIncomingMessagesAsRead() {
        try {
            await fetch('{{ route('citizen.messages.read', $serviceRequest) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Failed to mark messages as read');
        }
    }

    sendBtn.addEventListener('click', sendMsg);

    chatInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMsg();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (!window.Echo) {
            console.error('Echo is not loaded');
            return;
        }

        window.Echo.private('request.{{ $serviceRequest->id }}')
            .listen('.message.sent', async (e) => {
                appendMessage(e);

                if (e.sender_id !== {{ auth()->id() }}) {
                    await markIncomingMessagesAsRead();
                }
            })
            .listen('.messages.read', (e) => {
                e.message_ids.forEach((id) => {
                    const msgEl = chatBox.querySelector(`[data-message-id="${id}"]`);
                    if (!msgEl) return;

                    msgEl.dataset.readAt = '1';

                    const timeEl = msgEl.querySelector('.msg-time');
                    if (!timeEl) return;

                    if (msgEl.classList.contains('mine') && !timeEl.textContent.includes('Seen')) {
                        timeEl.textContent = timeEl.textContent.replace(' · Sent', '') + ' · Seen';
                    }
                });
            });
    });

    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
@endsection
