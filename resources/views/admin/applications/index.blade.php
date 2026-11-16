@extends('admin.layout')

@section('title', __('site.admin.applications'))

@section('content')
@if ($applications->isEmpty())
    <p class="admin-empty">{{ __('site.admin.no_applications') }}</p>
@else
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('site.admin.name') }}</th>
                    <th>{{ __('site.admin.phone') }}</th>
                    <th>{{ __('site.admin.email') }}</th>
                    <th>{{ __('site.admin.age') }}</th>
                    <th>{{ __('site.admin.preferred_role') }}</th>
                    <th>{{ __('site.admin.message') }}</th>
                    <th>{{ __('site.admin.date') }}</th>
                    <th>{{ __('site.admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->name }}</td>
                        <td>{{ $application->phone }}</td>
                        <td>{{ $application->email }}</td>
                        <td>{{ $application->age }}</td>
                        <td>{{ $application->preferred_role }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($application->message, 50) }}</td>
                        <td>{{ $application->created_at?->format('d M Y') }}</td>
                        <td class="admin-actions">
                            <form method="POST" action="{{ route('admin.applications.destroy', $application) }}" onsubmit="return confirm('{{ __('site.admin.delete_confirm') }}')">
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
@endif
@include('admin.partials.pagination', ['paginator' => $applications])
@endsection
