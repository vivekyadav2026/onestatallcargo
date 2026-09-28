@extends('layouts.public')
@section('title', 'Resolve Delivery Issue')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <h4 class="mb-3">Delivery Exception (NDR)</h4>
                    <p>AWB: <strong>{{ $shipment->awb_number }}</strong></p>
                    <div class="alert alert-warning">
                        Our rider attempted delivery but encountered an issue: <strong>{{ $shipment->ndr_reason }}</strong>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @else
                        <p class="mb-4">Please let us know how you would like us to proceed:</p>
                        <form method="POST" action="{{ route('ndr.resolve.submit', $shipment->awb_number) }}">
                            @csrf
                            <div class="d-grid gap-3">
                                <button type="submit" name="customer_action" value="reattempt" class="btn btn-primary btn-lg">
                                    Re-attempt Delivery
                                </button>
                                <button type="submit" name="customer_action" value="rto" class="btn btn-outline-danger">
                                    Cancel & Return to Sender
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
