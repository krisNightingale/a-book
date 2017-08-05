<h1>Logout</h1>

@if(auth()->check())
    {{auth()->user()->name}}
@endif