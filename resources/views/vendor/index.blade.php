@extends('layouts.app')

@section('title', 'Vendors')

@section('content')
<h1>Vendors <a href="{{ route('vendor.create') }}" class="btn btn-outline-primary float-right">Add Vendor</a></h1>
<table class="table table-striped">
	<thead  class="thead-dark">
		<tr>
			<th scope="col">Vendor</th>
			<th scope="col">Contact Name</th>
			<th scope="col">Contact Email</th>
			<th scope="col">Last Modified</th>
			<th scope="col">Licenses</th>
			<th scope="col">Actions</th>
		</tr>
	</thead>
	<tbody>
	@foreach($vendors as $vendor)
		<tr>
			<td><a href="{{ route('vendor.show',['id' => $vendor->id]) }}">{{ $vendor->name }}</a></td>
			<td>{{ $vendor->contact_name }}</td>
			<td>{{ $vendor->contact_email }}</td>
			<td>{{ $vendor->updated_at->toFormattedDateString() }}</td>
			<td>{{ $vendor->licenses->count() }}</td>
			<td><a href="{{ route('vendor.edit',['id' => $vendor['id']]) }}">Edit</a> / <a href="{{ route('license.create', ['vendor_id' => $vendor->id]) }}">Add License</a></td>
		</tr>
@endforeach
	</tbody>
</table>
<a href="{{ route('vendor.create') }}" class="btn btn-outline-primary">Add Vendor</a>
@endsection
