<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div>
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required autofocus>
    </div>
    <div>
        <button type="submit">Send Password Reset Link</button>
    </div>
</form>

@if (session('status'))
    <div>
        {{ session('status') }}
    </div>
@endif

<a href="{{ route('login') }}">Back to Login</a>