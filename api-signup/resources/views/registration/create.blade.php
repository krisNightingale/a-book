<h1>Register</h1>

<form method="post" action="/v1/register">
    {{csrf_field()}}

    <br>
    <label for="first_name">First Name</label>
    <input id="first_name" name="first_name" type="text">

    <br>
    <label for="last_name">Last Name</label>
    <input id="last_name" name="last_name" type="text">

    <br>
    <label for="email">Email</label>
    <input id="email" name="email" type="email" required>

    <br>
    <label for="password">Password</label>
    <input id="password" name="password" type="password" required>

    <br>
    <label for="password_confirmation">Password confirmation</label>
    <input id="password_confirmation" name="password_confirmation" type="password">

    <br>
    <button type="submit">Register</button>

    <br>
    @include('layouts.errors')
</form>