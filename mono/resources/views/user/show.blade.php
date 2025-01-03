@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>User Details</h1>
        <div class="card">
            <div class="card-body">
                <p><strong>Nama:</strong> {{ $user->nama }}</p>
                <p><strong>Status:</strong> {{ $user->status }}</p>
                @if ($user->foto)
                    <p><strong>Foto:</strong> <img src="{{ asset('storage/' . $user->foto) }}" alt="User Foto" class="img-fluid"></p>
                @endif
                <a href="{{ route('user.index') }}" class="btn btn-secondary">Back to Users</a>
            </div>
        </div>
    </div>
@endsection
