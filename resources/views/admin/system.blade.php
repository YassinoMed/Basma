@extends('layouts.app')

@section('title', 'Système - Administration')

@section('content')
<div class="section-header">
    <div class="section-badge">
        <i class="ph ph-gear"></i>
        <span>Administration</span>
    </div>
    <h1 class="section-title">Système</h1>
    <p class="section-description">Statut et actions d’administration</p>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        <i class="ph ph-warning-circle"></i>
        {{ session('error') }}
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="ph ph-x"></i>
        </button>
    </div>
@endif

<div class="form-card form-card-wide">
    <div class="form-group">
        <div class="form-label">Statut</div>
        <div>
            <div><strong>Application:</strong> {{ $system['app_name'] }}</div>
            <div><strong>Environnement:</strong> {{ $system['app_env'] }}</div>
            <div><strong>Debug:</strong> {{ $system['app_debug'] ? 'on' : 'off' }}</div>
            <div><strong>Laravel:</strong> {{ $system['laravel_version'] }}</div>
            <div><strong>PHP:</strong> {{ $system['php_version'] }}</div>
            <div><strong>DB:</strong> {{ $system['database_connection'] }} — {{ $system['db_ok'] ? 'OK' : 'KO' }}</div>
            @if(!$system['db_ok'] && !empty($system['db_error']))
                <div class="mt-4 opacity-80">{{ $system['db_error'] }}</div>
            @endif
            <div><strong>Cache:</strong> {{ $system['cache_driver'] }}</div>
            <div><strong>Queue:</strong> {{ $system['queue_connection'] }}</div>
            <div><strong>Session:</strong> {{ $system['session_driver'] }}</div>
            <div><strong>Storage writable:</strong> {{ $system['storage_writable'] ? 'OK' : 'KO' }}</div>
            <div><strong>Logs writable:</strong> {{ $system['logs_writable'] ? 'OK' : 'KO' }}</div>
            <div class="mt-4">
                <strong>Health:</strong>
                <a href="{{ url('/up') }}">/up</a>
                ·
                <a href="{{ url('/health') }}">/health</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-label">Actions</div>
        <div class="flex flex-wrap gap-4">
            <form method="POST" action="{{ route('admin.system.action') }}">
                @csrf
                <input type="hidden" name="action" value="theme_cache_clear">
                <button type="submit" class="btn btn-secondary">Vider cache thème</button>
            </form>

            <form method="POST" action="{{ route('admin.system.action') }}">
                @csrf
                <input type="hidden" name="action" value="optimize_clear">
                <button type="submit" class="btn btn-secondary">optimize:clear</button>
            </form>

            <form method="POST" action="{{ route('admin.system.action') }}">
                @csrf
                <input type="hidden" name="action" value="cache_clear">
                <button type="submit" class="btn btn-secondary">cache:clear</button>
            </form>

            <form method="POST" action="{{ route('admin.system.action') }}">
                @csrf
                <input type="hidden" name="action" value="config_clear">
                <button type="submit" class="btn btn-secondary">config:clear</button>
            </form>

            <form method="POST" action="{{ route('admin.system.action') }}">
                @csrf
                <input type="hidden" name="action" value="route_clear">
                <button type="submit" class="btn btn-secondary">route:clear</button>
            </form>

            <form method="POST" action="{{ route('admin.system.action') }}">
                @csrf
                <input type="hidden" name="action" value="view_clear">
                <button type="submit" class="btn btn-secondary">view:clear</button>
            </form>
        </div>
    </div>
</div>
@endsection
