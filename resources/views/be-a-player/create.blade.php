@extends('layouts.app')

@section('title', 'Be a Player')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Be a Player 🏏</h1>
            <p>Want to join REAL CHALLENGER BOYS? Fill in the form and our team will contact you.</p>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('be-a-player.store') }}" method="POST" class="form-grid card">
            @csrf

            <div class="form-field">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="email">Email (optional)</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="age">Age *</label>
                <input type="number" id="age" name="age" value="{{ old('age') }}" min="10" max="60" required>
                @error('age')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="preferred_role">Preferred Role</label>
                <select id="preferred_role" name="preferred_role">
                    <option value="">— Select —</option>
                    @foreach (['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper'] as $role)
                        <option value="{{ $role }}" {{ old('preferred_role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @error('preferred_role')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="message">Message (optional)</label>
                <textarea id="message" name="message" rows="4" placeholder="Tell us about your cricket experience...">{{ old('message') }}</textarea>
                @error('message')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <button type="submit" class="btn btn-primary">SUBMIT APPLICATION</button>
            </div>
        </form>
    </div>
@endsection
