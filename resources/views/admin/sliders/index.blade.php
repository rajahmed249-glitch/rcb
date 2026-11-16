@extends('admin.layout')

@section('title', __('site.admin.slider'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.sliders.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.image') }}</th>
                <th>{{ __('site.admin.title') }}</th>
                <th>{{ __('site.admin.active') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sliders as $slider)
                <tr>
                    <td>{{ $slider->id }}</td>
                    <td>@if ($slider->image)<img src="{{ asset($slider->image) }}" alt="" class="admin-thumb small">@endif</td>
                    <td>{{ $slider->title }}@if($slider->title_bn) <small class="admin-bn">/{{ $slider->title_bn }}</small>@endif</td>
                    <td>
                        @if ($slider->is_active)
                            <span class="admin-badge completed">{{ __('site.admin.active') }}</span>
                        @endif
                    </td>
                    <td>{{ $slider->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn small danger">{{ __('site.admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="admin-empty">{{ __('site.admin.no_data') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['paginator' => $sliders])
@endsection
