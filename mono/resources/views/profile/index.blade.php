<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>index user</title>
</head>

<body>
    <div class="container">
        <h2>Profil Saya</h2>

        <p>Nama: {{ Auth::user()->name }}</p>
        <p>Email: {{ Auth::user()->email }}</p>

        <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" style="width: 150px; height: 150px;">

        <a href="{{ route('profile.edit') }}" class="btn btn-warning">Edit Profil</a>
    </div>
</body>

</html>
