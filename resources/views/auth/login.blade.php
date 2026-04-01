@extends('layouts.app')

@section('title', 'Connexion - FrenchVerbs')

@section('content')
<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-sign-in"></i>
        <span>Compte</span>
    </div>
    <h1 class="section-title">Connexion</h1>
    <p class="section-description">Connectez-vous pour accéder à l’administration.</p>
</div>

@if ($errors->any())
<div class="alert alert-danger" role="alert">
    <i class="ph ph-warning-circle"></i>
    <div>
        <div style="font-weight: 600;">Impossible de vous connecter.</div>
        <ul style="margin: 8px 0 0 18px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('login') }}" method="POST" class="form-card form-card-wide" style="max-width: 560px;">
    @csrf

    <div class="form-group">
        <label class="form-label" for="email">Email *</label>
        <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
    </div>

    <div class="form-group">
        <label class="form-label" for="password">Mot de passe *</label>
        <input type="password" class="form-input" id="password" name="password" required autocomplete="current-password">
    </div>

    <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
        <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember" style="margin: 0;">Se souvenir de moi</label>
    </div>

    <div class="form-actions">
        <a href="{{ route('cards.index') }}" class="btn btn-secondary">
            <i class="ph ph-arrow-left"></i>
            Retour
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="ph ph-check"></i>
            Se connecter
        </button>
    </div>

</form>
@endsection
