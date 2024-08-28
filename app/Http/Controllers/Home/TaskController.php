<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $tasks = Task::where('creator_id', $userId)->orWhereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->orderByDesc('created_at')
            ->paginate(30);
        return view('tasks.index', compact('tasks'));


    }
}
