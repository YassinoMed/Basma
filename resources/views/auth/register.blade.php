@extends('layouts.app')

@section('title', 'Inscription - FrenchVerbs')

@section('content')
<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-user-plus"></i>
        <span>Compte</span>
    </div>
    <h1 class="section-title">Inscription</h1>
    <p class="section-description">Créez un compte pour accéder à l’administration.</p>
</div>

@if ($errors->any())
<div class="alert alert-danger" role="alert">
    <i class="ph ph-warning-circle"></i>
    <div>
        <div style="font-weight: 600;">Veuillez corriger les champs en erreur.</div>
        <ul style="margin: 8px 0 0 18px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('register') }}" method="POST" class="form-card form-card-wide" style="max-width: 560px;">
    @csrf

    <div class="form-group">
        <label class="form-label" for="name">Nom *</label>
        <input type="text" class="form-input" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
    </div>

    <div class="form-group">
        <label class="form-label" for="email">Email *</label>
        <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label" for="password">Mot de passe *</label>
            <input type="password" class="form-input" id="password" name="password" required autocomplete="new-password">
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmation *</label>
            <input type="password" class="form-input" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('cards.index') }}" class="btn btn-secondary">
            <i class="ph ph-arrow-left"></i>
            Retour
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="ph ph-check"></i>
            Créer le compte
        </button>
    </div>

    <div style="margin-top: 16px; text-align: center;">
        <a href="{{ route('login') }}">Déjà un compte ? Se connecter</a>
    </div>
</form>
@endsection
