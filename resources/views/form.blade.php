@extends('layouts.app')
@section('content')
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input name="name" type="text" class="form-control" id="name">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <input name="description" type="text" class="form-control" id="description">
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Контрольный срок выполнения задачи</label>
            <input name="deadline" type="datetime-local" class="form-control" id="deadline">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Исполнитель</label>
            <select id="category" class="form-select">
                @foreach ($categories as $category)
                    <option>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Добавить файл к задаче</label>
            <input class="form-control" type="file" id="file" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
