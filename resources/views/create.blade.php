@extends('layouts.app')
@section('content')
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <input type="text" class="form-control" id="description">
        </div>
        <div class="mb-3">
            <label for="manager" class="form-label">Исполнитель</label>
            <select id="manager" class="form-select">
                @foreach ($categories as $category)
                    <option>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="checkbox">
            <label class="form-check-label" for="checkbox">Check box</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
