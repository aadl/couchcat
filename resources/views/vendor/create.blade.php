@extends('layouts.app')

@section('title', 'Add a Vendor')

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

{{ Form::open(array('route' => 'vendor.store')) }}

{{ Form::label('name', 'Vendor Name') }}
{{ Form::text('name') }}
{{ Form::label('contact_name', 'Contact Name') }}
{{ Form::text('contact_name') }}
{{ Form::label('contact_email', 'Contact Email') }}
{{ Form::text('contact_email') }}
{{ Form::submit('Add Vendor') }}

{{ Form::close() }}

@endsection