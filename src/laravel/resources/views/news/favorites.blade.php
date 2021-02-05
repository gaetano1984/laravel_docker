@extends('layout')

@section('container')
	<form action="{{route('postFavorite')}}" method="POST">
	<div class="row">
		<div class="col-md-6">
			@csrf
			<table class="table table-striped">
				<thead>
					<tr>
						<th style="width:3%;"></th>
						<th>Quotidiano</th>
					</tr>
				</thead>
				<tbody>
					@foreach($obj as $o)
						<tr>
							<td><input type="checkbox" name="favorites[]" id="favorites[]" value="{{$o['source']}}" 
								@if($o['favorites']==1) checked @endif></td>
							<td>{{$o['source']}}</td>
						</tr>
					@endforeach	
				</tbody>	
			</table>	
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 text-center">
			<input type="submit" class="btn btn-info" value="Salva">
		</div>
	</div>
</form>				
@endsection