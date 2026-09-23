@extends('layouts.admin')
@section('title', 'Live GPS Tracking - OneStall Cargo')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Live Fleet Tracking</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time GPS locations of all active Delivery and Pickup Riders.</p>
        </div>
        <div class="flex gap-2">
            <span class="px-3 py-1.5 bg-green-100 text-green-800 text-xs font-bold rounded-lg border border-green-200"><i class="fa-solid fa-circle text-[8px] animate-pulse mr-1"></i> Live Connection Active</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Active Riders List -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="font-extrabold text-gray-800 text-sm"><i class="fa-solid fa-motorcycle mr-2"></i> Active Riders</h2>
                </div>
                <div class="p-0 divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                    @forelse($activeRiders as $rider)
                    <div class="p-4 hover:bg-gray-50 cursor-pointer transition">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-gray-900 text-sm">{{ $rider->name }}</h3>
                            <span class="text-[10px] bg-green-50 text-green-700 px-2 py-0.5 rounded font-bold">Live</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">Role: {{ ucfirst(str_replace('_', ' ', $rider->role)) }}</p>
                        <div class="text-xs text-gray-400"><i class="fa-solid fa-clock text-green-500 mr-1"></i> Updated: {{ $rider->last_location_at ? \Carbon\Carbon::parse($rider->last_location_at)->diffForHumans() : 'Just now' }}</div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">
                        <i class="fa-solid fa-location-dot text-3xl mb-2"></i>
                        <p class="text-sm">No riders currently active on duty.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- The Map (Leaflet.js integration for mock tracking) -->
        <div class="lg:col-span-3">
            <div class="bg-white p-2 rounded-3xl border border-gray-200 shadow-sm h-[600px] relative">
                <!-- Leaflet CSS & JS -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                
                <div id="fleetMap" class="w-full h-full rounded-2xl z-0"></div>

                <!-- Map Setup Script -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Initialize map centered roughly on India/Mumbai
                        var map = L.map('fleetMap').setView([19.0760, 72.8777], 11);

                        // Add OpenStreetMap tiles (free, no API key needed)
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        // Custom Marker Icon for Riders
                        var riderIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: "<div style='background-color:#1e293b;width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FFD700;border:2px solid white;box-shadow:0 4px 6px rgba(0,0,0,0.3);'><i class='fa-solid fa-motorcycle text-xs'></i></div>",
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        });

                        // Dynamic Rider Locations from Database
                        var riders = @json($activeRiders);

                        if(riders.length === 0) {
                            // Default view if no active riders
                            map.setView([19.0760, 72.8777], 11);
                        } else {
                            // Center on the first rider
                            map.setView([riders[0].latitude, riders[0].longitude], 12);
                            
                            // Add markers
                            riders.forEach(function(rider) {
                                L.marker([rider.latitude, rider.longitude], {icon: riderIcon})
                                 .addTo(map)
                                 .bindPopup("<b>" + rider.name + "</b><br>Role: " + rider.role + "<br>Last updated: " + (rider.last_location_at || 'Just now'));
                            });
                        }
                    });
                </script>
            </div>
        </div>

    </div>
</div>
@endsection
