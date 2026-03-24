@extends('layouts.app')
@section('title','Browse Services')
@section('page-title','Browse Offices')

@section('content')

{{-- Search --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:.6rem">
            <div style="flex:1;min-width:180px;position:relative">
                <i class="bi bi-search" style="position:absolute;left:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none"></i>
                <input type="text" name="search" class="form-control" style="padding-left:2.3rem" placeholder="Search offices by name..." value="{{ request('search') }}">
            </div>
            <select name="municipality_id" class="form-select" style="min-width:160px">
                <option value="">All Municipalities</option>
                @foreach(\App\Models\Municipality::where('is_active',true)->get() as $m)
                    <option value="{{ $m->id }}" {{ request('municipality_id')==$m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
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
<div style="text-align:center;padding:3rem 1rem;color:#9ca3af;background:#fff;border-radius:14px;border:1px solid #e5eaf0">
    <i class="bi bi-building-x" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db"></i>
    <div style="font-weight:600;font-size:.9rem">No offices found</div>
    <a href="{{ route('citizen.offices') }}" style="color:var(--primary);font-size:.82rem">Clear filters</a>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
    @foreach($offices as $office)
    <a href="{{ route('citizen.offices.show', $office) }}" style="text-decoration:none;color:inherit;display:block">
        <div style="background:#fff;border-radius:14px;border:1px solid #e5eaf0;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden;transition:transform .2s,box-shadow .2s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow='0 1px 3px rgba(0,0,0,.06)'">
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#0038a8,#0070f3);padding:1.25rem;display:flex;align-items:center;gap:.85rem">
                @if($office->logo)
                <img src="{{ Storage::url($office->logo) }}" alt="{{ $office->name }}" style="width:44px;height:44px;border-radius:10px;object-fit:cover;border:2px solid rgba(255,255,255,.3)">
                @else
                <div style="width:44px;height:44px;border-radius:10px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;flex-shrink:0">
                    <i class="bi bi-building"></i>
                </div>
                @endif
                <div style="flex:1;min-width:0">
                    <div style="color:#fff;font-weight:800;font-size:.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $office->name }}</div>
                    <div style="color:rgba(255,255,255,.65);font-size:.73rem">{{ $office->municipality->name }}</div>
                </div>
            </div>
            {{-- Body --}}
            <div style="padding:1rem">
                @if($office->address)
                <div style="display:flex;align-items:flex-start;gap:.5rem;margin-bottom:.5rem">
                    <i class="bi bi-geo-alt" style="color:#9ca3af;font-size:.85rem;margin-top:1px;flex-shrink:0"></i>
                    <span style="font-size:.78rem;color:#6b7280;line-height:1.4">{{ $office->address }}</span>
                </div>
                @endif
                @if($office->phone)
                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem">
                    <i class="bi bi-telephone" style="color:#9ca3af;font-size:.82rem;flex-shrink:0"></i>
                    <span style="font-size:.78rem;color:#6b7280">{{ $office->phone }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.75rem;padding-top:.75rem;border-top:1px solid #f3f4f6">
                    <span style="font-size:.75rem;color:#6b7280"><i class="bi bi-grid-3x3-gap me-1"></i>{{ $office->services->count() }} services</span>
                    @php $rating = $office->averageRating(); @endphp
                    @if($rating)
                    <span style="display:flex;align-items:center;gap:.25rem;font-size:.75rem;font-weight:600;color:#374151">
                        <i class="bi bi-star-fill" style="color:#f59e0b;font-size:.7rem"></i>{{ number_format($rating,1) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
<div style="margin-top:1rem">{{ $offices->links() }}</div>
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
