@extends('layouts.app')

@section('title', 'Add a Record')

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
{{ Form::open(array('route' => 'record.store')) }}
<div class="form-group row">
    <label for="vendor_id" class="col-sm-2 col-form-label">License Name</label>
    <div class="col-sm-6">
        {{ Form::text('vendor_id', $license_slug, ['class' => 'form-control', 'aria-describedby' => 'licenseHelp']) }}
        <small id="licenseHelp" class="form-text text-muted">Create new licenses on the licenses page.</small>
    </div>
</div>
<div class="form-group row">
    <label for="vendor_id" class="col-sm-2 col-form-label">Title</label>
    <div class="col-sm-6">
        {{ Form::text('title', null , null, ['class' => 'form-control', 'aria-describedby' => 'titleHelp']) }}
        <small id="titleHelp" class="form-text text-muted">Title of the record.</small>
    </div>
</div>
<div class="form-group row">
    <label for="vendor_id" class="col-sm-2 col-form-label">Material Type</label>
    <div class="col-sm-6">
        {{ Form::select('mat_type', $mat_types, ['class' => 'form-control', 'aria-describedby' => 'materialHelp']) }}
        <small id="materialHelp" class="form-text text-muted">Select the material type for this record.</small>
    </div>
</div>
<div class="form-group row">
    <label for="vendor_id" class="col-sm-2 col-form-label">Pub Year</label>
    <div class="col-sm-6">
        {{ Form::number('pub_year', null , null, ['class' => 'form-control', 'aria-describedby' => 'pubYearHelp']) }}
        <small id="pubYearHelp" class="form-text text-muted">Year this material was published.</small>
    </div>
</div>
<div class="form-group row">
    <label for="patrons_only" class="col-sm-2 col-form-label">Cover Image</label>
    <div class="col-sm-6">
        {{ Form::file('attachment', null, null, ['class' => 'form-control', 'aria-describedby' => 'coverHelp']) }}
        <small id="coverHelp" class="form-text text-muted">Attach a cover image.</small>
    </div>
</div>
<div class="form-group row">
    <label for="patrons_only" class="col-sm-2 col-form-label">Record File(s)</label>
    <div class="col-sm-6">
        {{ Form::file('attachment', null, null, ['class' => 'form-control', 'aria-describedby' => 'attachmentHelp']) }}
        <small id="attachmentHelp" class="form-text text-muted">Attach a file relevant to the record (e.g., a pdf for a book download).</small>
    </div>
</div>
<div class="form-group row">
    <label for="patrons_only" class="col-sm-2 col-form-label">Record Active</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_active', null, true, ['class' => 'form-control', 'aria-describedby' => 'publicHelp']) }}
        <small id="publicHelp" class="form-text text-muted">Uncheck if this should suppressed in the public catalog.</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Create Record</button>
{{ Form::close() }}
@endsection
