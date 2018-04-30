@extends('layouts.app')

@section('title', 'AADL Cataloging')

@section('content')
<h3>Couchcat Changes</h3>
<ul>
    <li><a href="/vendor">Vendor</a> entry is done</li>
    <li><a href="/license">License</a> entry is done</li>
</ul>
<h3>Recent Vendor Changes</h3>
<ul>
    @foreach($vendor_changes as $vendor_change)
        <li><a href="{{ route('vendor.show',['id' => $vendor_change->id]) }}">{{ $vendor_change->name }}</a> ( {{ $vendor_change->updated_at }})</li>
    @endforeach
</ul>
<h3>Recent License Changes</h3>
<ul>
    @foreach($license_changes as $license_change)
        <li><a href="{{ route('license.show',['id' => $license_change->id]) }}">{{ $license_change->license_slug }}</a> ( {{ $license_change->updated_at }})</li>
    @endforeach
</ul>
@endsection
