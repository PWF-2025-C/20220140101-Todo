<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use App\Models\Category;

class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::all();
        // $todos = Todo::where('user_id', auth()->user()->id)
        $todos = Todo::with('category')
            ->where('user_id', auth()->user()->id)
            ->orderBy('is_done','asc')
            ->orderBy('created_at','desc')
            ->get();
        // dd($todos);
        // return view('todo.index', compact('todos'));
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
        ->where('is_done', true)
        ->count();
        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->orderBy('title')->get();   
        return view('todo.create', compact('categories'));
    }

    public function edit(Todo $todo) {
        if (auth()->user()->id == $todo->user_id){
            $categories = Category::where('user_id', Auth::id())->orderBy('title')->get();
            return view('todo.edit', compact('todo', 'categories'));
        }else{
            return redirect()->route('todo.index')->
            with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo){
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
        ]);
        return redirect()->route('todo.index')->
        with('success', 'Todo updated successfully!');
    }

    public function complete(Todo $todo)
    {
        if (Auth::id() == $todo->user_id){
            $todo->update([
                'is_done' => true,
            ]);
            return redirect()->route('todo.index')->
            with('success', 'Todo completed successfully!');
        }
        else {
            return redirect()->route('todo.index')->
            with('danger', 'You are not authorized to complete this todo!');
        }
    }

    public function uncomplete(Todo $todo)
    {
        if (Auth::id() == $todo->user_id){
            $todo->update([
                'is_done' => false,
            ]);
            return redirect()->route('todo.index')->
            with('success', 'Todo uncompleted successfully!');
        }
        else {
            return redirect()->route('todo.index')->
            with('danger', 'You are not authorized to uncomplete this todo!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $todo = todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);
        return redirect()->route('todo.index')->
        with('success', 'todo created successfully.');
    }

    public function destroy(Todo $todo){
        if (auth()  ->user()->id == $todo->user_id){
            $todo->delete();
            return redirect()->route('todo.index')->
            with('success', 'Todo deleted successfully!');
        }else{
            return redirect()->route('todo.index')->
            with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted(){
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_done', true)
            ->get();
        foreach ($todosCompleted as $todo){
            $todo->delete();
        }
        return redirect()->route('todo.index')->
        with('success', 'All completed todos deleted successfully!');
    }
}