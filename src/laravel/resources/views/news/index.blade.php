@extends('layout')

@section('container')
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 15%;">Data</th>
				<th>titolo</th>
				<th>giornale</th>
			</tr>
		</thead>
		<tbody>	
		@foreach($news as $n)
			<tr>
				<td>{{date('d-m-Y', strtotime($n->pubDate))}}</td>
				<td><a href="{{$n->guid}}">{{$n->title}}</a></td>
				<td>{{$n->giornale}}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	<div class="row">
		<div class="col-md-12 text-center">
			{{ $news->links() }}
		</div>
	</div>
@endsection