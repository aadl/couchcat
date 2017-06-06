@extends('layouts.app')

@section('title', 'Add a Vendor')

@section('content')

{{ Form::open(array('route' => 'vendor.store')) }}

{{ Form::label('name', 'Vendor Name') }}
{{ Form::text('name') }}
{{ Form::label('name', 'Contact Name') }}
{{ Form::text('name') }}
{{ Form::label('name', 'Contact Email') }}
{{ Form::text('name') }}
{{ Form::submit('Add Vendor') }}

{{ Form::close() }}

@endsection