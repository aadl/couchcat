@extends('layouts.app')

@section('title', 'Add a License')

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
{{ Form::open(array('route' => 'license.store')) }}
<div class="form-group row">
    <label for="vendor_id" class="col-sm-2 col-form-label">Vendor Name</label>
    <div class="col-sm-6">
        <select type="text" class="form-control" name="vendor_id" aria-describedby="vendorHelp" placeholder="My Vendor">
            @foreach ($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select>
        <small id="emailHelp" class="form-text text-muted">Create new vendors on the vendor page.</small>
    </div>
</div>
<div class="form-group row">
    <label for="license_slug" class="col-sm-2 col-form-label">Statistics Stub</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="license_slug" aria-describedby="statsHelp" placeholder="my-license">
        <small id="statsHelp" class="form-text text-muted">A unique string for use in generating reports and organizing for this license (no spaces).</small>
    </div>
</div>
<div class="form-group row">
    <label for="cost" class="col-sm-2 col-form-label">Cost</label>
    <div class="col-sm-6 input-group">
        <div class="input-group-prepend">
            <div class="input-group-text">$</div>
        </div>
        <input type="text" class="form-control" name="cost" aria-describedby="statsHelp" placeholder="100000000000">
    </div>
</div>
<div class="form-group row">
    <label for="starts" class="col-sm-2 col-form-label">Start Date</label>
    <div class="col-sm-3">
        <input type="date" class="form-control" name="starts" aria-describedby="startHelp" value="{{ \Carbon\Carbon::now() }}">
        <small id="startHelp" class="form-text text-muted">When the license starts.</small>
    </div>
</div>
<div class="form-group row">
    <label for="expires" class="col-sm-2 col-form-label">Expires Date</label>
    <div class="col-sm-3">
        <input type="date" class="form-control" name="expires" aria-describedby="endHelp" placeholder="{{ \Carbon\Carbon::now() }}">
        <small id="endHelp" class="form-text text-muted">When the license expires.<br />Leave as-is for ones that don't expire.</small>
    </div>
</div>
<div class="form-group row">
    <label for="patrons_only" class="col-sm-2 col-form-label">Patrons Only</label>
    <div class="col-sm-3">
        <input type="checkbox" class="form-control" name="patrons_only" aria-describedby="publicHelp" checked>
        <small id="publicHelp" class="form-text text-muted">Check if this should be public access (no Lcard required).</small>
    </div>
</div>
<button type="submit" class="btn btn-primary mb-2">Add License</button>
{{ Form::close() }}

@endsection
