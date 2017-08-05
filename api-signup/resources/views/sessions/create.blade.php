<h2>Sign in</h2>

<form method="POST" action="/login">
    {{csrf_field()}}

    <br>
    <label for="email">Email</label>
    <input id="email" name="email" type="email" required>

    <br>
    <label for="password">Password</label>
    <input id="password" name="password" type="password" required>

    <br>
    <button type="submit">Sign in</button>

    <br>
</form>

@include('layouts.errors')