@extends('layouts.app')

@section('title', 'Vendor')

@section('content')
{{ Form::model($vendor, ['route' => ['vendor.update', $vendor->id], 'method' => 'PATCH']) }}
<div class="form-group row">
    {{ Form::label('name', 'Vendor Name', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-6">
        {{ Form::text('name', null , ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('contact_name', 'Contact Name', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-6">
        {{ Form::text('contact_name', null , ['class' => 'form-control', 'aria-describedby' => 'contactNameHelp']) }}
        <small id="contactNameHelp" class="form-text text-muted">Can be same as vendor name.</small>
    </div>
</div>
<div class="form-group row">
    {{ Form::label('contact_email', 'Contact Email', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-6">
        {{ Form::email('contact_email', null , ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('contact_address', 'Contact Address', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-6">
        {{ Form::textarea('contact_address', null , ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('notes', 'Notes', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-6">
        {{ Form::textarea('notes', null , ['class' => 'form-control']) }}
        <button type="submit" class="btn btn-outline-primary float-right mt-2">Update Vendor</button>
    </div>
</div>
{{ Form::close() }}
@endsection
