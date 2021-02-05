@extends('layout')

@section('container')
	<table class="table table-striped">
		<thead>
			<tr>
				<th colspan=3>
					<select name="giornale" id="giornale" class="form-control col-md-3">
						<option value=""></option>
						@foreach($sources as $source_temp)
							@if($source_temp==$giornale)
								<option value="{{$source_temp}}" selected>{{$source_temp}}</option>
							@else
								<option value="{{$source_temp}}">{{$source_temp}}</option>
							@endif
						@endforeach
					</select>
				</th>
			</tr>
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
			{{ $news->appends(request()->query())->links() }}
		</div>
	</div>
@endsection

@section('extrascripts')
	<script type="text/javascript">
		$('#giornale').on('change', function(){
			url="{{urldecode($url)}}";
			if(url.indexOf('giornale')>=0){
				url=url.replace(/&amp;/, '&');
				url=url.replace(/giornale=(.+?)$/, 'giornale='+$(this).val());
			}
			else{
				url=url+'&giornale='+$(this).val();
			}
			window.location.replace(url);
		});
	</script>	
@endsection