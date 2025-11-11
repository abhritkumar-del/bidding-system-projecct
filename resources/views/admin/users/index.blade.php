@extends('layouts.app')

@section('title', 'User Management - Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold mb-0" style="color: #1f2937;">
            <i class="fas fa-users"></i> User Management
        </h2>
        <p class="text-muted">Manage all users on the platform</p>
    </div>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ !request('role') ? 'active' : '' }}" 
           href="{{ route('admin.users.index') }}">
            All Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('role') == 'seller' ? 'active' : '' }}" 
           href="{{ route('admin.users.index', ['role' => 'seller']) }}">
            Sellers
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('role') == 'bidder' ? 'active' : '' }}" 
           href="{{ route('admin.users.index', ['role' => 'bidder']) }}">
            Bidders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
           href="{{ route('admin.users.index', ['status' => 'pending']) }}">
            Pending <span class="badge bg-warning">{{ \App\Models\User::where('status', 'pending')->count() }}</span>
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Products</th>
                        <th>Bids</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role == 'seller')
                                    <span class="badge bg-primary">Seller</span>
                                @else
                                    <span class="badge bg-info">Bidder</span>
                                @endif
                            </td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($user->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Banned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $user->products_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $user->bids_count }}</span>
                            </td>
                            <td>{{ $user->created_at->format('d M, Y') }}</td>
                            <td>
                                @if(!$user->isAdmin())
                                    <div class="btn-group btn-group-sm">
                                        @if($user->status == 'pending')
                                            <form action="{{ route('admin.users.approve', $user) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success"
                                                        onclick="return confirm('Approve this user?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($user->status != 'banned')
                                            <form action="{{ route('admin.users.ban', $user) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger"
                                                        onclick="return confirm('Ban this user?')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.unban', $user) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success"
                                                        onclick="return confirm('Unban this user?')">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">Admin</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p>No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection