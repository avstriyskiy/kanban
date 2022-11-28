@extends('layouts.app')
@section('content')
    <div class="card card-body shadow-lg">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th></th>
                    <th>ФИО</th>
                    <th>E-mail</th>
                    <th>Должность</th>
                    <th>Подразделение</th>
                    <th>Отдел</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><a href="#">
                            @if (isset($user->image))
                                {{ $user->image}}
                            @else
                                <img src="{{ Storage::url('profile_pics/default.png') }}" width="64" height="64" />
                            @endif
                        </a></td>
                        <td><a href="#">{{ $user->name }}</a></td>
                        <td><a href="#">{{ $user->email }}</a></td>
                        <td><a href="#">{{ $user->position }}</a></td>
                        <td><a href="#">{{ $user->category->name }}</a></td>
                        <td><a href="#">{{ $user->department->name }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-2">{{ $users->links() }}</div>
    </div>
@endsection
