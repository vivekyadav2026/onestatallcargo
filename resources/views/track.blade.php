<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Shipment - OneStall Cargo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <main class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Track Your Parcel</h1>
        <div class="bg-white rounded-3xl p-4 mb-8">
            <form action="{{ route('track.post') }}" method="POST" class="flex">
                @csrf
                <input type="text" name="awb_number" required placeholder="Enter AWB" class="w-full px-4 border rounded-l-lg outline-none">
                <button type="submit" class="px-8 py-3 bg-yellow-400 font-bold rounded-r-lg">Track</button>
            </form>
        </div>

        @if(isset($shipment))
            <div class="bg-white rounded-3xl p-8 border">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold">{{ $shipment->awb_number }}</h2>
                    <span class="px-4 py-2 bg-gray-800 text-white rounded">{{ $shipment->status }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>To:</strong> {{ $shipment->receiver_name }}, {{ $shipment->delivery_city }}</div>
                    <div><strong>From:</strong> {{ $shipment->user->name }}</div>
                </div>
            </div>
        @endif
    </main>
</body>
</html>
