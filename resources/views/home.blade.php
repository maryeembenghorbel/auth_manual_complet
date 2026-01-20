@extends('layouts.app')

@section('content')
<div class="home-container">

    <div class="card home-card">
        
        <div class="icon-wrapper">
            <i class="fa-solid fa-circle-user user-icon"></i>
        </div>

        <h1 class="title">
            <i class="fa-solid fa-house"></i> Bienvenue, {{ Auth::user()->name }} 👋
        </h1>

        <p class="subtitle">{{ $message }}</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
           
        </form>

    </div>

</div>
@endsection

<style>
    :root {
        --blue-navy: #0A1A44;
        --burgundy: #7A0A2A;
        --light-bg: #f4f6fa;
    }

    .home-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
        padding: 20px;
        background: var(--light-bg);
    }

    .home-card {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        text-align: center;
        width: 100%;
        max-width: 500px;
        border-top: 6px solid var(--burgundy);
        animation: fadeIn .5s ease-in-out;
    }

    /* Icône ronde en haut */
    .icon-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
    }

    .user-icon {
        font-size: 70px;
        color: var(--blue-navy);
    }

    .title {
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 15px;
        color: var(--blue-navy);
    }

    .subtitle {
        font-size: 18px;
        margin-bottom: 25px;
        color: #333;
    }

    .logout-btn {
        background: var(--burgundy);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: .25s;
    }

    .logout-btn:hover {
        background: #58081f;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
