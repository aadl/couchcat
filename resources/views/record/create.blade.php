@extends('layouts.app')

@section('title', 'Add a Record')

@section('content')
{{ Form::open(array('route' => 'record.store', 'files' => true)) }}
<div class="form-group row">
    <label for="license_slug" class="col-sm-2 col-form-label">License Name</label>
    <div class="col-sm-6">
        {{ Form::select('license_slug', $licenses, $license_slug, ['id' => 'license_slug', 'class' => 'form-control', 'aria-describedby' => 'licenseHelpHelp']) }}
        <small id="licenseHelp" class="form-text text-muted">Create new licenses on the licenses page.</small>
    </div>
</div>
<div class="form-group row">
    <label for="mat_type" class="col-sm-2 col-form-label">Material Type</label>
    <div class="col-sm-6">
        {{ Form::select('mat_code', $mat_types, null, ['id' => 'mat_code', 'class' => 'form-control', 'aria-describedby' => 'materialHelp']) }}
        <small id="materialHelp" class="form-text text-muted">Select the material type for this record.</small>
    </div>
</div>
<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">Title</label>
    <div class="col-sm-6">
        {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'aria-describedby' => 'titleHelp']) }}
        <small id="titleHelp" class="form-text text-muted">Title of the record.</small>
    </div>
</div>
<div class="form-group row no-display">
    <label for="title" class="col-sm-2 col-form-label">Author</label>
    <div class="col-sm-6">
        {{ Form::text('author', null, ['id' => 'author', 'class' => 'form-control', 'aria-describedby' => 'authorHelp']) }}
        <small id="authorHelp" class="form-text text-muted">Author.</small>
    </div>
</div>
<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">Artist</label>
    <div class="col-sm-6">
        {{ Form::text('artist', null, ['id' => 'artist', 'class' => 'form-control', 'aria-describedby' => 'artistHelp']) }}
        <small id="artistHelp" class="form-text text-muted">Artist.</small>
    </div>
</div>
<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">Summary / Description</label>
    <div class="col-sm-6">
        {{ Form::textarea('notes', null, ['id' => 'notes', 'class' => 'form-control', 'aria-describedby' => 'notesHelp']) }}
        <small id="titleHelp" class="form-text text-muted">A summary / description for displaying on the public catalog.</small>
    </div>
</div>
<div class="form-group row">
    <label for="pub_year" class="col-sm-2 col-form-label">Pub Year</label>
    <div class="col-sm-6">
        {{ Form::number('pub_year', null, ['id' => 'pub_year', 'class' => 'form-control', 'aria-describedby' => 'pubYearHelp']) }}
        <small id="pubYearHelp" class="form-text text-muted">Year this material was published.</small>
    </div>
</div>
<div class="form-group row">
    <label for="cover" class="col-sm-2 col-form-label">Cover Image</label>
    <div class="col-sm-6">
        {{ Form::file('cover', ['id' => 'cover', 'class' => 'form-control', 'aria-describedby' => 'coverHelp']) }}
        <small id="coverHelp" class="form-text text-muted">Attach a cover image.</small>
    </div>
</div>
<div class="form-group row no-display">
    <label for="attachment" class="col-sm-2 col-form-label">Record File</label>
    <div class="col-sm-6">
        {{ Form::file('attachment', ['id' => 'attachment', 'class' => 'form-control', 'aria-describedby' => 'attachmentHelp']) }}
        <small id="attachmentHelp" class="form-text text-muted">Attach a file relevant to the record (e.g., a pdf for a book download).</small>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm-6 offset-sm-2">
        <button id="track-add" class="btn btn-secondary">Add Track</button>
    </div>
</div>
<div class="form-group row">
    <label for="is_active" class="col-sm-2 col-form-label">Record Active</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_active', null, true, ['id' => 'is_active', 'aria-describedby' => 'publicHelp']) }}
        <small id="publicHelp" class="form-text text-muted">Uncheck if this should suppressed in the public catalog.</small>
    </div>
</div>
<div class="form-group row">
    <label for="is_protected" class="col-sm-2 col-form-label">Record Protected</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_protected', null, false, ['id' => 'is_protected', 'aria-describedby' => 'protectedHelp']) }}
        <small id="protectedHelp" class="form-text text-muted">Check if this record should be protected from suppression.</small>
    </div>
</div>
<div class="form-group row">
    <label for="not_requestable" class="col-sm-2 col-form-label">Not Requestable</label>
    <div class="col-sm-6">
        {{ Form::checkbox('not_requestable', null, false, ['id' => 'not_requestable', 'aria-describedby' => 'requestableHelp']) }}
        <small id="requestableHelp" class="form-text text-muted">Check if this record shouldn't be requestable.</small>
    </div>
</div>
<div class="form-group row">
    <label for="is_public_domain" class="col-sm-2 col-form-label">Public Domain</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_public_domain', null, false, ['id' => 'is_public_domain', 'aria-describedby' => 'publicDomainHelp']) }}
        <small id="publicDomainHelp" class="form-text text-muted">Check if this record should be in the public domain and available to anyone.</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Create Record</button>
{{ Form::close() }}
@endsection
