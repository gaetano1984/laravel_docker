@extends('news.layout');

@section('container')
	<div class="row">
		<div class="col-md-6">
			<form action="{{route('postRegister')}}" method="post">
				@csrf
				<div class="form-group">
					<label for="username" class="label-control">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Username">
					@if($errors->has('username'))
						{{$errors->first('username')}}
					@endif	
				</div>		
				<div class="form-group">
					<label for="first_name" class="label-control">First Name</label>
					<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
					@if($errors->has('first_name'))
						{{$errors->first('first_name')}}
					@endif
				</div>
				<div class="form-group">
					<label for="last_name" class="label-control">Last Name</label>
					<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
					@if($errors->has('last_name'))
						{{$errors->first('last_name')}}
					@endif
				</div>
				<div class="form-group">
					<label for="Password" class="label-control">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
					@if($errors->has('password'))
						{{$errors->first('password')}}
					@endif
				</div>
				<div class="form-group text-center">
					<input class="btn btn-info" type="submit" value="register">
				</div>
			</form>	
		</div>	
	</div>	
@endsection