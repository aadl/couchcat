@extends('layouts.app')

@section('title', 'Edit '.$record->title)

@section('content')
{{ Form::open(array('route' => ['record.update', $record->_id], 'files' => true, 'method' => 'PATCH')) }}
@if (strpos($record->mat_code, 'z') !== false)
<div class="form-group row">
    <label for="license_slug" class="col-sm-2 col-form-label">License Name</label>
    <div class="col-sm-6">
        {{ Form::select('license_slug', $licenses, ($record->licensed_from ?? ''), ['id' => 'license_slug', 'class' => 'form-control', 'aria-describedby' => 'licenseHelpHelp']) }}
        <small id="licenseHelp" class="form-text text-muted">Create new licenses on the licenses page.</small>
    </div>
</div>
@endif
<div class="form-group row">
    <label for="mat_type" class="col-sm-2 col-form-label">Material Type</label>
    <div class="col-sm-6">
        {{ Form::select('mat_code', $mat_types, $record->mat_code, ['id' => 'mat_code', 'class' => 'form-control', 'aria-describedby' => 'materialHelp', $protect_evg_fields]) }}
        <small id="materialHelp" class="form-text text-muted">Select the material type for this record.</small>
    </div>
</div>
<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">Title</label>
    <div class="col-sm-6">
        {{ Form::text('title', $record->title, ['id' => 'title', 'class' => 'form-control', 'aria-describedby' => 'titleHelp', 'required' => true, $protect_evg_fields ]) }}
        <small id="titleHelp" class="form-text text-muted">Title of the record.</small>
    </div>
</div>
<div class="form-group row @if ($record->mat_code == 'z' || $record->mat_code == 'za')no-display @endif">
    <label for="author" class="col-sm-2 col-form-label">Author</label>
    <div class="col-sm-6">
        {{ Form::text('author', ($record->author ?? ''), ['id' => 'author', 'class' => 'form-control', 'aria-describedby' => 'authorHelp', $protect_evg_fields]) }}
        <small id="authorHelp" class="form-text text-muted">Last name, first name.</small>
    </div>
</div>
<div class="form-group row @if ($record->mat_code != 'z' && $record->mat_code != 'za')no-display @endif">
    <label for="artist" class="col-sm-2 col-form-label">Artist</label>
    <div class="col-sm-6">
        {{ Form::text('artist', ($record->artist ?? ''), ['id' => 'artist', 'class' => 'form-control', 'aria-describedby' => 'artistHelp', $protect_evg_fields]) }}
        <small id="artistHelp" class="form-text text-muted">Artist.</small>
    </div>
</div>
@if ($record->mat_code == 'r')
<div class="form-group row">
    <label for="good-for" class="col-sm-2 col-form-label">Tagline</label>
    <div class="col-sm-6">
        {{ Form::text('tagline', ($record->tagline ?? ''), ['id' => 'tagline', 'class' => 'form-control', 'aria-describedby' => 'taglineHelp']) }}
        <small id="taglineHelp" class="form-text text-muted">Tagline for the item like 'Good for audio'. This will display where authors normally are.</small>
    </div>
</div>
@endif
<div class="form-group row">
    <label for="notes" class="col-sm-2 col-form-label">Summary / Description</label>
    <div class="col-sm-6">
        {{ Form::textarea('notes', (isset($record->notes) ? implode("\r\n", $record->notes) : ''), ['id' => 'notes', 'class' => 'form-control', 'aria-describedby' => 'notesHelp', $protect_evg_fields]) }}
        <small id="notesHelp" class="form-text text-muted">A summary / description for displaying on the public catalog.</small>
    </div>
</div>
@if ($record->mat_code == 'a')
<div class="form-group row">
    <label for="lexile" class="col-sm-2 col-form-label">Lexile</label>
    <div class="col-sm-6">
        {{ Form::text('lexile', (isset($record->reading_level) ? $record->reading_level->lexile : ''), ['id' => 'lexile', 'class' => 'form-control', 'aria-describedby' => 'lexileHelp']) }}
        <small id="lexileHelp" class="form-text text-muted">The lexile reading level.</small>
    </div>
</div>
@endif
<div class="form-group row">
    <label for="series" class="col-sm-2 col-form-label">Series</label>
    <div class="col-sm-6">
        {{ Form::textarea('series', implode("\r\n", ($record->series ?? [])), ['id' => 'series', 'class' => 'form-control', 'aria-describedby' => 'seriesHelp', $protect_evg_fields]) }}
        <small id="seriesHelp" class="form-text text-muted">Series. One per line.</small>
    </div>
</div>
<div class="form-group row">
    <label for="subjects" class="col-sm-2 col-form-label">Subjects</label>
    <div class="col-sm-6">
        {{ Form::textarea('subjects', implode("\r\n", (isset($record->subjects) && $record->subjects != '' ? $record->subjects : [])), ['id' => 'subjects', 'class' => 'form-control', 'aria-describedby' => 'subjectsHelp', $protect_evg_fields]) }}
        <small id="subjectsHelp" class="form-text text-muted">Subjects. One per line.</small>
    </div>
</div>
@if ($record->mat_code == 'r')
<div class="form-group row">
    <label for="documentation" class="col-sm-2 col-form-label">Documentation</label>
    <div class="col-sm-6">
        {{ Form::textarea('documentation', implode("\r\n", ($record->documentation ?? [])), ['id' => 'documentation', 'class' => 'form-control', 'aria-describedby' => 'documentationHelp']) }}
        <small id="documentationHelp" class="form-text text-muted">Links to useful documentation for the record.</small>
    </div>
</div>
@endif
@if ($record->mat_code == 'r')
<div class="form-group row">
    <label for="contents" class="col-sm-2 col-form-label">Contents</label>
    <div class="col-sm-6">
        {{ Form::textarea('contents', implode("\r\n", ($record->contents ?? [])), ['id' => 'contents', 'class' => 'form-control', 'aria-describedby' => 'contentsHelp']) }}
        <small id="contentsHelp" class="form-text text-muted">All parts that come with the tool.</small>
    </div>
</div>
<div class="form-group row">
    <label for="related_links" class="col-sm-2 col-form-label">Related Links</label>
    <div class="col-sm-6">
        {{ Form::textarea('related_links', implode("\r\n", ($record->related_links ?? [])), ['id' => 'related_links', 'class' => 'form-control', 'aria-describedby' => 'relatedHelp']) }}
        <small id="relatedHelp" class="form-text text-muted">Related items for this record. Format as Title|Link with each line being a related link.</small>
    </div>
</div>
<div class="form-group row">
    <label for="accessories" class="col-sm-2 col-form-label">Accessories</label>
    <div class="col-sm-6">
        {{ Form::textarea('accessories', implode("\r\n", ($record->accessories ?? [])), ['id' => 'accessories', 'class' => 'form-control', 'aria-describedby' => 'accessoriesHelp']) }}
        <small id="relatedHelp" class="form-text text-muted">Accessories for this record. Format as Title|Link with each line being a related link.</small>
    </div>
</div>
@endif
<div class="form-group row">
    <label for="pub_year" class="col-sm-2 col-form-label">Pub Year</label>
    <div class="col-sm-6">
        {{ Form::number('pub_year', ($record->pub_year ?? ''), ['id' => 'pub_year', 'class' => 'form-control', 'aria-describedby' => 'pubYearHelp', $protect_evg_fields]) }}
        <small id="pubYearHelp" class="form-text text-muted">Year this material was published.</small>
    </div>
</div>
<div class="form-group row">
    <label for="stdnum" class="col-sm-2 col-form-label">ISBN/Standard Number</label>
    <div class="col-sm-6">
        {{ Form::textarea('stdnum', implode("\r\n", ($record->stdnum ?? [])), ['id' => 'stdnum', 'class' => 'form-control', 'aria-describedby' => 'stdnumHelp', $protect_evg_fields]) }}
        <small id="stdnumHelp" class="form-text text-muted">ISBNs. One per line.</small>
    </div>
</div>
<div class="form-group row">
    <label for="cover" class="col-sm-2 col-form-label">Cover Image</label>
    <div class="col-sm-6">
        {{ Form::file('cover', ['id' => 'cover', 'class' => 'form-control', 'aria-describedby' => 'coverHelp']) }}
        <small id="coverHelp" class="form-text text-muted">Attach a new cover image.</small>
    </div>
</div>
@if ($record->mat_code == 'r')
<div class="form-group row">
    <label for="cat_guide" class="col-sm-2 col-form-label">Catalog Guide</label>
    <div class="col-sm-6">
        {{ Form::file('cat_guide', ['id' => 'cat_guide', 'class' => 'form-control', 'aria-describedby' => 'guideHelp']) }}
        <small id="coverHelp" class="form-text text-muted">Upload a catalog guide.</small>
    </div>
</div>
@endif
@if (strpos($record->mat_code, 'z') !== false)
<div class="form-group row @if ($record->mat_code == 'z' || $record->mat_code == 'za')no-display @endif">
    <label for="attachment" class="col-sm-2 col-form-label">Record File</label>
    <div class="col-sm-6">
        {{ Form::file('attachment', ['id' => 'attachment', 'class' => 'form-control', 'aria-describedby' => 'attachmentHelp']) }}
        <small id="attachmentHelp" class="form-text text-muted">Attach a file relevant to the record (e.g., a pdf for a book download).</small>
    </div>
</div>
<div class="form-group row">
    <label for="ages" class="col-sm-2 col-form-label">Ages</label>
    <div class="col-sm-6">
    {{ Form::select('ages[]', ['youth' => 'youth', 'teen' => 'teen', 'adult' => 'adult'], ($record->ages ?? null), ['id' => 'ages', 'multiple' => 'multiple', 'aria-describedby' => 'agesHelp']) }}
    <small id="agesHelp" class="form-text text-muted">Select the age groups that apply to this record.</small>
    </div>
</div>
@endif
@if (isset($record->tracks))
    <div>
        <fieldset>
            <legend>Tracks</legend>
            @foreach ($record->tracks as $num => $track)
                <div class="row form-group">
                    <label for="edit-track-title-{{ $num }}" class="col-sm-2 col-form-label">Track {{ $num }} Title</label>
                    <div class="col-sm-6">
                        {{ Form::text('edit-track-title[' . $num . ']', $track->title, ['id' => 'edit-track-title-' . $num, 'class' => 'form-control', 'aria-describedby' => 'trackTitleHelp']) }}
                        <small id="trackTitleHelp" class="form-text text-muted">Title of the track.</small>
                    </div>
                </div>
            @endforeach
        </fieldset>
    </div>    
@endif
@if ($record->mat_code == 'z' || $record->mat_code == 'za')
<div class="row form-group">
    <div class="col-sm-6 offset-sm-2">
        <button id="track-add" class="btn btn-secondary">Add Track</button>
    </div>
</div>
@endif
@if (isset($record->gamecodes))
    <div>
        <fieldset>
            <legend>Game Codes</legend>
            @foreach ($record->gamecodes as $k => $codes)
                @foreach ($codes as $num => $code)
                <div class="row form-group">
                    <label for="edit-gamecode-{{ $k }}-{{ $num }}" class="col-sm-2 col-form-label">{{ $k }}</label>
                    <div class="col-sm-6">
                        {{ Form::text("edit-gamecode[$k][]", $code, ['id' => 'edit-gamecode-' . $k . '-' . $num, 'class' => 'form-control', 'aria-describedby' => 'gameCodeHelp']) }}
                        <small id="gameCodeHelp" class="form-text text-muted">Game code text.</small>
                        <button class="btn btn-danger delete-game-code">Delete {{ $code }}</button>
                    </div>
                </div>
                @endforeach
            @endforeach
        </fieldset>
    </div>
@endif
<div class="form-group row">
    <label for="is_active" class="col-sm-2 col-form-label">Record Active</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_active', null, $record->active, ['id' => 'is_active', 'aria-describedby' => 'publicHelp', $protect_evg_fields]) }}
        <small id="publicHelp" class="form-text text-muted">Uncheck if this should suppressed in the public catalog.</small>
    </div>
</div>
<div class="form-group row">
    <label for="is_protected" class="col-sm-2 col-form-label">Record Protected</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_protected', null, $record->flags->protected ?? 0, ['id' => 'is_protected', 'aria-describedby' => 'protectedHelp', $protect_evg_fields]) }}
        <small id="protectedHelp" class="form-text text-muted">Check if this record should be protected from suppression.</small>
    </div>
</div>
<div class="form-group row">
    <label for="is_protected" class="col-sm-2 col-form-label">Not Requestable</label>
    <div class="col-sm-6">
        {{ Form::checkbox('not_requestable', null, $record->disable_requests ?? 0, ['id' => 'not_requestable', 'aria-describedby' => 'requestableHelp']) }}
        <small id="requestableHelp" class="form-text text-muted">Check if this record shouldn't be requestable.</small>
    </div>
</div>
<div class="form-group row">
    <label for="is_public_domain" class="col-sm-2 col-form-label">Public Domain</label>
    <div class="col-sm-6">
        {{ Form::checkbox('is_public_domain', null, $record->flags->public_domain ?? 0, ['id' => 'is_public_domain', 'aria-describedby' => 'publicDomainHelp']) }}
        <small id="publicDomainHelp" class="form-text text-muted">Check if this record should be in the public domain and available to anyone.</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Edit Record</button>
{{ Form::close() }}
@endsection
