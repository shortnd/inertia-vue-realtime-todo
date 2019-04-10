<?php

namespace App\Http\Controllers;

use App\Task;
use Inertia\Inertia;
use App\Events\TaskCreated;
use App\Events\TaskRemoved;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::get();

        return Inertia::render('Todos/Index', ['tasks' => $tasks]);
    }

    public function store(Request $request)
    {
        $task = Task::create($request->only(['title', 'body']));

        broadcast(new TaskCreated($task));

        return response()->json("added");
    }

    public function delete(Task $task)
    {
        broadcast(new TaskRemoved($task));
        $task->delete();
        return response()->json("deleted");
    }
}
