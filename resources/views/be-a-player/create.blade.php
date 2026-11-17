@extends('layouts.app')

@section('title', __('site.be_a_player_page.title'))

@section('content')
    @php
        $oldOld = old();
    @endphp
    <section class="pr-section">
        <div class="pr-blob pr-blob-1"></div>
        <div class="pr-blob pr-blob-2"></div>

        <div class="pr-title">
            <h1><span class="pr-gradient">{{ __('site.be_a_player_page.lets_play') }}</span></h1>
            <span class="pr-underline"></span>
            <p>{{ __('site.be_a_player_page.lets_play_sub') }}</p>
        </div>

        <div class="pr-card">
            @if (session('success'))
                <div class="pr-alert success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('be-a-player.store') }}" enctype="multipart/form-data" class="pr-form">
                @csrf

                <div class="pr-upload-row">
                    <label for="photo" class="pr-upload-area">
                        <span class="pr-upload-ico">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </span>
                        <span class="pr-upload-text">{{ __('site.be_a_player_page.click_upload') }}</span>
                        <span class="pr-upload-hint">{{ __('site.be_a_player_page.supported') }}</span>
                        <input type="file" name="photo" id="photo" accept="image/*">
                    </label>
                    <div class="pr-preview-col">
                        <div class="pr-preview">
                            <img src="{{ $oldOld['photo'] && is_string($oldOld['photo']) ? asset($oldOld['photo']) : asset('images/logo2.png') }}" alt="Preview" id="imagePreview">
                        </div>
                        <p class="pr-preview-label">{{ __('site.be_a_player_page.preview') }}</p>
                    </div>
                </div>

                <div class="pr-section-title">
                    <span></span><h3>{{ __('site.be_a_player_page.personal_info') }}</h3><span></span>
                </div>
                <div class="pr-grid">
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.full_name') }} <em>*</em></label>
                        <div class="pr-input-ico">
                            <span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"/></svg></span>
                            <input type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                        @error('name')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.email') }} <em>*</em></label>
                        <div class="pr-input-ico">
                            <span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                            <input type="email" name="email" value="{{ old('email') }}">
                        </div>
                        @error('email')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.phone') }} <em>*</em></label>
                        <div class="pr-input-ico">
                            <span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg></span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required>
                        </div>
                        @error('phone')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.age') }} <em>*</em></label>
                        <input type="number" name="age" value="{{ old('age') }}" min="10" max="60" required>
                        @error('age')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.dob') }} <em>*</em></label>
                        <input type="date" name="dob" value="{{ old('dob') }}">
                        @error('dob')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.preferred_role') }} <em>*</em></label>
                        <select name="preferred_role" required>
                            <option value="">{{ __('site.select') }}</option>
                            @foreach (['Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper'] as $role)
                                <option value="{{ $role }}" @selected(old('preferred_role') === $role)>{{ __('site.roles.' . str_replace(['-', ' '], '_', strtolower($role))) }}</option>
                            @endforeach
                        </select>
                        @error('preferred_role')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.jersey_size') }} <em>*</em></label>
                        <select name="jersey_size" required>
                            <option value="">{{ __('site.be_a_player_page.select_jersey') }}</option>
                            @foreach (['S/36', 'M/38', 'L/40', 'XL/42', 'XXL/44'] as $size)
                                <option value="{{ $size }}" @selected(old('jersey_size') === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('jersey_size')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="pr-section-title">
                    <span></span><h3>{{ __('site.be_a_player_page.present_address') }}</h3><span></span>
                </div>
                <div class="pr-grid">
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.village') }} <em>*</em></label>
                        <input type="text" name="present_village" value="{{ old('present_village') }}">
                        @error('present_village')<p class="pr-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.district') }} <em>*</em></label>
                        <select name="present_district_id" id="present_district" required>
                            <option value="">{{ __('site.admin.select_district') }}</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" @selected(old('present_district_id') == $district->id)>{{ $district->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.thana') }} <em>*</em></label>
                        <select name="present_thana_id" id="present_thana" required>
                            <option value="">{{ __('site.admin.select_thana') }}</option>
                            @foreach ($thanas as $thana)
                                <option value="{{ $thana->id }}" data-district="{{ $thana->parent_id }}" @selected(old('present_thana_id') == $thana->id)>{{ $thana->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.post_office') }} <em>*</em></label>
                        <input type="text" name="present_post_office" value="{{ old('present_post_office') }}">
                    </div>
                </div>

                <div class="pr-section-title">
                    <span></span><h3>{{ __('site.be_a_player_page.permanent_address') }}</h3><span></span>
                </div>
                <div class="pr-same-check">
                    <input type="checkbox" id="sameAsPresent">
                    <label for="sameAsPresent">{{ __('site.be_a_player_page.same_as_present') }}</label>
                </div>
                <div class="pr-grid">
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.village') }} <em>*</em></label>
                        <input type="text" name="permanent_village" id="permanent_village" value="{{ old('permanent_village') }}">
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.district') }} <em>*</em></label>
                        <select name="permanent_district_id" id="permanent_district">
                            <option value="">{{ __('site.admin.select_district') }}</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" @selected(old('permanent_district_id') == $district->id)>{{ $district->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.thana') }} <em>*</em></label>
                        <select name="permanent_thana_id" id="permanent_thana">
                            <option value="">{{ __('site.admin.select_thana') }}</option>
                            @foreach ($thanas as $thana)
                                <option value="{{ $thana->id }}" data-district="{{ $thana->parent_id }}" @selected(old('permanent_thana_id') == $thana->id)>{{ $thana->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.post_office') }} <em>*</em></label>
                        <input type="text" name="permanent_post_office" id="permanent_post_office" value="{{ old('permanent_post_office') }}">
                    </div>
                </div>

                <div class="pr-grid">
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.nationality') }} <em>*</em></label>
                        <select name="nationality">
                            <option value="Bangladeshi">{{ __('site.be_a_player_page.bangladeshi') }}</option>
                        </select>
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.religion') }} <em>*</em></label>
                        <select name="religion">
                            <option value="">{{ __('site.be_a_player_page.select_religion') }}</option>
                            @foreach (['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Other'] as $opt)
                                <option value="{{ $opt }}" @selected(old('religion') === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.admin.blood_group') }} <em>*</em></label>
                        <select name="blood_group">
                            <option value="">{{ __('site.admin.select_blood_group') }}</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $opt)
                                <option value="{{ $opt }}" @selected(old('blood_group') === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pr-field">
                        <label>{{ __('site.be_a_player_page.height') }} <em>*</em></label>
                        <input type="text" name="height" value="{{ old('height') }}" placeholder="e.g. 5'8&quot;">
                    </div>
                    <div class="pr-field pr-span2">
                        <label>{{ __('site.be_a_player_page.facebook_id') }}</label>
                        <div class="pr-input-ico">
                            <span class="pr-fb"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg></span>
                            <input type="text" name="facebook_id" value="{{ old('facebook_id') }}" placeholder="Facebook ID">
                        </div>
                    </div>
                    <div class="pr-field pr-span2">
                        <label>{{ __('site.be_a_player_page.message') }}</label>
                        <textarea name="message" rows="4">{{ old('message') }}</textarea>
                    </div>
                </div>

                <div class="pr-submit">
                    <button type="submit">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('site.be_a_player_page.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('photo').addEventListener('change', function (e) {
            var file = e.target.files[0];
            var preview = document.getElementById('imagePreview');
            if (file) {
                var reader = new FileReader();
                reader.onload = function (event) { preview.src = event.target.result; };
                reader.readAsDataURL(file);
            }
        });

        function filterThanasByDistrict(districtId, targetId) {
            var target = document.getElementById(targetId);
            if (!target) return;
            Array.prototype.forEach.call(target.options, function (opt) {
                if (!opt.value) return;
                opt.style.display = (!districtId || opt.getAttribute('data-district') === String(districtId)) ? '' : 'none';
            });
            target.value = '';
            Array.prototype.forEach.call(target.options, function (opt) {
                if (opt.value && opt.style.display !== 'none') { target.value = opt.value; }
            });
            if (!districtId) target.value = '';
        }

        document.getElementById('present_district').addEventListener('change', function () {
            filterThanasByDistrict(this.value, 'present_thana');
        });
        document.getElementById('permanent_district').addEventListener('change', function () {
            filterThanasByDistrict(this.value, 'permanent_thana');
        });

        document.getElementById('sameAsPresent').addEventListener('change', function (e) {
            if (e.target.checked) {
                document.querySelector('input[name="permanent_village"]').value = document.querySelector('input[name="present_village"]').value;
                document.getElementById('permanent_district').value = document.getElementById('present_district').value;
                filterThanasByDistrict(document.getElementById('present_district').value, 'permanent_thana');
                setTimeout(function () {
                    document.getElementById('permanent_thana').value = document.getElementById('present_thana').value;
                }, 50);
                document.querySelector('input[name="permanent_post_office"]').value = document.querySelector('input[name="present_post_office"]').value;
            } else {
                document.querySelector('input[name="permanent_village"]').value = '';
                document.getElementById('permanent_district').value = '';
                filterThanasByDistrict('', 'permanent_thana');
                document.querySelector('input[name="permanent_post_office"]').value = '';
            }
        });

        (function () {
            var pd = document.getElementById('present_district');
            if (pd && pd.value) filterThanasByDistrict(pd.value, 'present_thana');
        })();
    </script>
@endpush
