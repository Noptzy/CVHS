<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>edit user</title>
</head>

<body>
    <div class="container">
        <h2>edit profil</h2>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password Baru (Opsional)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label for="foto">Foto Profil (Opsional)</label>
            <input type="file" name="foto" id="foto" class="form-control">
        </div>

        <div class="form-group">
            <label>Preview Foto</label>
            <img id="preview" src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil"
                style="width: 150px; height: 150px; border-radius: 50%;" />
        </div>
        <button type="submit" class="btn btn-primary">Perbarui Profil</button>
    </form>

    <script>

        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
