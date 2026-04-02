@extends('layouts.app')
@section('title','Browse Services')
@section('page-title','Browse Offices')

@section('content')

{{-- Search --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="d-flex flex-wrap gap-2">
            <div class="flex-grow-1 position-relative" style="min-width:180px;">
                <i class="bi bi-search position-absolute text-muted" style="left:.8rem;top:50%;transform:translateY(-50%);pointer-events:none;"></i>
                <input type="text" name="search" class="form-control ps-5" placeholder="Search offices by name..." value="{{ request('search') }}">
            </div>
            <select name="municipality_id" class="form-select" style="min-width:160px;">
                <option value="">All Municipalities</option>
                @foreach(\App\Models\Municipality::where('is_active',true)->get() as $m)
                    <option value="{{ $m->id }}" {{ request('municipality_id')==$m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Filter</button>
        </form>
    </div>
</div>

{{-- Map Section --}}
<div class="card mb-3">
    <div class="card-header">
        <span class="card-title">
            <i class="bi bi-geo-alt me-2" style="color:var(--primary)"></i>Office Locations
        </span>
    </div>
    <div class="card-body">
        @if($mapOffices->isEmpty())
            <div style="text-align:center;padding:2rem 1rem;color:#9ca3af">
                No office locations available to display on the map.
            </div>
        @else
            <div id="officesMap" style="width:100%;height:430px;border-radius:12px;overflow:hidden;border:1px solid #e5eaf0"></div>
        @endif
    </div>
</div>

{{-- Office Cards Grid --}}
@if($offices->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="bi bi-building-x text-muted d-block mb-3" style="font-size:2.5rem;"></i>
            <div class="fw-semibold text-sm mb-1">No offices found</div>
            <a href="{{ route('citizen.offices') }}" class="text-sm">Clear filters</a>
        </div>
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
        @foreach($offices as $office)
            <a href="{{ route('citizen.offices.show', $office) }}" class="text-decoration-none text-reset d-block">
                <div class="office-card-wrap">
                    {{-- Header --}}
                    <div class="office-card-header">
                        @if($office->logo)
                            <img src="{{ Storage::url($office->logo) }}" alt="{{ $office->name }}" class="office-card-logo">
                        @else
                            <div class="office-card-logo-placeholder">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="office-card-name">{{ $office->name }}</div>
                            <div class="office-card-municipality">{{ $office->municipality->name }}</div>
                        </div>
                    </div>
                    {{-- Body --}}
                    <div class="office-card-body">
                        @if($office->address)
                            <div class="office-card-meta">
                                <i class="bi bi-geo-alt office-card-meta-icon"></i>
                                <span class="office-card-meta-text">{{ $office->address }}</span>
                            </div>
                        @endif
                        @if($office->phone)
                            <div class="office-card-meta">
                                <i class="bi bi-telephone office-card-meta-icon" style="font-size:.82rem;"></i>
                                <span class="office-card-meta-text">{{ $office->phone }}</span>
                            </div>
                        @endif
                        <div class="office-card-footer">
                            <span class="text-xs text-muted"><i class="bi bi-grid-3x3-gap me-1"></i>{{ $office->services->count() }} services</span>
                            @php $rating = $office->averageRating(); @endphp
                            @if($rating)
                                <span class="d-flex align-items-center gap-1 text-xs fw-semibold">
                                    <i class="bi bi-star-fill text-warning" style="font-size:.7rem;"></i>{{ number_format($rating,1) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-3">{{ $offices->links() }}</div>
@endif
@push('scripts')
@if($mapOffices->isNotEmpty())
<script>
    window.officeLocations = @json($mapOffices);
</script>

<script>
    function initCitizenOfficesMap() {
        const offices = window.officeLocations || [];
        if (!offices.length) return;

        const firstOffice = offices[0];

        const map = new google.maps.Map(document.getElementById('officesMap'), {
            center: { lat: firstOffice.latitude, lng: firstOffice.longitude },
            zoom: 11,
        });

        const bounds = new google.maps.LatLngBounds();
        const infoWindow = new google.maps.InfoWindow();

        offices.forEach((office) => {
            const marker = new google.maps.Marker({
                position: { lat: office.latitude, lng: office.longitude },
                map,
                title: office.name,
            });

            bounds.extend({ lat: office.latitude, lng: office.longitude });

            marker.addListener('click', () => {
                infoWindow.setContent(`
                    <div style="min-width:220px;max-width:260px;padding:4px 2px;">
                        <div style="font-weight:800;font-size:14px;color:#111827;margin-bottom:4px;">
                            ${office.name}
                        </div>
                        <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">
                            ${office.municipality ?? ''}
                        </div>
                        <div style="font-size:12px;color:#374151;line-height:1.4;margin-bottom:8px;">
                            ${office.address ?? ''}
                        </div>
                        <div style="font-size:12px;color:#374151;margin-bottom:8px;">
                            ${office.services_count} services
                        </div>
                        <a href="${office.show_url}" style="display:inline-block;padding:6px 10px;background:#0038a8;color:#fff;text-decoration:none;border-radius:8px;font-size:12px;">
                            View Office
                        </a>
                    </div>
                `);

                infoWindow.open(map, marker);
            });
        });

        if (offices.length > 1) {
            map.fitBounds(bounds);
        }
    }
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initCitizenOfficesMap">
</script>
@endif
@endpush
@endsection
