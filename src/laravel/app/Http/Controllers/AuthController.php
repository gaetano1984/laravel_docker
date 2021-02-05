<?php

namespace App\Http\Controllers;

use App\Models\News\NewsUser;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(){
    	return view('news.auth.login');
    }
    public function register(){
    	return view('news.auth.register');
    }
    public function postLogin(LoginRequest $request){
    	$credentials = $request->only('email', 'password');
    	if(Auth::attempt($credentials, TRUE)){
    		return redirect('news');
    	}    	
    }
    public function postRegister(RegisterRequest $request){
		$u = new NewsUser();
		$u->name = $request->get('first_name')." ".$request->get('last_name');
		$u->email = $request->get('username');
		$u->password = Hash::make($request->get('password'));
		$u->save();
		return redirect('login');
    }
}
