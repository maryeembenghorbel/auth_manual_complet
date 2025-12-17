
@extends('layouts.app')

@section('content')
<h1>Bienvenue, {{ Auth::user()->name }} !</h1>
<p>Vous pouvez gérer les utilisateurs, les équipements et les réglages.</p>

@endsection
