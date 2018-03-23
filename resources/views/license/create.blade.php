@extends('layouts.app')

@section('title', 'Add a License')

@section('content')

{{ Form::open(array('route' => 'license.store')) }}
<div class="form-group row">
    <label for="vendor" class="col-sm-2 col-form-label">Vendor Name</label>
    <div class="col-sm-6">
        <select type="text" class="form-control" id="vendor" aria-describedby="vendorHelp" placeholder="My Vendor">
            @foreach ($vendors as $vendor)
                <option id="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select>
        <small id="emailHelp" class="form-text text-muted">Create new vendors on the vendor page.</small>
    </div>
</div>
<div class="form-group row">
    <label for="satistics_slug" class="col-sm-2 col-form-label">Statistics Slug</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" id="statistics_slug" aria-describedby="statsHelp" placeholder="my-license">
        <small id="statsHelp" class="form-text text-muted">A unique string for use in generating reports for this license (no spaces).</small>
    </div>
</div>
<div class="form-group row">
    <label for="starts" class="col-sm-2 col-form-label">Start Date</label>
    <div class="col-sm-3">
        <input type="date" class="form-control" id="starts" aria-describedby="startHelp" placeholder="{{ \Carbon\Carbon::now() }}">
        <small id="startHelp" class="form-text text-muted">When the license starts.</small>
    </div>
</div>
<div class="form-group row">
    <label for="ends" class="col-sm-2 col-form-label">End Date</label>
    <div class="col-sm-3">
        <input type="date" class="form-control" id="ends" aria-describedby="endHelp" placeholder="{{ \Carbon\Carbon::now() }}">
        <small id="endHelp" class="form-text text-muted">When the license expires.<br />Leave as-is for ones that don't expire.</small>
    </div>
</div>
<div class="form-group row">
    <label for="public" class="col-sm-2 col-form-label">Public Access</label>
    <div class="col-sm-3">
        <input type="checkbox" class="form-control" id="public" aria-describedby="publicHelp">
        <small id="publicHelp" class="form-text text-muted">Check if this should be public access (no Lcard required).</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Add License</button>
{{ Form::close() }}

@endsection
