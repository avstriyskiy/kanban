@extends('layouts.app')
@section('title', isset($task) ? 'Изменить задачу "' . $task->name . '"': 'Создать задачу')
@section('content')
    <a href="{{ route('tasks.index') }}" class="btn btn-outline-dark mb-3">Вернуться к списку задач</a>
    <form method="POST"
        @if(isset($task))
            action="{{ route('tasks.update', $task) }}"
        @else
            action="{{ route('tasks.store') }}"
        @endif
        enctype="multipart/form-data">
        @csrf
        @isset($task)
            @method('PATCH')
        @endisset
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input
                value="{{ isset($task) ? $task->name : null }}"
                name="name" type="text" class="form-control" id="name">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <input value="{{ isset($task) ? $task->description : null }}"
                name="description" type="text" class="form-control" id="description">
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Контрольный срок выполнения задачи</label>
            <input value="{{ isset($task) ? $task->deadline : null }}"
                name="deadline" type="datetime-local" class="form-control" id="deadline">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Исполнитель</label>
            @if(!isset($task) or $user->category_id == 1)
                <select name="category_name" id="category" class="form-select" {{ $user->category_id == 1 ? '' : 'disabled' }}>
                    @foreach ($categories as $category)
                            <option>{{ $category->name }}</option>
                    @endforeach
            @else
                <select name="category_name" id="category" class="form-select" disabled>
                    @foreach ($categories as $category)
                        @if ($category->id == $user->category_id)
                            <option>{{ $category->name }}</option>
                        @endif
                    @endforeach
            @endif
            </select>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Добавить файл к задаче</label>
            <input name="doc" class="form-control" type="file" id="file">
        </div>
        <button type="submit" class="btn btn-outline-dark">Submit</button>
    </form>
@endsection
