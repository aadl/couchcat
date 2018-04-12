@extends('layouts.app')

@section('title', 'License | ' . $license->statistics_stub)

@section('content')

<h1>{{ $license->statistics_stub }} <a href="{{ route('license.edit',['id' => $license->id]) }}" class="btn btn-outline-primary float-right">Edit License</a></h1>
<div class="card">
    <h4 class="card-header">Vendor Information</h4>
    <div class="card-body">
        <p><a href="{{ route('vendor.show',['id' => $license->vendor->id]) }}">{{ $license->vendor->contact_name}}</a></p>
        <p>{{ $license->vendor->contact_email}}</p>
        <p>{!! nl2br(e($license->vendor->contact_address)) !!}</p>
    </div>
</div>
<div class="card mt-3">
    <h4 class="card-header">License Information</h4>
    <div class="card-body">
        <p>Cost: {{ $license->cost }}</p>
    </div>
</div>
<div class="card mt-3">
    <h4 class="card-header">Notes</h4>
    <div class="card-body">
        <p>{!! nl2br(e($license->notes)) !!}</p>
    </div>
</div>
<div class="card mt-3">
    <h4 class="card-header">History</h4>
    <div class="card-body">
        <p>Created at: {{ $license->created_at->toDayDateTimeString()}}</p>
        <p>Updated at: {{ $license->updated_at->toDayDateTimeString()}}</p>
    </div>
</div>
<a href="{{ route('license.edit',['id' => $license->id]) }}" class="btn btn-outline-primary float-right mt-2">Edit License</a>
@endsection
