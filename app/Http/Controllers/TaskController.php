<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Category;
use App\Models\Task;
use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;
use DateTime;

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
        $user = User::find(auth()->id());
        $condition = '';

        if ($user->category_id != 1){
            $condition = " AND category_id=$user->category_id";
        }

        $new = Task::whereRaw('status=1' . $condition)->get();
        $inWork = Task::whereRaw('status=2' . $condition)->get();
        $onVerify = Task::whereRaw('status=3' . $condition)->get();
        $ready = Task::whereRaw('status=4' . $condition)->get();

        return view('home', compact('ready', 'new', 'inWork', 'onVerify', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        // Получаем нужные категории для создания задачи
        $user = User::find(auth()->id());

        if ($user->category_id != 1){
            $categories = Category::where('id', $user->category_id)->get();
        } else {
            $categories = Category::get();
        }

        return view('form', compact('categories', 'user'));
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
        $deadline = new DateTime($request->deadline);
        $deadline = $deadline->format('Y-m-d H:i:s');

        // Записываем данные в БД
        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'deadline' => $deadline,
            'status' => 1,
            'category_id' => $categoryId,
        ]);

        // Проверяем добавил ли пользователь файл при создании задачи
        if ($request->hasFile('doc'))
        {
            // Сохраняем файл на сервере под исходным именем
            $fileName = $request->file('doc')->getClientOriginalName();
            $request->file('doc')->storeAs('/', $fileName);

            // Записываем данные о файле в базу данных
            $task->attaches()->create([
                    'file_name' => $fileName,
                    'file_url' => Storage::url($fileName)
                ]);
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
        // Проверка доступа к таску
        $user = User::find(auth()->id());

        if ($user->category_id != 1 && $task->category_id != $user->category_id){
            abort(403, 'Вы не имеете доступа к просмотру этой задачи');
        }

        $category = Category::find($task->category_id);

        $files = $task->attaches;

        $comments = $task->comments->sortByDesc('created_at');

        return view('show', compact('task', 'category', 'files', 'comments', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        // Получаем текущего юзера
        $user = User::find(auth()->id());

        $categories = Category::get();

        return view('form', compact('categories', 'task', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreTaskRequest $request
     * @param Task $task
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, Task $task)
    {
        if ($request->hasFile('doc'))
        {
            $fileName = $request->file('doc')->getClientOriginalName();
            $request->file('doc')->storeAs('/', $fileName);

            $task->attaches()->create([
                'file_name' => $fileName,
                'file_url' => Storage::url($fileName)
            ]);
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
        foreach ($task->attaches as $file){
            Storage::delete($file->file_name);
            Document::destroy($file->id);
        }

        // Удаляем все комментарии задачи
        $task->comments()->delete();

        // Удаляем данные об отправке email о просрочке задачи
        $task->mail()->delete();

        // Удаляем саму задачу
        Task::destroy($task->id);

        return redirect()->route('tasks.index');
    }

    public function delete(Request $request, Task $task, Document $document)
    {
        $document = $task->attaches()->find($request->doc);

        Storage::delete($document->file_name);
        $document->delete();

        return redirect()->route('tasks.show', $task);
    }

    public function deleteComments (Request $request, Task $task)
    {
        $task->comments()->delete();

        return redirect()->route('tasks.show', $task);
    }

    public function change(Request $request, Task $task)
    {
        $task->update([
            'status' => $this->getStatusNum($request->status)
        ]);

        return redirect()->route('tasks.index');
    }

    public static function dateFormat($date){
        $date = new DateTime($date);
        return $date->format("d.m.Y H:i");
    }

    public static function dateEqual($date, $today): bool
    {
        $date = $date->format('j');
        $today = $today->format('j');

        if (intval($date) == intval($today))
        {
            return True;
        } else {
            return False;
        }
    }

    public static function isDeadline($deadline)
    {
        $deadline = new DateTime($deadline);
        $today = new DateTime(now(new \DateTimeZone('Europe/Moscow')));

        if ($deadline > $today){
            if (TaskController::dateEqual($deadline, $today)){
                return 'today';
            }
            return 'yes';
        } elseif (TaskController::dateEqual($deadline, $today)){
            if ($deadline < $today){
                return 'no';
            }
            return 'today';
        } else {
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

    public static function getUserName($comment)
    {
        $user = User::find($comment->user_id);
        return $user->name;
    }
}
