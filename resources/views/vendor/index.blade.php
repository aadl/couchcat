@extends('layouts.app')

@section('title', 'Vendors')

@section('content')
<table>
	<thead>
		<tr>
		<th class='td-3'>Vendor</th>
		<th class='td-3'>Contact Name</th>
		<th class='td-2'>Contact Email</th>
		<th class='td-1'>Last Modified</th>
		<th class='td-1'>Licenses</th>
		<th class='td-2'>Actions</th>
		</tr>
	</thead>
	<tbody>
	@foreach($vendors as $vendor)
		<tr>
			<td class='td-3'><a href="{{ route('vendor.show',['id' => $vendor->id]) }}">{{ $vendor->name }}</a></td>
			<td class='td-3'>{{ $vendor->contact_name }}</td>
			<td class='td-2'>{{ $vendor->contact_email }}</td>
			<td class='td-1'>{{ $vendor->updated_at->toFormattedDateString() }}</td>
			<td class='td-1'>{{ $vendor->licenses()->count() }}</td>
			<td class='td-2'><a href="{{ route('vendor.edit',['id' => $vendor['id']]) }}">Edit</a> / <a href="{{ route('license.create', ['vendor_id' => $vendor->id]) }}">Add License</a></td>
		</tr>
@endforeach
	</tbody>
</table>
<a href='{{ route('vendor.create') }}' class='btn'>Add Vendor</a>
@endsection
