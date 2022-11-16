<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Task;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Task $task, StoreDocumentRequest $request){

        Document::saveFile($request->file('doc'), $task, '/');

        return redirect()->route('tasks.show', $task);
    }
}
