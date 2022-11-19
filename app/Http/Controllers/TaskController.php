<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Mail\TaskOverdue;
use App\Models\Category;
use App\Models\Task;
use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    /**
     *  Make an auth
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        // Проверяем текущего юзера, если админ, то выводим всё, если нет, то только то, что нужно
        $all_tasks = [];

        foreach (Task::STATUSES_NUM as $status){

            $query = Task::query()->where('status', '=', $status);

            if (\Auth::user()->category_id != 1){
                $query->where('category_id', '=', \Auth::user()->category_id);
            }

            $all_tasks[$status] = $query->get();
        }

        return view('home', compact('all_tasks'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        // Получаем нужные категории для создания задачи
        if (\Auth::user()->category_id != 1){
            $categories = Category::where('id', \Auth::user()->category_id)->get();
        } else {
            $categories = Category::get();
        }

        return view('form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTaskRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     * @throws \Exception
     */
    public function store(StoreTaskRequest $request)
    {
        // Получаем ID категории по названию из Request
        $categoryId = Category::where('name', '=', $request->category_name)->first()->id;

        // Форматируем дату и время так, чтобы можно было внести данные в БД
        $deadline = new Carbon($request->deadline);


        // Записываем данные в БД
        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'deadline' => $deadline->toDateTimeString(),
            'status' => 1,
            'category_id' => $categoryId,
        ]);

        // Проверяем добавил ли пользователь файл при создании задачи
        if ($request->hasFile('doc'))
        {
            // Сохраняем полученный файл
            Document::saveFile($request->file('doc'), $task, '/');
        }

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Task $task)
    {
//        dd($task->attaches());
        if (\Auth::user()->category_id != 1 && $task->category_id != \Auth::user()->category_id){
            abort(403, 'Вы не имеете доступа к просмотру этой задачи');
        }

        $category = Category::find($task->category_id);

        $comments = $task->comments->sortByDesc('created_at');

        return view('show', compact('task', 'category', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $categories = Category::get();

        return view('form', compact('categories', 'task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreTaskRequest $request
     * @param Task $task
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        if ($request->hasFile('doc'))
        {
            Document::saveFile($request->file('doc'), $task, '/');
        }

        if ($request->has('name') || $request->has('deadline') || $request->has('description')){

            $deadline = new DateTime($request->deadline);
            $deadline = $deadline->format('Y-m-d H:i:s');

            $task->update([
                'name' => $request->name,
                'description' => $request->description,
                'deadline' => $deadline,
            ]);
        }

        return redirect()->route('tasks.show', $task->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Task $task)
    {
        // Удаляем все файлы задачи
        Document::deleteAllFiles($task);

        // Удаляем все комментарии задачи
        $task->comments()->delete();

        // Удаляем данные об отправке email о просрочке задачи для экономии места в базе
        $task->mail()->delete();

        // Удаляем саму задачу
        $task->delete();

        return redirect()->route('tasks.index');
    }

    /**
     * Удаление документа у задачи
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function delete(Request $request, Task $task)
    {
        Document::deleteFile($task, $request->doc);

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Удаление всех комментариев у задачи
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function deleteComments (Request $request, Task $task)
    {
        $task->comments()->delete();

        return redirect()->route('tasks.show', $task);
    }

    /**
     * Изменение статуса задачи
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function change(Request $request, Task $task)
    {
        $task->update([
            'status' => Task::STATUSES_NUM[$request->status]
        ]);

        return redirect()->route('tasks.index');
    }
}
