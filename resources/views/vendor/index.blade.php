@extends('layouts.app')

@section('title', 'Vendors')

@section('content')
<table>
	<thead>
		<tr>
		<th class='td-3'>Vendor</th>
		<th class='td-3'>Contact Name</th>
		<th class='td-2'>Contact Email</th>
		<th class='td-2'>Last Modified</th>
		<th class='td-1'>Actions</th>
		</tr>
	</thead>
	<tbody>
	@foreach($vendors as $vendor)
		<tr>
			<td class='td-3'><a href="{{ route('vendor.show',['id' => $vendor['id']]) }}">{{ $vendor['name'] }}</a></td>
			<td class='td-3'>{{ $vendor['contact_name'] }}</td>
			<td class='td-2'>{{ $vendor['contact_email'] }}</td>
			<td class='td-2'>{{ $vendor['updated_at'] }}</td>
			<td class='td-1'><a href="{{ route('vendor.edit',['id' => $vendor['id']]) }}">Edit</a></td>
		</tr>
@endforeach
	</tbody>
</table>
<a href='{{ route('vendor.create') }}' class='btn'>Add Vendor</a>
@endsection