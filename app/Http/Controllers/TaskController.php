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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $categoryId = Category::where('name', '=', $request['category_name'])->first()->id;
        $deadline = new DateTime($request['deadline']);
        $deadline = $deadline->format('Y-m-d h:i:s');
//        dd($request->only(['name', 'description', 'deadline' , 'category_name']));
        Task::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'deadline' => $deadline,
                'status' => 1,
                'category_id' => $categoryId,
            ]);

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $category = Category::find($task->category_id);
        return view('show', compact('task', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $categories = Category::get();

        return view('form', compact('categories', 'task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {

        $deadline = new DateTime($request['deadline']);
        $deadline = $deadline->format('Y-m-d h:i:s');

        $task->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'deadline' => $deadline,
        ]);

        return redirect()->route('tasks.show', $task->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Task $task)
    {
        Task::destroy($task->id);
        return redirect()->route('tasks.index');
    }

    public function change(Request $request, Task $task)
    {
//        dd($request->all());
        $status = ['Новое' => 1, 'В работе' => 2, 'На проверке' => 3, 'Готово' => 4];
        $statusId = $status[$request['status']];

        $task->update([
            'status' => $statusId
        ]);

        return redirect()->route('tasks.index');
    }

    public function dateFormat($date){
        $date = new DateTime($date);
        return $date->format("d.m.Y H:i");
    }

    public function dateEqual($date): bool
    {
        $date = new DateTime($date);
        $date = intval($date->format('j'));
        $today = new DateTime(now());
        $today = intval($today->format('j'));
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
        if (TaskController::dateEqual($deadline)){
            return 'today';
        }
        elseif ($deadline > now())
        {
            return 'yes';
        }
        else
        {
            return 'no';
        }
    }

    public function getStatusName($status){
        $statusName = [1 => 'Новое', 2 => 'В работе', 3 => 'На проверке', 4 => 'Готово'];
        return $statusName[$status];
    }
}
