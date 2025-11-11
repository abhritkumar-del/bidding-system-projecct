@extends('layouts.app')

@section('title', 'My Bids - Deshi Bid')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">
            <i class="fas fa-list"></i> My Bids
        </h2>
        <p class="text-muted">Track all your bidding activity</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Bid Amount</th>
                                <th>Status</th>
                                <th>Bid Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bids as $bid)
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($bid->auction->product->name, 40) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $bid->auction->product->category->name }}</small>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        ৳{{ number_format($bid->amount, 2) }}
                                    </td>
                                    <td>
                                        @if($bid->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($bid->status == 'won')
                                            <span class="badge bg-primary">Won</span>
                                        @elseif($bid->status == 'outbid')
                                            <span class="badge bg-warning">Outbid</span>
                                        @else
                                            <span class="badge bg-secondary">Lost</span>
                                        @endif
                                    </td>
                                    <td>{{ $bid->created_at->format('d M, Y h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('auctions.show', $bid->auction) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> No bids yet
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $bids->links() }}
            </div>
        </div>
    </div>
</div>
@endsection