@extends('admin.layout')

@section('title', __('site.admin.sponsors'))

@section('content')
<div class="admin-toolbar">
    <a href="{{ route('admin.sponsors.create') }}" class="admin-btn">+ {{ __('site.admin.add') }}</a>
</div>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('site.admin.logo') }}</th>
                <th>{{ __('site.admin.name') }}</th>
                <th>{{ __('site.admin.website') }}</th>
                <th>{{ __('site.admin.sort_order') }}</th>
                <th>{{ __('site.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sponsors as $sponsor)
                <tr>
                    <td>{{ $sponsor->id }}</td>
                    <td>
                        @if ($sponsor->logo)
                            <img src="{{ asset($sponsor->logo) }}" alt="" class="admin-thumb">
                        @endif
                    </td>
                    <td>{{ $sponsor->name }}@if($sponsor->name_bn) <small class="admin-bn">/{{ $sponsor->name_bn }}</small>@endif</td>
                    <td>{{ $sponsor->website }}</td>
                    <td>{{ $sponsor->sort_order }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.sponsors.edit', $sponsor) }}" class="admin-btn small">{{ __('site.admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.sponsors.destroy', $sponsor) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn small danger">{{ __('site.admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.partials.pagination', ['paginator' => $sponsors])
@endsection
