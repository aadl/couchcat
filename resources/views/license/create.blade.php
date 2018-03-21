@extends('layouts.app')

@section('title', 'Add a License')

@section('content')

{{ Form::open(array('route' => 'license.store')) }}
{{ Form::label('vendor', 'Vendor Name') }}
{{ Form::text('vendor') }}
{{ Form::label('statistics_slug', 'Statistics Slug') }}
{{ Form::text('statistics_slug') }}
{{ Form::label('starts', 'License Starts') }}
{{ Form::date('starts', \Carbon\Carbon::now()) }}
{{ Form::label('ends', 'License Expires') }}
{{ Form::date('ends', \Carbon\Carbon::now("+1 year")) }}
{{ Form::label('public', 'Public Access') }}
{{ Form::checkbox('public', 'public') }}
{{ Form::submit('Add License') }}
<button type="submit" class="btn btn-primary mb-2">Add License</button>
{{ Form::close() }}

@endsection
