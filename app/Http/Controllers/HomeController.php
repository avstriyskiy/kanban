<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
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
     * Make new task
     */

    public function create()
    {
        $model = new Task();

        if (\request('POST')) {
            $model->save();
            return redirect('index');
        } else {
            return view('create', compact($model));
        }

    }
}
