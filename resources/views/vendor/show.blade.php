@extends('layouts.app')

@section('title', 'Vendor')

@section('content')

<h1>{{ $vendor->name }} (<a href="{{ route('vendor.edit',['id' => $vendor['id']]) }}">Edit</a>)</h1>
<hr>
<h4>Contact Information</h4>
<p>{{ $vendor->contact_name}}</p>
<p>{{ $vendor->contact_email}}</p>
<p>{!! nl2br(e($vendor->contact_address)) !!}</p>
<h4>Notes</h4>
<p>{!! nl2br(e($vendor->notes)) !!}</p>
<h4>History</h4>
<p>Created at: {{ $vendor->created_at->toDayDateTimeString()}}</p>
<p>Updated at: {{ $vendor->updated_at->toDayDateTimeString()}}</p>

@endsection
