@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Active Pincodes</h5>
                    <h2>{{ $totalActive }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>OneStall Covered Pincodes</h5>
                    <h2>{{ $onestallCovered }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>External-Only Pincodes</h5>
                    <h2>{{ $externalOnly }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4>Serviceable Pincodes</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Pincode</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Franchise</th>
                        <th>Status</th>
                        <th>Fulfillment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pincodes as $pin)
                    <tr>
                        <td>{{ $pin->pincode }}</td>
                        <td>{{ $pin->city ?? 'N/A' }}</td>
                        <td>{{ $pin->state ?? 'N/A' }}</td>
                        <td>{{ $pin->franchise ? $pin->franchise->company_name : '—' }}</td>
                        <td>
                            @if($pin->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($pin->franchise_id)
                                OneStall Cargo
                            @else
                                External Provider
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $pincodes->links() }}
        </div>
    </div>
</div>
@endsection
