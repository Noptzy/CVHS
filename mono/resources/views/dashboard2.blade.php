<p> halo mas {{ Auth::user()->name }} <br> </p>

<img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="foto user">

<h3>
    <a href="/logout">aku pergi</a>
</h3>

<a href="">Kendali Muani</a>
<a href="/camera">Kendali Camera</a>
<a href="{{ route('profile.edit') }}">edit profile</a>