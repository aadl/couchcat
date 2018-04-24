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
{{ Form::open(array('route' => ['record.update', $record->_id], 'method' => 'PATCH')) }}
<div class="form-group row">
    <label for="license_slug" class="col-sm-2 col-form-label">License Name</label>
    <div class="col-sm-6">
        {{ Form::text('license_slug', ($record->licensed_from ?? ''), ['id' => 'license_slug', 'class' => 'form-control', 'aria-describedby' => 'licenseHelp']) }}
        <small id="licenseHelp" class="form-text text-muted">Create new licenses on the licenses page.</small>
    </div>
</div>
<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">Title</label>
    <div class="col-sm-6">
        {{ Form::text('title', $record->title, ['id' => 'title', 'class' => 'form-control', 'aria-describedby' => 'titleHelp']) }}
        <small id="titleHelp" class="form-text text-muted">Title of the record.</small>
    </div>
</div>
<div class="form-group row">
    <label for="notes" class="col-sm-2 col-form-label">Summary / Description</label>
    <div class="col-sm-6">
        {{ Form::textarea('notes', ($record->notes ? implode("\n\n", $record->notes) : ''), ['id' => 'notes', 'class' => 'form-control', 'aria-describedby' => 'notesHelp']) }}
        <small id="notesHelp" class="form-text text-muted">A summary / description for displaying on the public catalog.</small>
    </div>
</div>
@if (isset($record->documentation))
<div class="form-group row">
    <label for="documentation" class="col-sm-2 col-form-label">Summary / Description</label>
    <div class="col-sm-6">
        {{ Form::textarea('documentation', implode("\n\n", $record->documentation), ['id' => 'documentation', 'class' => 'form-control', 'aria-describedby' => 'documentationHelp']) }}
        <small id="documentationHelp" class="form-text text-muted">Links to useful documentation for the record.</small>
    </div>
</div>
@endif
<div class="form-group row">
    <label for="mat_type" class="col-sm-2 col-form-label">Material Type</label>
    <div class="col-sm-6">
        {{ Form::select('mat_code', $mat_types, $record->mat_code, ['id' => 'mat_code', 'class' => 'form-control', 'aria-describedby' => 'materialHelp']) }}
        <small id="materialHelp" class="form-text text-muted">Select the material type for this record.</small>
    </div>
</div>
<div class="form-group row">
    <label for="pub_year" class="col-sm-2 col-form-label">Pub Year</label>
    <div class="col-sm-6">
        {{ Form::number('pub_year', ($record->pub_year ?? ''), ['id' => 'pub_year', 'class' => 'form-control', 'aria-describedby' => 'pubYearHelp']) }}
        <small id="pubYearHelp" class="form-text text-muted">Year this material was published.</small>
    </div>
</div>
<div class="form-group row">
    <label for="cover" class="col-sm-2 col-form-label">Cover Image</label>
    <div class="col-sm-6">
        {{ Form::file('cover', ['id' => 'cover', 'class' => 'form-control', 'aria-describedby' => 'coverHelp']) }}
        <small id="coverHelp" class="form-text text-muted">Attach a new cover image.</small>
    </div>
</div>
@if (strpos($record->mat_code, 'z') !== false)
<div class="form-group row @if ($record->mat_code == 'z' || $record->mat_code == 'za')no-display @endif">
    <label for="attachment" class="col-sm-2 col-form-label">Record File</label>
    <div class="col-sm-6">
        {{ Form::file('attachment', ['id' => 'attachment', 'class' => 'form-control', 'aria-describedby' => 'attachmentHelp']) }}
        <small id="attachmentHelp" class="form-text text-muted">Attach a file relevant to the record (e.g., a pdf for a book download).</small>
    </div>
</div>
@endif
<div class="row form-group @if ($record->mat_code != 'z' || $record->mat_code != 'za')no-display @endif">
    <div class="col-sm-6 offset-sm-2">
        <button id="track-add" class="btn btn-secondary">Add Track</button>
    </div>
</div>
<div class="form-group row">
    <label for="is_active" class="col-sm-2 col-form-label">Record Active</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_active', null, $record->active, ['id' => 'is_active', 'aria-describedby' => 'publicHelp']) }}
        <small id="publicHelp" class="form-text text-muted">Uncheck if this should suppressed in the public catalog.</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Edit Record</button>
{{ Form::close() }}
@endsection
