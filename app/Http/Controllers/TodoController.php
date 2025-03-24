<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Auth;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(){
        // $todos = Todo::all();
        $todos = Todo::where('user_id', Auth::id())->get();
        dd($todos);
       return view('todo.index'); 
    }

}