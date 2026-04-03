@extends('layouts.app')
@section('title', 'Request ' . $serviceRequest->reference_number)
@section('page-title', 'Request Details')

@section('content')
<div class="card mb-3 citizen-reveal" data-citizen-reveal>
    <div class="card-body citizen-request-head">
        <div>
            <span class="citizen-request-head-kicker">Service Request</span>
            <h5 class="citizen-request-head-title">{{ $serviceRequest->service->name }}</h5>
            <div class="citizen-request-head-sub">{{ $serviceRequest->office->name }}</div>
            <code>{{ $serviceRequest->reference_number }}</code>
        </div>
        <x-status-pill :status="$serviceRequest->status" />
    </div>
</div>

<div class="citizen-request-detail-grid">
    <div class="citizen-request-left-col">
        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title"><i class="bi bi-clock-history me-2 text-primary"></i>Status History</span>
            </div>
            <div class="card-body">
                @php
                    $statusIcons = [
                        'pending' => 'bi-hourglass-split',
                        'in_review' => 'bi-search',
                        'missing_documents' => 'bi-exclamation-triangle',
                        'approved' => 'bi-check2-circle',
                        'rejected' => 'bi-x-circle',
                        'completed' => 'bi-check-circle-fill',
                    ];
                @endphp

                @forelse($serviceRequest->statusLogs as $log)
                    <div class="citizen-timeline-item">
                        <div class="citizen-timeline-rail">
                            <div class="citizen-timeline-dot">
                                <i class="bi {{ $statusIcons[$log->to_status] ?? 'bi-arrow-right' }}"></i>
                            </div>
                            @if(!$loop->last)
                                <div class="citizen-timeline-line"></div>
                            @endif
                        </div>
                        <div class="citizen-timeline-content">
                            <div class="citizen-timeline-title">{{ ucfirst(str_replace('_', ' ', $log->to_status)) }}</div>
                            <div class="citizen-timeline-time">{{ $log->created_at->diffForHumans() }}</div>
                            @if($log->comment)
                                <div class="citizen-timeline-note">{{ $log->comment }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="citizen-muted-note m-0">No updates yet.</p>
                @endforelse
            </div>
        </div>

        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title"><i class="bi bi-paperclip me-2 text-primary"></i>Documents</span>
            </div>
            <div class="card-body p-0">
                @forelse($serviceRequest->documents as $doc)
                    <div class="citizen-doc-row">
                        <div class="citizen-doc-icon">
                            <i class="bi bi-file-earmark"></i>
                        </div>
                        <div class="citizen-doc-main">
                            <div class="citizen-doc-name">{{ $doc->original_name }}</div>
                            <div class="citizen-doc-sub">Uploaded by {{ ucfirst($doc->uploaded_by) }}</div>
                        </div>
                        <a href="{{ route('citizen.documents.download', [$serviceRequest, $doc->id]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                @empty
                    <div class="citizen-panel-empty">
                        <i class="bi bi-folder2-open"></i>
                        <p>No documents uploaded.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title"><i class="bi bi-chat-dots me-2 text-primary"></i>Messages</span>
            </div>
            <div class="chat-box" id="chatBox">
                @if($serviceRequest->messages->isEmpty())
                    <div class="citizen-chat-empty">No messages yet. Start the conversation.</div>
                @else
                    @foreach($serviceRequest->messages as $msg)
                        @php $mine = $msg->sender_id === auth()->id(); @endphp
                        <div class="msg {{ $mine ? 'mine' : 'theirs' }}">
                            <div class="msg-av {{ $mine ? 'av-me' : 'av-other' }}">
                                {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                            </div>
                            <div class="msg-bubble">
                                <div class="msg-name">{{ $mine ? 'You' : $msg->sender->name }}</div>
                                <p>{{ $msg->body }}</p>
                                <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="citizen-chat-input-wrap">
                <div class="d-flex gap-2">
                    <input type="text" id="chatInput" class="form-control form-control-sm" placeholder="Type a message..." style="flex:1">
                    <button id="sendBtn" class="btn btn-primary btn-sm" style="flex-shrink:0"><i class="bi bi-send"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="citizen-request-right-col">
        @if($serviceRequest->qr_code)
            <div class="card text-center citizen-reveal" data-citizen-reveal>
                <div class="card-body">
                    <div class="citizen-side-card-title">Track via QR Code</div>
                    <img src="{{ Storage::url($serviceRequest->qr_code) }}" alt="QR Code" class="citizen-qr-image">
                    <div class="citizen-muted-note mt-2">Scan to check request status.</div>
                </div>
            </div>
        @endif

        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title">Payment</span>
            </div>
            <div class="card-body">
                <div class="citizen-side-row">
                    <span>Amount Due</span>
                    <strong>${{ number_format($serviceRequest->service->price, 2) }}</strong>
                </div>
                <div class="citizen-side-row">
                    <span>Status</span>
                    <x-status-pill :status="$serviceRequest->payment_status === 'paid' ? 'paid' : 'unpaid'" />
                </div>
                @if($serviceRequest->transaction_id)
                    <div class="citizen-side-row">
                        <span>Method</span>
                        <strong>{{ ucfirst($serviceRequest->payment_method ?? '-') }}</strong>
                    </div>
                @endif
                @if($serviceRequest->payment_status !== 'paid')
                    <a href="{{ route('citizen.payment', $serviceRequest) }}" class="btn btn-primary w-100 mt-2">
                        <i class="bi bi-credit-card me-1"></i> Pay Now
                    </a>
                @else
                    <div class="citizen-paid-ok"><i class="bi bi-check-circle me-1"></i>Payment complete</div>
                    <a href="{{ route('citizen.requests.receipt', $serviceRequest) }}" class="btn btn-outline-success w-100">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Download Receipt
                    </a>
                @endif
            </div>
        </div>

        <div class="card citizen-reveal" data-citizen-reveal>
            <div class="card-header">
                <span class="card-title">Appointment</span>
            </div>
            <div class="card-body">
                @if($serviceRequest->appointment)
                    <div class="citizen-side-row">
                        <span>Date</span>
                        <strong>{{ \Carbon\Carbon::parse($serviceRequest->appointment->appointment_date)->format('M d, Y') }}</strong>
                    </div>
                    <div class="citizen-side-row">
                        <span>Time</span>
                        <strong>{{ \Carbon\Carbon::parse($serviceRequest->appointment->appointment_time)->format('g:i A') }}</strong>
                    </div>
                    <x-status-pill :status="$serviceRequest->appointment->status" />
                @else
                    <p class="citizen-muted-note">No appointment scheduled yet.</p>
                    <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#aptModal">
                        <i class="bi bi-calendar-plus me-1"></i> Book Appointment
                    </button>
                @endif
            </div>
        </div>

        @if($serviceRequest->status === 'completed')
            <div class="card citizen-reveal" data-citizen-reveal>
                <div class="card-header">
                    <span class="card-title"><i class="bi bi-star me-2 text-primary"></i>Feedback</span>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger" style="font-size:.8rem">
                            <ul class="mb-0 ps-3">
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
                            <i class="bi bi-send me-1"></i> Submit Feedback
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="aptModal" tabindex="-1" aria-labelledby="aptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content citizen-apt-modal">
            <div class="modal-header border-0 pb-1">
                <h6 class="modal-title fw-bold" id="aptModalLabel">Book Appointment</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('citizen.appointments.book') }}" method="POST">
                @csrf
                <input type="hidden" name="office_id" value="{{ $serviceRequest->office_id }}">
                <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                <div class="modal-body pt-2">
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
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any specific notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
body.es-role-citizen .citizen-request-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: .75rem;
    flex-wrap: wrap;
}

body.es-role-citizen .citizen-request-head-kicker {
    display: inline-flex;
    padding: .2rem .55rem;
    border-radius: 999px;
    background: #E0F2FE;
    border: 1px solid #BAE6FD;
    color: #0369A1;
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
}

body.es-role-citizen .citizen-request-head-title {
    margin: .55rem 0 .18rem;
    font-size: 1rem;
    font-weight: 800;
    color: #0F172A;
}

body.es-role-citizen .citizen-request-head-sub {
    color: #64748B;
    font-size: .79rem;
    margin-bottom: .3rem;
}

body.es-role-citizen .citizen-request-detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

body.es-role-citizen .citizen-request-left-col,
body.es-role-citizen .citizen-request-right-col {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

body.es-role-citizen .citizen-timeline-item {
    display: flex;
    gap: .75rem;
    margin-bottom: .9rem;
}

body.es-role-citizen .citizen-timeline-item:last-child {
    margin-bottom: 0;
}

body.es-role-citizen .citizen-timeline-rail {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-timeline-dot {
    width: 1.65rem;
    height: 1.65rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #E0F2FE;
    color: #0369A1;
    border: 1px solid #BAE6FD;
    font-size: .7rem;
}

body.es-role-citizen .citizen-timeline-line {
    width: 1px;
    flex: 1;
    background: #E2E8F0;
    margin-top: .2rem;
}

body.es-role-citizen .citizen-timeline-title {
    font-size: .84rem;
    font-weight: 700;
    color: #0F172A;
}

body.es-role-citizen .citizen-timeline-time {
    font-size: .72rem;
    color: #64748B;
    margin-top: .1rem;
}

body.es-role-citizen .citizen-timeline-note {
    margin-top: .22rem;
    font-size: .78rem;
    color: #334155;
}

body.es-role-citizen .citizen-muted-note {
    color: #64748B;
    font-size: .79rem;
}

body.es-role-citizen .citizen-doc-row {
    display: flex;
    align-items: center;
    gap: .72rem;
    padding: .82rem 1rem;
    border-bottom: 1px solid #E2E8F0;
}

body.es-role-citizen .citizen-doc-row:last-child {
    border-bottom: 0;
}

body.es-role-citizen .citizen-doc-icon {
    width: 2.1rem;
    height: 2.1rem;
    border-radius: .65rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #E0F2FE;
    color: #0369A1;
    border: 1px solid #BAE6FD;
    flex-shrink: 0;
}

body.es-role-citizen .citizen-doc-main {
    flex: 1;
    min-width: 0;
}

body.es-role-citizen .citizen-doc-name {
    font-size: .82rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

body.es-role-citizen .citizen-doc-sub {
    font-size: .72rem;
    color: #64748B;
}

body.es-role-citizen .chat-box {
    max-height: 20rem;
    overflow: auto;
    padding: 1rem;
    background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFF 100%);
}

body.es-role-citizen .citizen-chat-empty {
    text-align: center;
    color: #64748B;
    font-size: .8rem;
}

body.es-role-citizen .msg {
    display: flex;
    gap: .55rem;
    margin-bottom: .7rem;
}

body.es-role-citizen .msg:last-child {
    margin-bottom: 0;
}

body.es-role-citizen .msg.mine {
    justify-content: flex-end;
}

body.es-role-citizen .msg.theirs {
    justify-content: flex-start;
}

body.es-role-citizen .msg-av {
    width: 1.85rem;
    height: 1.85rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .7rem;
    font-weight: 700;
    flex-shrink: 0;
}

body.es-role-citizen .msg-av.av-me {
    background: #DBEAFE;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
    order: 2;
}

body.es-role-citizen .msg-av.av-other {
    background: #ECFEFF;
    color: #0F766E;
    border: 1px solid #A5F3FC;
}

body.es-role-citizen .msg-bubble {
    max-width: min(80%, 27rem);
    border-radius: .85rem;
    padding: .55rem .66rem;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
}

body.es-role-citizen .msg.mine .msg-bubble {
    background: #E0F2FE;
    border-color: #BAE6FD;
    order: 1;
}

body.es-role-citizen .msg-name {
    font-size: .68rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: .15rem;
}

body.es-role-citizen .msg-bubble p {
    margin: 0;
    font-size: .79rem;
    color: #0F172A;
    line-height: 1.45;
    white-space: pre-wrap;
}

body.es-role-citizen .msg-time {
    margin-top: .2rem;
    font-size: .66rem;
    color: #64748B;
    text-align: right;
}

body.es-role-citizen .citizen-chat-input-wrap {
    border-top: 1px solid #E2E8F0;
    padding: .75rem 1rem;
}

body.es-role-citizen .citizen-side-card-title {
    font-size: .84rem;
    font-weight: 700;
    margin-bottom: .7rem;
    color: #0F172A;
}

body.es-role-citizen .citizen-qr-image {
    max-width: 10rem;
    border-radius: .6rem;
    border: 1px solid #CBD5E1;
}

body.es-role-citizen .citizen-side-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: .8rem;
    color: #475569;
    padding: .3rem 0;
}

body.es-role-citizen .citizen-side-row strong {
    color: #0F172A;
}

body.es-role-citizen .citizen-paid-ok {
    text-align: center;
    margin: .65rem 0;
    color: #047857;
    font-size: .79rem;
    font-weight: 700;
}

body.es-role-citizen .citizen-panel-empty {
    padding: 1.4rem 1rem;
    text-align: center;
    color: #64748B;
}

body.es-role-citizen .citizen-panel-empty i {
    font-size: 1.5rem;
    color: #94A3B8;
    display: block;
    margin-bottom: .35rem;
}

body.es-role-citizen .citizen-panel-empty p {
    margin: 0;
    font-size: .8rem;
}

body.es-role-citizen .citizen-apt-modal {
    border: 1px solid #DBEAFE;
    border-radius: 1rem;
    box-shadow: 0 24px 56px rgba(15, 23, 42, 0.22);
}

@media (min-width: 768px) {
    body.es-role-citizen .citizen-request-detail-grid {
        grid-template-columns: 1fr 320px;
    }
}
</style>
@endpush

@push('scripts')
<script>
    const chatBox = document.getElementById('chatBox');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');
    const myInitial = @json(strtoupper(substr(auth()->user()->name, 0, 1)));

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
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body }),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                alert(data.message || 'Failed to send message');
                return;
            }

            chatInput.value = '';
            await loadMessages();
        } catch (error) {
            alert('Something went wrong while sending the message.');
        } finally {
            chatInput.disabled = false;
            sendBtn.disabled = false;
            chatInput.focus();
        }
    }

    async function loadMessages() {
        try {
            const res = await fetch('{{ route('citizen.messages.get', $serviceRequest) }}', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            chatBox.innerHTML = '';

            if (!data.messages.length) {
                chatBox.innerHTML = `
                    <div style="padding:1rem;text-align:center;color:#9ca3af;font-size:.82rem">
                        No messages yet. Start the conversation.
                    </div>
                `;
                return;
            }

            data.messages.forEach(msg => {
                const mine = msg.sender_id === {{ auth()->id() }};

                const div = document.createElement('div');
                div.className = 'msg ' + (mine ? 'mine' : 'theirs');

                div.innerHTML = `
                    <div class="msg-av ${mine ? 'av-me' : 'av-other'}">
                        ${msg.sender.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="msg-bubble">
                        <div style="font-size:.7rem;font-weight:700;margin-bottom:.2rem;color:#6b7280">
                            ${mine ? 'You' : msg.sender.name}
                        </div>
                        <p></p>
                        <div class="msg-time">
                            ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                        </div>
                    </div>
                `;

                div.querySelector('p').textContent = msg.body;
                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;

        } catch (e) {
            console.error('Error loading messages', e);
        }
    }

    sendBtn.addEventListener('click', sendMsg);

    chatInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMsg();
        }
    });

    loadMessages();
    setInterval(loadMessages, 2000);
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
@endsection
