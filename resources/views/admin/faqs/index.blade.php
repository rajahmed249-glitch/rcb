@extends('admin.layout')

@section('title', __('site.admin.faq'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.faqs.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.question') }}</th>
                <th>{{ __('site.admin.active') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($faqs as $faq)
                <tr>
                    <td>{{ $faq->id }}</td>
                    <td>{{ Str::limit($faq->display_question, 60) }}</td>
                    <td>
                        @if ($faq->is_active)
                            <span class="admin-badge completed">{{ __('site.admin.active') }}</span>
                        @endif
                    </td>
                    <td>{{ $faq->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn small danger">{{ __('site.admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="admin-empty">{{ __('site.admin.no_data') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['paginator' => $faqs])
@endsection
