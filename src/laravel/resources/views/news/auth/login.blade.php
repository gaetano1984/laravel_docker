@extends('news.layout')

@section('container')
<div class="row">
	<div class="col-md-3">
		&nbsp;
	</div>
	<div class="col-md-6">
		<form method="post" action="{{route('postLogin')}}">
			@csrf
			<div class="form-group">
				<label for="email" class="label-control">Username</label>
				<input class="form-control" type="text" name="email" id="email" placeholder="username">
				@if($errors->has('email'))
					{{$errors->first('email')}}
				@endif
			</div>
			<div class="form-group">
				<label for="password" class="label-control">Password</label>	
				<input class="form-control" type="password" name="password" id="password">
				@if($errors->has('password'))
					{{$errors->first('password')}}
				@endif
			</div>
			<div class="form-group text-center">
				<input class="btn btn-info" type="submit" value="login">
			</div>
		</form>			
	</div>				
</div>	
@endsection