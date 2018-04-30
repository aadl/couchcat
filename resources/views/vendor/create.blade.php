@extends('layouts.app')

@section('title', 'Add a Vendor')

@section('content')
{{ Form::open(array('route' => 'vendor.store')) }}
<div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Vendor Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="name" id="name" placeholder="My Vendor" required>
    </div>
</div>
<div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Contact Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" aria-describedby="contactNameHelp" name="contact_name" id="contact_name" placeholder="Contact Name">
        <small id="contactNameHelp" class="form-text text-muted">Can be blank if same as vendor.</small>
    </div>
</div>
<div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Contact Email</label>
    <div class="col-sm-6">
        <input type="email" class="form-control" name="contact_email" id="contact_email" placeholder="bob@ross.com">
    </div>
</div>
<div class="form-group row">
    <label for="contact_address" class="col-sm-2 col-form-label">Mailing Address</label>
    <div class="col-sm-6">
        <textarea class="form-control" name="contact_address" id="contact_address"></textarea>
    </div>
</div>
<div class="form-group row">
    <label for="notes" class="col-sm-2 col-form-label">Notes</label>
    <div class="col-sm-6">
        <textarea class="form-control" name="notes" id="notes"></textarea>
        <button type="submit" class="btn btn-primary float-right mt-2">Add Vendor</button>
    </div>
</div>

{{ Form::close() }}

@endsection
