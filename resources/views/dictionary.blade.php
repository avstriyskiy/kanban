@extends('layouts.app')
@section('content')
    <div class="card card-body shadow-lg">
        <table class="table table-borderless mx-5">
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>E-mail</th>
                    <th>Должность</th>
                    <th>Отдел</th>
                    <th>Подразделение</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->position }}</td>
                    <td>{{ $user->category_id }}</td>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
