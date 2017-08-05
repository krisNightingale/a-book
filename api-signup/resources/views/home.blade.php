
<h1>Welcome!</h1>

@if(auth()->check())
    <h2>Hello, {{auth()->user()->first_name}}</h2>
@endif