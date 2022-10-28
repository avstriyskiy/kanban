<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use DateTime;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $new = Task::where('status', 1)->get();
        $inWork = Task::where('status', 2)->get();
        $onVerify = Task::where('status', 3)->get();
        $ready = Task::where('status', 4)->get();
        return view('home', compact('ready', 'new', 'inWork', 'onVerify'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();

        return view('form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $categories = Category::get();

        return view('form', compact('categories', 'task'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Task $task)
    {
        return redirect(route('tasks.index'));
    }

    public function dateFormat($date){
        $date = new DateTime($date);
        return $date->format("d.m.Y H:i");
    }

    public function dateEqual($date): bool
    {
        $date = new DateTime($date);
        $date = intval($date->format('d'));
        $today = new DateTime(now());
        $today = intval($today->format('d'));
        if ($date == $today)
        {
            return True;
        }
        else
        {
            return False;
        }
    }

    public function isDeadline($deadline)
    {
        if ($deadline > now()){
            return 'yes';
        }
        elseif (TaskController::dateEqual($deadline))
        {
            return 'today';
        }
        else
        {
            return 'no';
        }
    }
}
