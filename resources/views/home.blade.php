@extends('layouts.app')

@section('content')
    <a href="#" class="btn btn-dark">Создать задачу</a>

<div class="row row-cols-3" style="margin-left: 3rem">
    <div class="col-3" style="text-align: center">
        <h3>Новое</h3>
        <div class="card">
            <a href="#" class="btn btn-outline-dark">Поменять статус</a>
            <div class="card-body">
                СДЕЛАТЬ КАКАШКИ
            </div>
        </div>
    </div>
    <div class="col-3 " style="text-align: center">
        <h3>В работе</h3>
        <div class="card">
            <a href="#" class="btn btn-outline-dark">Поменять статус</a>
            <div class="card-body">
                СДЕЛАТЬ КАКАШКИ
            </div>
        </div>
        <div class="card">
            <a href="#" class="btn btn-outline-dark">Поменять статус</a>
            <div class="card-body">
                СДЕЛАТЬ ПОНОС
            </div>
        </div>
    </div>
    <div class="col-3" style="text-align: center">
        <h3>На проверке</h3>
        <div class="card">
            <a href="#" class="btn btn-outline-dark">Поменять статус</a>
            <div class="card-body">
                СДЕЛАТЬ КАКАШКИ
            </div>
        </div>
    </div>
    <div class="col-3 align-content-center" style="text-align: center">
        <h3>Готово</h3>
        <div class="card">
            <a href="#" class="btn btn-outline-dark">Поменять статус</a>
            <div class="card-body">
                СДЕЛАТЬ КАКАШКИ
            </div>
        </div>
    </div>
</div>
@endsection
