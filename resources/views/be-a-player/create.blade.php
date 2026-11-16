@extends('layouts.app')

@section('title', 'Be a Player')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.be_a_player_page.title') }}</h1>
            <p>{{ __('site.be_a_player_page.subtitle') }}</p>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('be-a-player.store') }}" method="POST" class="form-grid card">
            @csrf

            <div class="form-field">
                <label for="name">{{ __('site.be_a_player_page.full_name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="phone">{{ __('site.be_a_player_page.phone') }}</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="email">{{ __('site.be_a_player_page.email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="age">{{ __('site.be_a_player_page.age') }}</label>
                <input type="number" id="age" name="age" value="{{ old('age') }}" min="10" max="60" required>
                @error('age')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="preferred_role">{{ __('site.be_a_player_page.preferred_role') }}</label>
                <select id="preferred_role" name="preferred_role">
                    <option value="">{{ __('site.select') }}</option>
                    @foreach (['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper'] as $role)
                        <option value="{{ $role }}" {{ old('preferred_role') === $role ? 'selected' : '' }}>{{ __('site.roles.' . str_replace(['-', ' '], '_', strtolower($role))) }}</option>
                    @endforeach
                </select>
                @error('preferred_role')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="message">{{ __('site.be_a_player_page.message') }}</label>
                <textarea id="message" name="message" rows="4" placeholder="{{ __('site.be_a_player_page.message') }}">{{ old('message') }}</textarea>
                @error('message')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <button type="submit" class="btn btn-primary">{{ __('site.be_a_player_page.submit') }}</button>
            </div>
        </form>
    </div>
@endsection
