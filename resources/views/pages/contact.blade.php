@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.contact.title') }}</h1>
            <p>{{ __('site.contact.subtitle') }}</p>
        </div>

        <section class="section">
            <div class="contact-grid">
                <div class="card contact-card">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
                    </div>
                    <h3>{{ __('site.contact.location') }}</h3>
                    <p>East Shewrapara, Begum Rokeya Sarani,<br>Mirpur, Dhaka - 1216, Bangladesh</p>
                </div>
                <div class="card contact-card">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M2 3a1 1 0 011-1h3.28a1 1 0 01.95.68l1.52 4.55a1 1 0 01-.27 1.03l-2.1 2.1a16 16 0 006.95 6.95l2.1-2.1a1 1 0 011.03-.27l4.55 1.52a1 1 0 01.68.95V21a1 1 0 01-1 1H19c-9.39 0-17-7.61-17-17V4a1 1 0 011-1z"/></svg>
                    </div>
                    <h3>{{ __('site.contact.phone') }}</h3>
                    <p><a href="tel:01674297522" class="text-link">01674297522</a></p>
                </div>
                <div class="card contact-card">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18a2 2 0 002 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    </div>
                    <h3>{{ __('site.contact.email') }}</h3>
                    <p><a href="mailto:info@rcb.com.bd" class="text-link">info@rcb.com.bd</a></p>
                </div>
                <div class="card contact-card">
                    <div class="contact-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M16.72 14.47c-.27-.13-1.59-.78-1.84-.87-.25-.1-.43-.13-.62.13-.18.27-.72.87-.88 1.05-.16.18-.32.2-.59.07-.27-.13-1.13-.42-2.15-1.35-.79-.71-1.32-1.58-1.48-1.85-.15-.27-.02-.41.11-.54.12-.12.27-.32.41-.48.14-.16.18-.27.27-.45.09-.18.05-.34-.02-.47-.07-.13-.62-1.5-.85-2.05-.22-.53-.45-.46-.62-.47h-.53c-.18 0-.48.07-.73.34s-.95.93-.95 2.27 1 2.64 1.14 2.82c.14.18 1.96 3 4.75 4.2.66.29 1.18.46 1.58.59.66.21 1.26.18 1.74.11.53-.08 1.59-.65 1.81-1.28.22-.63.22-1.18.15-1.28-.06-.1-.25-.16-.52-.29z"/><path d="M20.52 3.48A11.94 11.94 0 0012 0a12 12 0 00-10.6 17.4L0 24l6.73-1.75A11.97 11.97 0 0012 24c6.63 0 12-5.37 12-12 0-3.2-1.24-6.21-3.48-8.52z"/></svg>
                    </div>
                    <h3>{{ __('site.contact.whatsapp') }}</h3>
                    <p><a href="https://wa.link/c1y139" target="_blank" rel="noopener" class="text-link">{{ __('site.contact.chat_with_us') }}</a></p>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="card about-card">
                <h2>{{ __('site.be_a_player_page.title') }}</h2>
                <p>{{ __('site.contact.join_before') }}
                <a href="{{ route('be-a-player.create') }}" class="text-link">{{ __('site.be_a_player') }}</a> {{ __('site.contact.join_after') }}</p>
            </div>
        </section>

        <section class="section">
            <div class="card about-card">
                <h2>{{ __('site.contact.follow_us') }}</h2>
                <div class="socials" style="margin-top:.75rem;">
                    <a href="https://www.facebook.com/realchallengerboys" target="_blank" rel="noopener" class="social-fb" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2v-3h2v-2.3c0-2 1.2-3.1 3-3.1.9 0 1.8.2 1.8.2v2h-1c-1 0-1.3.6-1.3 1.2V12h2.6l-.4 3h-2.2v7A10 10 0 0 0 22 12z"/></svg>
                    </a>
                    <a href="https://x.com/rcb_2004" target="_blank" rel="noopener" class="social-tw" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.59 8.59 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.14 12.14 0 0 1 3.16 4.9a4.28 4.28 0 0 0 1.33 5.7A4.24 4.24 0 0 1 2.8 10v.05a4.28 4.28 0 0 0 3.44 4.19 4.28 4.28 0 0 1-1.92.07 4.28 4.28 0 0 0 4 2.97A8.58 8.58 0 0 1 2 19.54 12.09 12.09 0 0 0 8.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.36-.02-.54A8.36 8.36 0 0 0 22.46 6z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/rcb.com.bd" target="_blank" rel="noopener" class="social-ig" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm10 2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h10zm-5 3.3A4.7 4.7 0 1 0 16.7 12 4.7 4.7 0 0 0 12 7.3zm0 7.7A3 3 0 1 1 15 12a3 3 0 0 1-3 3zm4.8-7.9a1.1 1.1 0 1 1-1.1-1.1c.6 0 1.1.5 1.1 1.1z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@realchallengerboys" target="_blank" rel="noopener" class="social-yt" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6zm12-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
