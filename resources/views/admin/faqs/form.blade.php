@extends('admin.layout')

@section('title', $faq->exists ? __('site.admin.edit') . ' — ' . $faq->display_question : __('site.admin.faq'))

@section('content')
<form method="POST" class="admin-form"
      action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
    @csrf
    @if ($faq->exists)
        @method('PUT')
    @endif

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.question') }} *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required>
        </div>
        <div>
            <label>{{ __('site.admin.question_bn') }}</label>
            <input type="text" name="question_bn" value="{{ old('question_bn', $faq->question_bn) }}">
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.answer') }} *</label>
            <textarea name="answer" rows="4" required>{{ old('answer', $faq->answer) }}</textarea>
        </div>
        <div>
            <label>{{ __('site.admin.answer_bn') }}</label>
            <textarea name="answer_bn" rows="4">{{ old('answer_bn', $faq->answer_bn) }}</textarea>
        </div>
    </div>

    <div class="admin-grid2">
        <div>
            <label>{{ __('site.admin.sort_order') }}</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}">
        </div>
        <div class="admin-checks" style="align-self:end;">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                {{ __('site.admin.active') }}
            </label>
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="admin-btn">{{ __('site.admin.save') }}</button>
        <a href="{{ route('admin.faqs.index') }}" class="admin-btn ghost">{{ __('site.admin.cancel') }}</a>
    </div>
</form>
@endsection
