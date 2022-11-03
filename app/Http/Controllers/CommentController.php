<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Category;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;



class CommentController extends Controller
{
    public function create(Request $request, Task $task)
    {
        $task->comments()->create([
           'body' => $request->body,
           'user_id' => $request->user()->id,
        ]);

        return redirect()->route('tasks.show', $task);
    }

    public function delete(Comment $comment)
    {
        $task = $comment->commentable_id;
        $comment->delete();

        return redirect()->route('tasks.show', $task);
    }


}
