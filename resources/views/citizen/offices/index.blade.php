@extends('layouts.app')
@section('title', 'Browse Services')
@section('page-title', 'Browse Offices')

@section('content')
<div class="card mb-3 citizen-reveal" data-citizen-reveal>
    <div class="card-body">
        <form method="GET" class="citizen-office-filter-grid">
            <div class="citizen-office-search-wrap">
                <i class="bi bi-search citizen-office-search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="form-control citizen-office-search-input"
                    placeholder="Search offices by name..."
                    value="{{ request('search') }}"
                >
            </div>
            <select name="municipality_id" class="form-select citizen-office-filter-select">
                <option value="">All Municipalities</option>
                @foreach($municipalities as $municipality)
                    <option value="{{ $municipality->id }}" {{ (string) request('municipality_id') === (string) $municipality->id ? 'selected' : '' }}>
                        {{ $municipality->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary citizen-office-filter-btn">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'municipality_id']))
                <a href="{{ route('citizen.offices') }}" class="btn btn-outline-secondary citizen-office-filter-btn">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card mb-3 citizen-reveal" data-citizen-reveal>
    <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
        <span class="card-title"><i class="bi bi-geo-alt me-2 text-primary"></i>Office Locations</span>
        <div class="citizen-office-map-meta">
            <span><strong>{{ $offices->total() }}</strong> offices found</span>
            <span class="mx-2">&middot;</span>
            <span><strong>{{ $mapOffices->count() }}</strong> with map pin</span>
        </div>
    </div>
    <div class="card-body">
        @if($mapOffices->isEmpty())
            <x-empty-state
                icon="bi-geo-alt"
                title="No map locations yet"
                message="No office locations are available to display on the map."
                class="citizen-office-map-empty"
            />
        @else
            <div id="officesMap" class="citizen-office-map"></div>
        @endif
    </div>
</div>

@if($offices->isEmpty())
    <div class="card citizen-reveal" data-citizen-reveal>
        <div class="card-body">
            <x-empty-state
                icon="bi-building-x"
                title="No offices found"
                message="Try another municipality or clear your filters."
                :action-url="route('citizen.offices')"
                action-label="Clear Filters"
            />
        </div>
    </div>
@else
    <div class="citizen-office-grid citizen-reveal" data-citizen-reveal>
        @foreach($offices as $office)
            <a href="{{ route('citizen.offices.show', $office) }}" class="text-decoration-none text-reset d-block">
                <article class="office-card-wrap">
                    <div class="office-card-glow"></div>
                    <div class="office-card-header">
                        @if($office->logo)
                            <img src="{{ Storage::url($office->logo) }}" alt="{{ $office->name }}" class="office-card-logo">
                        @else
                            <div class="office-card-logo-placeholder">
                                <i class="bi bi-building"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1 citizen-min-w-0">
                            <h3 class="office-card-name">{{ $office->name }}</h3>
                            <div class="office-card-municipality">{{ $office->municipality->name }}</div>
                        </div>
                    </div>

                    <div class="office-card-body">
                        @if($office->address)
                            <div class="office-card-meta">
                                <i class="bi bi-geo-alt office-card-meta-icon"></i>
                                <span class="office-card-meta-text">{{ $office->address }}</span>
                            </div>
                        @endif
                        @if($office->phone)
                            <div class="office-card-meta">
                                <i class="bi bi-telephone office-card-meta-icon"></i>
                                <span class="office-card-meta-text">{{ $office->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <footer class="office-card-footer">
                        <span class="office-card-chip">
                            <i class="bi bi-grid-3x3-gap me-1"></i>{{ $office->services->count() }} services
                        </span>
                        @php $rating = $office->averageRating(); @endphp
                        @if($rating)
                            <span class="office-card-chip is-rating">
                                <i class="bi bi-star-fill"></i>{{ number_format($rating, 1) }}
                            </span>
                        @endif
                    </footer>
                </article>
            </a>
        @endforeach
    </div>
    <div class="mt-3 citizen-reveal" data-citizen-reveal>{{ $offices->links() }}</div>
@endif
@endsection

@push('styles')
<style>
body.es-role-citizen .citizen-office-filter-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 14rem auto auto;
    gap: .64rem;
    align-items: center;
}

body.es-role-citizen .citizen-office-search-wrap {
    position: relative;
}

body.es-role-citizen .citizen-office-search-icon {
    position: absolute;
    left: .8rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: .85rem;
    pointer-events: none;
}

body.es-role-citizen .citizen-office-search-input {
    padding-left: 2.25rem;
}

body.es-role-citizen .citizen-office-filter-select,
body.es-role-citizen .citizen-office-filter-btn {
    height: 2.45rem;
}

body.es-role-citizen .citizen-office-map-meta {
    color: #64748B;
    font-size: .76rem;
}

body.es-role-citizen .citizen-office-map {
    width: 100%;
    height: 26rem;
    border-radius: .95rem;
    overflow: hidden;
    border: 1px solid #CBD5E1;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.34), 0 10px 22px rgba(15, 23, 42, 0.08);
}

body.es-role-citizen .citizen-office-map-empty {
    padding-top: 1.2rem;
    padding-bottom: 1.2rem;
}

body.es-role-citizen .citizen-office-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

body.es-role-citizen .office-card-wrap {
    position: relative;
    overflow: hidden;
    border-radius: .95rem;
    border: 1px solid color-mix(in srgb, var(--es-border) 72%, #BFDBFE 28%);
    background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFF 100%);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

body.es-role-citizen .office-card-wrap:hover {
    transform: translateY(-3px);
    border-color: #93C5FD;
    box-shadow: 0 16px 30px rgba(37, 99, 235, 0.14);
}

body.es-role-citizen .office-card-glow {
    position: absolute;
    top: -46%;
    right: -24%;
    width: 9rem;
    height: 9rem;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(14, 165, 233, 0.22) 0%, rgba(14, 165, 233, 0) 72%);
    pointer-events: none;
}

body.es-role-citizen .office-card-header {
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: 1rem 1rem .72rem;
}

body.es-role-citizen .office-card-logo,
body.es-role-citizen .office-card-logo-placeholder {
    width: 2.8rem;
    height: 2.8rem;
    border-radius: .72rem;
    flex-shrink: 0;
}

body.es-role-citizen .office-card-logo {
    object-fit: cover;
    border: 1px solid #DBEAFE;
}

body.es-role-citizen .office-card-logo-placeholder {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0369A1;
    background: #E0F2FE;
    border: 1px solid #BAE6FD;
    font-size: 1rem;
}

body.es-role-citizen .citizen-min-w-0 {
    min-width: 0;
}

body.es-role-citizen .office-card-name {
    margin: 0;
    font-size: .88rem;
    font-weight: 800;
    line-height: 1.2;
    color: #0F172A;
}

body.es-role-citizen .office-card-municipality {
    margin-top: .2rem;
    font-size: .74rem;
    color: #64748B;
}

body.es-role-citizen .office-card-body {
    padding: 0 1rem .82rem;
}

body.es-role-citizen .office-card-meta {
    display: flex;
    align-items: flex-start;
    gap: .5rem;
    margin-top: .46rem;
}

body.es-role-citizen .office-card-meta-icon {
    color: #0369A1;
    font-size: .82rem;
    margin-top: .12rem;
}

body.es-role-citizen .office-card-meta-text {
    color: #475569;
    font-size: .76rem;
    line-height: 1.45;
}

body.es-role-citizen .office-card-footer {
    border-top: 1px solid #E2E8F0;
    padding: .72rem 1rem .84rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
    flex-wrap: wrap;
}

body.es-role-citizen .office-card-chip {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    font-size: .7rem;
    font-weight: 700;
    color: #0F766E;
    background: #ECFEFF;
    border: 1px solid #A5F3FC;
    border-radius: 999px;
    padding: .18rem .5rem;
}

body.es-role-citizen .office-card-chip.is-rating {
    color: #92400E;
    background: #FEF3C7;
    border-color: #FDE68A;
}

body.es-role-citizen .office-card-chip.is-rating i {
    color: #B45309;
}

@media (max-width: 991.98px) {
    body.es-role-citizen .citizen-office-filter-grid {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    }
}

@media (max-width: 767.98px) {
    body.es-role-citizen .citizen-office-filter-grid {
        grid-template-columns: 1fr;
    }

    body.es-role-citizen .citizen-office-filter-btn {
        width: 100%;
    }

    body.es-role-citizen .citizen-office-map {
        height: 22rem;
    }
}
</style>
@endpush

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
                        <div style="font-weight:800;font-size:14px;color:#0f172a;margin-bottom:4px;">
                            ${office.name}
                        </div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:6px;">
                            ${office.municipality ?? ''}
                        </div>
                        <div style="font-size:12px;color:#334155;line-height:1.4;margin-bottom:8px;">
                            ${office.address ?? ''}
                        </div>
                        <div style="font-size:12px;color:#334155;margin-bottom:8px;">
                            ${office.services_count} services
                        </div>
                        <a href="${office.show_url}" style="display:inline-block;padding:6px 10px;background:#0ea5e9;color:#fff;text-decoration:none;border-radius:8px;font-size:12px;">
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
