@extends('layouts.app')
@section('title', 'Request ' . $serviceRequest->reference_number)
@section('page-title', 'Request Details')

@section('content')

{{-- Header card --}}
<div class="card mb-3">
    <div class="card-body">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.75rem;flex-wrap:wrap">
            <div style="min-width:0">
                <div style="font-size:.75rem;color:#9ca3af;font-weight:500;margin-bottom:.2rem">Service Request</div>
                <h5 style="font-size:.95rem;font-weight:800;color:#111827;margin:0 0 .25rem">{{ $serviceRequest->service->name }}</h5>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;align-items:center">
                    <span style="font-size:.78rem;color:#6b7280"><i class="bi bi-person me-1"></i>{{ $serviceRequest->citizen->name }}</span>
                    <span style="color:#d1d5db">·</span>
                    <code>{{ $serviceRequest->reference_number }}</code>
                </div>
            </div>
            @php $sc = ['pending'=>'#d97706','in_review'=>'#2563eb','missing_documents'=>'#dc2626','approved'=>'#16a34a','rejected'=>'#dc2626','completed'=>'#065f46']; @endphp
            <span class="sbadge s-{{ $serviceRequest->status }}" style="font-size:.78rem;padding:.3rem .8rem;flex-shrink:0">
                {{ ucfirst(str_replace('_',' ',$serviceRequest->status)) }}
            </span>
        </div>
        @if($serviceRequest->notes)
        <div style="background:#f8fafc;border-radius:8px;padding:.65rem .85rem;margin-top:.75rem;font-size:.8rem;color:#374151;border-left:3px solid var(--primary)">
            <strong>Citizen Note:</strong> {{ $serviceRequest->notes }}
        </div>
        @endif
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr;gap:1rem" class="req-grid">

    {{-- Left --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Update Status --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-arrow-repeat me-2" style="color:var(--primary)"></i>Update Status</span></div>
            <div class="card-body">
                <form action="{{ route('office.requests.status', $serviceRequest) }}" method="POST">
                    @csrf @method('PATCH')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:.75rem" class="status-form-grid">
                        <div>
                            <label class="form-label">New Status</label>
                            <select name="status" class="form-select" required>
                                @foreach(['in_review','missing_documents','approved','rejected','completed'] as $s)
                                    <option value="{{ $s }}" {{ $serviceRequest->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_',' ',$s)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Comment (visible to citizen)</label>
                            <input type="text" name="comment" class="form-control" placeholder="Optional...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Internal Office Note (private)</label>
                        <textarea name="office_notes" class="form-control" rows="2" placeholder="Internal notes, not shown to citizen...">{{ $serviceRequest->office_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle"></i> Update Status</button>
                </form>
            </div>
        </div>

        {{-- Documents --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-paperclip me-2" style="color:var(--primary)"></i>Submitted Documents</span></div>
            <div class="card-body" style="padding:0 !important">
                @forelse($serviceRequest->documents as $doc)
                <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem 1.2rem;border-bottom:1px solid #f3f4f6">
                    <div style="width:34px;height:34px;border-radius:8px;background:#f3f4f6;color:#6b7280;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $doc->original_name }}</div>
                        <div style="font-size:.7rem;color:#9ca3af">Uploaded by {{ $doc->uploaded_by }}</div>
                    </div>
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                        class="btn btn-sm" style="background:#f3f4f6;border:none;color:#374151;flex-shrink:0">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
                @empty
                <div style="padding:1.25rem;text-align:center;color:#9ca3af;font-size:.82rem">No documents uploaded.</div>
                @endforelse
            </div>
        </div>

        {{-- Chat --}}
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="bi bi-chat-dots me-2" style="color:var(--primary)"></i>Chat with Citizen</span></div>
            <div class="chat-box" id="chatBox">
                @if($serviceRequest->messages->isEmpty())
                <div style="padding:1rem;text-align:center;color:#9ca3af;font-size:.82rem">
                    No messages yet. Start the conversation.
                </div>
                @else
                    @foreach($serviceRequest->messages as $msg)
                    @php $mine = $msg->sender_id === auth()->id(); @endphp
                    <div class="msg {{ $mine ? 'mine' : 'theirs' }}">
                        <div class="msg-av {{ $mine ? 'av-me' : 'av-other' }}">
                            {{ strtoupper(substr($msg->sender->name,0,1)) }}
                        </div>
                        <div class="msg-bubble">
                            <div style="font-size:.7rem;font-weight:700;margin-bottom:.2rem;color:#6b7280">
                                {{ $mine ? 'You' : $msg->sender->name }}
                            </div>
                            <p>{{ $msg->body }}</p>
                            <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <div style="border-top:1px solid #f3f4f6;padding:.75rem 1rem">
                <div style="display:flex;gap:.5rem">
                    <input type="text" id="chatInput" class="form-control form-control-sm" placeholder="Message citizen...">
                    <button id="sendBtn" class="btn btn-primary btn-sm"><i class="bi bi-send"></i></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Right --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

        {{-- Status Log --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Timeline</span></div>
            <div class="card-body">
                @forelse($serviceRequest->statusLogs as $log)
                <div style="display:flex;gap:.65rem;margin-bottom:.75rem">
                    <div style="width:22px;height:22px;border-radius:50%;background:{{ $sc[$log->to_status] ?? '#6b7280' }}20;color:{{ $sc[$log->to_status] ?? '#6b7280' }};display:flex;align-items:center;justify-content:center;font-size:.6rem;flex-shrink:0;margin-top:2px">
                        <i class="bi bi-check2"></i>
                    </div>
                    <div>
                        <div style="font-size:.8rem;font-weight:600">{{ ucfirst(str_replace('_',' ',$log->to_status)) }}</div>
                        <div style="font-size:.7rem;color:#9ca3af">{{ $log->changedBy->name }} · {{ $log->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <p style="color:#9ca3af;font-size:.8rem;margin:0">No changes yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Payment --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Payment</span></div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;font-size:.82rem">
                    <span style="color:#6b7280">Amount</span>
                    <span style="font-weight:700">${{ number_format($serviceRequest->service->price, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;font-size:.82rem">
                    <span style="color:#6b7280">Method</span>
                    <span>{{ ucfirst($serviceRequest->payment_method ?? 'N/A') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.82rem">
                    <span style="color:#6b7280">Status</span>
                    <span class="sbadge s-{{ $serviceRequest->payment_status }}">{{ ucfirst($serviceRequest->payment_status) }}</span>
                </div>
                @if($serviceRequest->transaction_id)
                <div style="margin-top:.6rem;padding:.5rem;background:#f8fafc;border-radius:6px">
                    <div style="font-size:.68rem;color:#9ca3af;margin-bottom:2px">Transaction ID</div>
                    <code style="font-size:.72rem">{{ $serviceRequest->transaction_id }}</code>
                </div>
                @endif
            </div>
        </div>

        {{-- Citizen Info --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Citizen</span></div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:.65rem;margin-bottom:.75rem">
                    <div style="width:38px;height:38px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($serviceRequest->citizen->name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-size:.85rem;font-weight:700">{{ $serviceRequest->citizen->name }}</div>
                        <div style="font-size:.73rem;color:#9ca3af">{{ $serviceRequest->citizen->email }}</div>
                    </div>
                </div>
                @if($serviceRequest->citizen->phone)
                <div style="font-size:.79rem;color:#6b7280"><i class="bi bi-telephone me-1"></i>{{ $serviceRequest->citizen->phone }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media (min-width: 768px) {
    .req-grid { grid-template-columns: 1fr 280px !important; }
    .status-form-grid { grid-template-columns: 1fr 1fr !important; }
}
@media (max-width: 480px) {
    .status-form-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush

@push('scripts')
<script>
    const chatBox   = document.getElementById('chatBox');
    const chatInput = document.getElementById('chatInput');
    const sendBtn   = document.getElementById('sendBtn');

    async function sendMsg() {
        const body = chatInput.value.trim();
        if (!body) return;

        chatInput.disabled = true;
        sendBtn.disabled = true;

        try {
            const response = await fetch('{{ route('office.messages.send', $serviceRequest) }}', {
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
            const res = await fetch('{{ route('office.messages.get', $serviceRequest) }}', {
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
