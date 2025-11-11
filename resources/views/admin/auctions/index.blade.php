@extends('layouts.app')

@section('title', 'Manage Auctions - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-gavel"></i> Manage Auctions
        </h2>
        <p class="text-muted">Monitor and manage all auctions</p>
    </div>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
           href="{{ route('admin.auctions.index') }}">
            All Auctions
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}" 
           href="{{ route('admin.auctions.index', ['status' => 'active']) }}">
            Active
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'scheduled' ? 'active' : '' }}" 
           href="{{ route('admin.auctions.index', ['status' => 'scheduled']) }}">
            Scheduled
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'ended' ? 'active' : '' }}" 
           href="{{ route('admin.auctions.index', ['status' => 'ended']) }}">
            Ended
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Seller</th>
                        <th>Current Price</th>
                        <th>Bids</th>
                        <th>Status</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auctions as $auction)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($auction->product->name, 30) }}</strong>
                            </td>
                            <td>{{ $auction->product->user->name }}</td>
                            <td class="fw-bold text-success">
                                ৳{{ number_format($auction->current_price > 0 ? $auction->current_price : $auction->product->starting_price, 2) }}
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $auction->total_bids }}</span>
                            </td>
                            <td>
                                @if($auction->status == 'scheduled')
                                    <span class="badge bg-info">Scheduled</span>
                                @elseif($auction->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($auction->status == 'ended')
                                    <span class="badge bg-secondary">Ended</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $auction->start_time->format('d M, Y') }}</td>
                            <td>{{ $auction->end_time->format('d M, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.auctions.show', $auction) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$auction->hasEnded())
                                        <form action="{{ route('admin.auctions.cancel', $auction) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger"
                                                    onclick="return confirm('Cancel this auction?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                                <p>No auctions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $auctions->links() }}
        </div>
    </div>
</div>
@endsection