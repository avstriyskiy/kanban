@extends('layouts.app')
@section('content')
    <h3 class="text-center">Добро пожаловать в CRM для бедных, выберите нужный Вам раздел!</h3>
    <div class="row mt-5">
        <div class="col col-md">
            <div class="card">
                <div class="card card-body text-center">
                    <h4>Справочник пользователей</h4>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-dark mx-auto mt-2">Перейти</a>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col">
            <div class="card col-md">
                <div class="card card-body text-center">
                    <h4>Канбан</h4>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-dark mx-auto mt-2">Перейти</a>
                </div>
            </div>
        </div>
    </div>
@endsection
