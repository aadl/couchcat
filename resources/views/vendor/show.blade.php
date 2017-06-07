@extends('layouts.app')

@section('title', 'Vendor')

@section('content')

<h1>{{ $vendor->name }} (<a href="{{ route('vendor.edit',['id' => $vendor['id']]) }}">Edit</a>)</h1>
<table>
<tr>
<td class='td-2'>Contact Name</td>
<td class='td-3'>{{ $vendor->contact_name}}</td>
</tr>
<tr>
<td class='td-2'>Contact Email</td>
<td class='td-3'>{{ $vendor->contact_email}}</td>
</tr>
<tr>
<td class='td-2'>Created</td>
<td class='td-3'>{{ $vendor->created_at}}</td>
</tr>
<tr>
<td class='td-2'>Updated</td>
<td class='td-3'>{{ $vendor->updated_at}}</td>
</tr>
</table>

{{ Form::open(array('route' => ['vendor.destroy', $vendor->id], 'method' => 'DELETE')) }}
{{ Form::submit('Delete Vendor') }}
{{ Form::close() }}
@endsection