@extends('layouts.app')

@section('content')
    <a href="{{ route('create_task') }}" class="btn btn-dark">Создать задачу</a>

<div class="row row-cols-3" style="margin-left: 3rem">
    <div class="col-3" style="text-align: center">
        <h3>Новое</h3>
        @foreach ($new as $task)
        <div class="card">
            <a href="#" class="btn btn-outline-dark">Поменять статус</a>
            <div class="card-body">
                {{ $task->name }}
                <br>
                <a href="#" class="btn btn-outline-secondary position-relative">Удалить</a>
                <a href="#" class="btn btn-outline-secondary position-relative">Открыть задачу</a>
            </div>
        </div>
            <br>
        @endforeach
    </div>
    <div class="col-3 " style="text-align: center">
        <h3>В работе</h3>
        @foreach ($inWork as $task)
        <div class="card">
            <a href="#" class="btn btn-outline-dark">Поменять статус</a>
            <div class="card-body">
                {{ $task->name }}
                <br>
                <a href="#" class="btn btn-outline-secondary position-relative">Удалить</a>
                <a href="#" class="btn btn-outline-secondary position-relative">Открыть задачу</a>
            </div>
        </div>
            <br>
        @endforeach
    </div>
    <div class="col-3" style="text-align: center">
        <h3>На проверке</h3>
        @foreach ($onVerify as $task)
            <div class="card">
                <a href="#" class="btn btn-outline-dark">Поменять статус</a>
                <div class="card-body">
                    {{ $task->name }}
                    <br>
                    <a href="#" class="btn btn-outline-secondary position-relative">Удалить</a>
                    <a href="#" class="btn btn-outline-secondary position-relative">Открыть задачу</a>
                </div>
            </div>
            <br>
        @endforeach
    </div>
    <div class="col-3 align-content-center" style="text-align: center">
        <h3>Готово</h3>
        @foreach ($ready as $task)
            <div class="card">
                <a href="#" class="btn btn-outline-dark">Поменять статус</a>
                <div class="card-body">
                    {{ $task->name }}
                    <br>
                    <a href="#" class="btn btn-outline-secondary position-relative">Удалить</a>
                    <a href="#" class="btn btn-outline-secondary position-relative">Открыть задачу</a>
                </div>
            </div>
            <br>
        @endforeach
    </div>
</div>
@endsection
