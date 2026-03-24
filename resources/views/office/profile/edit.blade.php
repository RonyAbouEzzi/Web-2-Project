@extends('layouts.app')
@section('title','Office Profile')
@section('page-title','Office Profile')

@section('content')
<div style="max-width:700px">
    <div class="card">
        <div class="card-header"><span class="card-title">Edit Office Details</span></div>
        <div class="card-body">
            <form action="{{ route('office.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem" class="profile-grid">
                    <div class="profile-full">
                        <label class="form-label">Office Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $office->name) }}" required>
                    </div>
                    <div class="profile-full">
                        <label class="form-label">Address *</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $office->address) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $office->phone) }}" placeholder="+961 1 ...">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $office->email) }}">
                    </div>
                    <div>
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $office->website) }}" placeholder="https://...">
                    </div>
                    <div>
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div>
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $office->latitude) }}" placeholder="33.8938">
                    </div>
                    <div>
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $office->longitude) }}" placeholder="35.5018">
                    </div>
                </div>

                {{-- Working Hours --}}
                <div style="margin-bottom:.75rem">
                    <label class="form-label">Working Hours</label>
                    @php $wh = $office->working_hours ?? []; $days = ['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday']; @endphp
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.5rem">
                        @foreach($days as $key => $label)
                        <div style="display:flex;align-items:center;gap:.5rem;background:#f8fafc;border-radius:8px;padding:.5rem .75rem">
                            <span style="font-size:.75rem;font-weight:600;color:#374151;min-width:30px">{{ substr($label,0,3) }}</span>
                            <input type="text" name="working_hours[{{ $key }}]" class="form-control form-control-sm"
                                   value="{{ $wh[$key] ?? '' }}" placeholder="08:00-16:00 or closed" style="font-size:.75rem">
                        </div>
                        @endforeach
                    </div>
                    <p class="form-text">Format: 08:00-16:00 or type "closed"</p>
                </div>

                {{-- Google Maps preview --}}
                @if($office->latitude && $office->longitude)
                @php
                    $mapsKey = config('services.google_maps.api_key');
                    $mapQuery = rawurlencode($office->latitude . ',' . $office->longitude);
                    $mapUrl = $mapsKey
                        ? "https://www.google.com/maps/embed/v1/place?key={$mapsKey}&q={$mapQuery}&zoom=15"
                        : null;
                @endphp
                <div style="margin-bottom:.75rem">
                    <label class="form-label">Map Preview</label>
                    @if($mapUrl)
                        <div style="border-radius:10px;overflow:hidden;border:1px solid #e5eaf0;height:260px;background:#f3f4f6">
                            <iframe
                                title="Office location map"
                                src="{{ $mapUrl }}"
                                width="100%"
                                height="260"
                                style="border:0"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <p class="form-text">Showing map for coordinates {{ $office->latitude }}, {{ $office->longitude }}.</p>
                    @else
                        <div style="border-radius:10px;overflow:hidden;border:1px solid #e5eaf0;height:200px;background:#f3f4f6;display:flex;align-items:center;justify-content:center">
                            <div style="text-align:center;color:#9ca3af">
                                <i class="bi bi-geo-alt" style="font-size:2rem;display:block;margin-bottom:.4rem"></i>
                                <span style="font-size:.78rem">{{ $office->latitude }}, {{ $office->longitude }}</span>
                            </div>
                        </div>
                        <p class="form-text">Set <code>GOOGLE_MAPS_API_KEY</code> in <code>.env</code> and clear config cache.</p>
                    @endif
                </div>
                @endif

                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Changes</button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
@media(max-width:576px) {
    .profile-grid { grid-template-columns: 1fr !important; }
}
.profile-full { grid-column: 1 / -1; }
</style>
@endpush
@endsection
