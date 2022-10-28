<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;

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
    public static function index()
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

    public static function create()
    {
        $model = new Task();
        $categories = Category::all();

        if (\request('POST')) {

            $model->save();
            return redirect('index');
        } else {
            return view('create', compact('model', 'categories'));
        }
    }

    public static function dateFormat($date){
        $date = new DateTime($date);
        return $date->format("d.m.Y H:i");
    }

    public static function dateEqual($date): bool
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

    public static function isDeadline($deadline)
    {
        if ($deadline > now()){
            return 'yes';
        }
        elseif (HomeController::dateEqual($deadline))
        {
            return 'today';
        }
        else
        {
            return 'no';
        }
    }
}
