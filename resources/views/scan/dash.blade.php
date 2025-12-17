<!-- resources/views/scan/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<h1>Bienvenue, Analyste {{ Auth::user()->name }} !</h1>
<p>Vous pouvez consulter les scans de vulnérabilité et les rapports.</p>
@endsection
