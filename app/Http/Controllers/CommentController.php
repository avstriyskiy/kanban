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

    public function update(Request $request, Task $task)
    {

        foreach ($task->comments as $comment){
            if ($comment->body == $request->body){
                $task->comments()->update([
                    'body' => $request->body,
                ]);

                break;
            }
        }

        return redirect()->route('tasks.show', $task->id);
    }

    public function delete(Request $request, Comment $comment)
    {
        $task = $comment->commentable_id;
        Comment::destroy($comment->id);

        return redirect()->route('tasks.show', $task);
    }


}
