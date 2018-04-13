@extends('layouts.app')

@section('title', 'Vendor | ' . $vendor->name)

@section('content')

<h1>{{ $vendor->name }} <a href="{{ route('vendor.edit',['id' => $vendor['id']]) }}" class="btn btn-outline-primary float-right">Edit Vendor</a></h1>
<div class="card">
    <h4 class="card-header">Contact Information</h4>
    <div class="card-body">
        <p>{{ $vendor->contact_name}}</p>
        <p>{{ $vendor->contact_email}}</p>
        <p>{!! nl2br(e($vendor->contact_address)) !!}</p>
    </div>
</div>
<div class="card mt-3">
    <h4 class="card-header">Licenses</h4>
    <div class="card-body">
        <ul>
        @foreach($vendor->licenses as $license)
            <li><a href="{{ route('license.show',['id' => $license->id]) }}">{{ $license->license_slug }}</a> (expires {{ $license->expires }})</li>
        @endforeach
        </ul>
    </div>
</div>
<div class="card mt-3">
    <h4 class="card-header">Notes</h4>
    <div class="card-body">
        <p>{!! nl2br(e($vendor->notes)) !!}</p>
    </div>
</div>
<div class="card mt-3">
    <h4 class="card-header">History</h4>
    <div class="card-body">
        <p>Created at: {{ $vendor->created_at->toDayDateTimeString()}}</p>
        <p>Updated at: {{ $vendor->updated_at->toDayDateTimeString()}}</p>
    </div>
</div>
<a href="{{ route('vendor.edit',['id' => $vendor['id']]) }}" class="btn btn-outline-primary float-right mt-2">Edit Vendor</a>
@endsection
