@extends('layouts.app')

@section('title', 'Update Cover Image')

@section('content')
{{ Form::open(array('route' => ['record.cover', $id], 'files' => true)) }}
<h1>Update cover for {{ $title }}</h1>
<div class="form-group row">
    <label for="cover" class="col-sm-2 col-form-label">Cover Image</label>
    <div class="col-sm-6">
        {{ Form::file('cover', ['id' => 'cover', 'class' => 'form-control', 'aria-describedby' => 'coverHelp']) }}
        <small id="coverHelp" class="form-text text-muted">Attach a cover image (.jpg/.jpeg).</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Update Cover</button>
{{ Form::close() }}
@endsection
