@extends('layouts.app')

@section('title', 'Edit License | '. $license->license_slug)

@section('content')
{{ Form::model($license, ['route' => ['license.update', $license->id], 'method' => 'PATCH']) }}
<div class="form-group row">
    <label for="vendor_id" class="col-sm-2 col-form-label">Vendor Name</label>
    <div class="col-sm-6">
        {{ Form::select('vendor_id', $vendors, $license->vendor_id, ['class' => 'form-control', 'aria-describedby' => 'vendorHelp']) }}
        <small id="emailHelp" class="form-text text-muted">Create new vendors on the vendor page.</small>
    </div>
</div>
<div class="form-group row">
    <label for="license_slug" class="col-sm-2 col-form-label">License Slug</label>
    <div class="col-sm-6">
        {{ Form::text('license_slug', null , ['class' => 'form-control']) }}
        <small id="statsHelp" class="form-text text-muted">A unique string for use in generating reports and organizing for this license (no spaces).</small>
    </div>
</div>
<div class="form-group row">
    <label for="cost" class="col-sm-2 col-form-label">Cost</label>
    <div class="col-sm-6 input-group">
        <div class="input-group-prepend">
            <div class="input-group-text">$</div>
        </div>
        {{ Form::text('cost', null , ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    <label for="starts" class="col-sm-2 col-form-label">Start Date</label>
    <div class="col-sm-3">
        {{ Form::date('starts', null , ['class' => 'form-control', 'aria-describedby' => 'startHelp']) }}
        <small id="startHelp" class="form-text text-muted">When the license starts.</small>
    </div>
</div>
<div class="form-group row">
    <label for="expires" class="col-sm-2 col-form-label">Expires Date</label>
    <div class="col-sm-3">
        {{ Form::date('expires', null , ['class' => 'form-control', 'aria-describedby' => 'endHelp']) }}
        <small id="endHelp" class="form-text text-muted">When the license expires.<br />Leave as-is for ones that don't expire.</small>
    </div>
</div>
<div class="form-group row">
    <label for="notes" class="col-sm-2 col-form-label">Notes</label>
    <div class="col-sm-6">
        {{ Form::textarea('notes', null , ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    <label for="patrons_only" class="col-sm-2 col-form-label">Patrons Only</label>
    <div class="col-sm-6">
        {{ Form::checkbox('patrons_only', null , null, ['class' => 'form-control', 'aria-describedby' => 'publicHelp']) }}
        <small id="publicHelp" class="form-text text-muted">Uncheck if this should be public access (no Lcard required).</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Update License</button>
{{ Form::close() }}
@endsection
