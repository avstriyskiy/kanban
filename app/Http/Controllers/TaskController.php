<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;

class TaskController extends Controller
{
    /**
     *  Make an auth for pages
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        $task = Task::create([
            'name' => request()->name,
            'description' => request()->description,
            'deadline' => $deadline,
            'status' => 1,
            'category_id' => $categoryId,
        ]);

        if (request()->hasFile('doc'))
        {
            $fileName = $request->file('doc')->getClientOriginalName();
            $request->file('doc')->storeAs('/', $fileName);

            $task->attaches()->create([
                    'file_name' => $fileName
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
        foreach ($task->attaches as $document)
        {
            $files[] = [
                'url' => Storage::url($document->file_name),
                'name' => $document->file_name
            ];
        }

        $comments = [];

        return view('show', compact('task', 'category', 'files', 'comments'));
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
        if (request()->hasFile('doc'))
        {
            $fileName = $request->file('doc')->getClientOriginalName();
            $request->file('doc')->storeAs('/', $fileName);

            $task->attaches()->create([
                'file_name' => $fileName,
            ]);
        }

        if ($request->has('name') || $request->has('deadline') || $request->has('description')){

            $deadline = new DateTime(request()->deadline);
            $deadline = $deadline->format('Y-m-d h:i:s');

            $task->update([
                'name' => request()->name,
                'description' => request()->description,
                'deadline' => $deadline,
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
        foreach ($task->attaches as $file){
            Storage::delete($file->file_name);
            Document::destroy($file->id);
        }

        Task::destroy($task->id);

        return redirect()->route('tasks.index');
    }

    public function delete(Request $request, Task $task)
    {
        $file = Document::where('file_name', $request->file_name)->first();

        Storage::delete($file->file_name);
        Document::destroy($file->id);

        return redirect()->route('tasks.show', $task->id);
    }

    public function change(Request $request, Task $task)
    {
        $task->update([
            'status' => $this->getStatusNum(request()->status)
        ]);

        return redirect()->route('tasks.index');
    }

    public static function dateFormat($date){
        $date = new DateTime($date);
        return $date->format("d.m.Y H:i");
    }

    public static function dateEqual($date): bool
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

    public static function isDeadline($deadline)
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

    public static function getStatusName($status){
        $statusName = [1 => 'Новое', 2 => 'В работе', 3 => 'На проверке', 4 => 'Готово'];
        return $statusName[$status];
    }

    public static function getStatusNum($status){
        $statusNum = ['Новое' => 1, 'В работе' => 2, 'На проверке' => 3, 'Готово' => 4];
        return $statusNum[$status];
    }

}
