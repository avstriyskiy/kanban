<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Category;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;



class CommentController extends Controller
{
    public function create(Request $request, Task $task, User $user)
    {
        $task->comments()->create([
           'body' => $request->body,
           'user_id' => $user->id,
        ]);

        return redirect()->route('tasks.show', $task->id);
    }

    public function update(Request $request, Task $task)
    {

    }


}
