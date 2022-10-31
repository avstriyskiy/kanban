<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;
use PhpParser\Comment\Doc;

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

        $categoryId = Category::where('name', '=', request()->category_name)->first()->id;
        $deadline = new DateTime(request()->deadline);
        $deadline = $deadline->format('Y-m-d h:i:s');

        if (request()->hasFile('doc'))
        {
            $task = Task::create([
                    'name' => request()->name,
                    'description' => request()->description,
                    'deadline' => $deadline,
                    'status' => 1,
                    'category_id' => $categoryId,
                    'has_files' => 1,
                ]);

            $fileName = $request->file('doc')->getClientOriginalName();
            $request->file('doc')->storeAs('/', $fileName);

            Document::create([
                'file_name' => $fileName,
                'task_id' => $task->id
            ]);
        } else {
            Task::create([
                'name' => request()->name,
                'description' => request()->description,
                'deadline' => $deadline,
                'status' => 1,
                'category_id' => $categoryId,
                'has_files' => 0,
            ]);
        }

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

        $files = [];
        $documents = Document::where('task_id', $task->id)->get();
        foreach ($documents as $document)
        {
            $files[] = ['url' => Storage::url($document->file_name),
                'name' => $document->file_name
            ];
        }

        return view('show', compact('task', 'category', 'files'));
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
        $hasFiles = $task->has_files;
        if (request()->hasFile('doc'))
        {
            $fileName = $request->file('doc')->getClientOriginalName();
            $request->file('doc')->storeAs('/', $fileName);

            Document::create([
                'file_name' => $fileName,
                'task_id' => $task->id
            ]);

            $hasFiles += 1;
            $task->update([
               'has_files' => $hasFiles
            ]);

        }
        if($request->has('name'))
        {
            $task->update([
                'name' => $request['name'],
                'description' => $request['description'],
                'deadline' => $deadline,
                'has_files' => $hasFiles,
            ]);
        }


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
        if ($task->has_files >= 1)
        {
            $files = Document::where('task_id', $task->id)->get();
            foreach ($files as $file){
                Storage::delete($file->file_name);
                Document::destroy($file->id);
            }
        }
        Task::destroy($task->id);

        return redirect()->route('tasks.index');
    }

    public function delete(Request $request, Task $task)
    {
        $file = Document::where('file_name', $request->file_name)->first();

        Storage::delete($file->file_name);
        Document::destroy($file->id);
        $hasFiles = $task->has_files - 1;
        $task->update([
            'has_files' => $hasFiles
        ]);

        return redirect()->route('tasks.show', $task->id);
    }

    public function change(Request $request, Task $task)
    {
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
