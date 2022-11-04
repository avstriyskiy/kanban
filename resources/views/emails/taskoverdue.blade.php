<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body class="bg-light">
<div class="container">
    <div class="card my-10">
        <div class="card-body">
            <h1 class="h3 mb-2">Задача <b>{{ $taskName }}</b> просрочена</h1>
            <h5 class="text-teal-700">Перейдите на сайт, чтобы закончить выполнение задачи</h5>
            <hr>
            <div class="space-y-3">
                <p class="text-gray-700">
                    Просмотреть задачу вы можете по кнопке ниже
                </p>
            </div>
            <hr>
            <a class="btn btn-primary" href="{{ route('tasks.show', $taskId) }}" target="_blank">Завершить свои задачи</a>
        </div>
    </div>
</div>
</body>
</html>
