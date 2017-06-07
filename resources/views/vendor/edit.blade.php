@extends('layouts.app')

@section('title', 'Vendor')

@section('content')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{ Form::model($vendor, ['route' => ['vendor.update', $vendor->id], 'method' => 'PATCH']) }}
{{ Form::label('name', 'Vendor Name') }}
{{ Form::text('name') }}
{{ Form::label('contact_name', 'Contact Name') }}
{{ Form::text('contact_name') }}
{{ Form::label('contact_email', 'Contact Email') }}
{{ Form::text('contact_email') }}
{{ Form::submit('Update Vendor') }}
{{ Form::close() }}
@endsection