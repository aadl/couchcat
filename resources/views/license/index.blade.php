@extends('layouts.app')

@section('title', 'Licenses')

@section('content')
<h1>Licenses <a href="{{ route('license.create') }}" class="btn btn-outline-primary float-right">Add License</a></h1>
<table class="table table-striped">
	<thead class="thead-dark">
		<tr>
		<th scope="col">Vendor</th>
		<th scope="col">Catalog Stub</th>
		<th scope="col">Starts</th>
		<th scope="col">Expires</th>
		<th scope="col">Patrons Only</th>
		<th scope="col">Records</th>
		<th scope="col">Actions</th>
		</tr>
	</thead>
	<tbody>
	@foreach($licenses as $license)
		@php 
			$records_count = 0;
			if ($records_key = array_search($license->statistics_stub, array_column($records, 'key'))) {
				$records_count = $records[$records_key]->value;
			}
		@endphp
		<tr>
			<td><a href="{{ route('vendor.show',['id' => $license['vendor']['id']]) }}">{{ $license['vendor']['name'] }}</a></td>
			<td>{{ $license['statistics_stub'] }}</td>
			<td>{{ $license['starts'] }}</td>
			<td class='{{ $license->expired ? 'text-danger' : 'text-success' }}'>{{ $license['expires'] }}</td>
			<td>{{ $license['patrons_only'] ? 'yes' : 'no' }}</td>
			<td>{{ $records_count }}</td>
			<td><a href="{{ route('license.edit',['id' => $license['id']]) }}">Edit</a></td>
		</tr>
	@endforeach
	</tbody>
</table>
<a href="{{ route('license.create') }}" class="btn btn-outline-primary">Add License</a>
@endsection
