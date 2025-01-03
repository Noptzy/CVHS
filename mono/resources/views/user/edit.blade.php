@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit User</h1>
        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="form-group">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ $user->nama }}" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="dalam rumah" {{ $user->status == 'dalam rumah' ? 'selected' : '' }}>Dalam Rumah</option>
                    <option value="sedang diluar" {{ $user->status == 'sedang diluar' ? 'selected' : '' }}>Sedang Diluar</option>
                </select>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto" class="form-control">
            </div>
            <div class="form-group">
                <label for="fototrain">Foto Train:</label>
                <input type="file" name="fototrain" id="fototrain" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
