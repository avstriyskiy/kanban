@extends('layouts.app')

@section('content')
    <a href="{{ route('tasks.index') }}" class="btn btn-outline-dark mb-3">Вернуться к списку задач</a>
    <div class="row">
        <div class="col-xxl-8">
            <div class="card">
                <div class="card-header text-center">
                    <h2>{{ $task->name }}</h2>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <h5>Описание задачи:</h5>
                            </div>
                            <div class="col">
                                <h5><b>{{ $task->description }}</b></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h5>Контрольный срок исполнения:</h5>
                            </div>
                            <div class="col">
                                @if (Home::isDeadline($task->deadline) == 'today')
                                    <div class="text-bg-warning">
                                    @elseif(Home::isDeadline($task->deadline) == 'yes')
                                    <div class="text-dark">
                                    @else
                                    <div class="text-bg-danger">
                                    @endif
                                        <h5><b>{{ Home::dateFormat($task->deadline) }}</b></h5>
                                    </div>
                            </div>
                            </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Исполнитель задачи:</h5>
                                    </div>
                                    <div class="col">
                                        <h5><b>{{ $category->name }}</b></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Статус задачи:</h5>
                                    </div>
                                    <div class="col">
                                        <h5><b>{{ Home::getStatusName($task->status) }}</b></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5>Файлы задачи:</h5>
                                    </div>
                                    <div class="col">
                                        <form method="POST" action="{{ route('tasks.update', $task) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-3">
                                                <input name="doc" class="form-control" type="file" id="file">
                                            </div>
                                            <button type="submit" class="btn btn-outline-secondary">Добавить</button>
                                        </form>
                                    </div>
                                </div>
                                @if (isset($files))
                                @foreach($files as $file)
                                <div class="row mt-2">
                                    <form method="POST" action="{{ route('tasks.delete', $task) }}">
                                        @csrf
                                        @method('DELETE')
                                        <a  class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#doc_{{$loop->index}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            {{ $file['name'] }}
                                        </a>
                                        <div class="collapse" id="doc_{{$loop->index}}">
                                            <div class="card card-body">
                                                <img src="{{ $file['url'] }}" alt="" class="w-50 h-25">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <input type="hidden" name="file_name" value="{{$file['name']}}" class="w-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                            </svg>
                        </button>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-secondary position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                            </svg>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <h2>Комментарии к задаче</h2>
                </div>
                <div class="card-body">
                    <div class="container">
                        <a  class="btn btn-outline-dark" data-bs-toggle="collapse" href="#add_comment" role="button" aria-expanded="false">Добавить комментарий</a>
                        <div class="collapse" id="add_comment">
                            <div class="card card-body mt-2">
                                <form method="POST" action="{{ route('comments.create', $task) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="comment_body" class="form-label">Текст комментария</label>
                                        <textarea name="body" class="form-control" id="comment_body" rows="3"></textarea>
                                        <label for="comment_body" class="form-label">Максимум 255 знаков</label>
                                    </div>
                                    <button type="submit" class="btn btn-outline-dark">Добавить</button>
                                </form>
                            </div>
                        </div>
                    @if (isset($comments))
                        @foreach($comments as $comment)
                            <div class="card mt-3">
                                <div class="card-body">
                                    {{ $comment->body }}
                                    <div class="row gx">
                                        <div class="col">
                                            <p class="mb-0 mt-3">{{ Home::getUserName($comment) }}</p>
                                        </div>
                                        <div class="col">
                                            <p class="mb-0 mt-3">{{ Home::dateFormat($comment->created_at) }}</p>
                                            <form method="POST" action="{{ route('comments.delete', $comment) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-secondary">
                                                    <input type="hidden" name="comment" value="{{ $comment }}" class="w-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <form method="POST" action="{{ route('tasks.deleteComments', $task) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-dark">
                            <input type="hidden" name="comment" value="{{ $task }}" class="w-0">
                            Удалить все комментарии
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
