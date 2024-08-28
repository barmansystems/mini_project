@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">صفحه</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="/">خانه</a></li>
                            <li class="breadcrumb-item"><a href="/tasks">وظایف</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tr>
                                    <th>ردیف</th>
                                    <th>عنوان</th>
                                    <th>ایجاد کننده</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>مشاهده</th>
                                    <th>ویرایش</th>
                                    <th>حذف</th>
                                </tr>
                                @foreach($tasks as $key => $task)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->creator_id == auth()->id() ? 'شما' : $task->creator->fullName() }}</td>
                                        <td>{{ verta($task->created_at)->format('H:i - Y/m/d') }}</td>
                                        <td>
                                            <a class="btn btn-info btn-floating" href="{{ route('tasks.show', $task->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        @can('tasks-edit')
                                            <td>
                                                <a class="btn btn-warning btn-floating {{ $task->creator_id != auth()->id() ? 'disabled' : '' }}"
                                                   href="{{ route('tasks.edit', $task->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @endcan
                                        @can('tasks-delete')
                                            <td>
                                                <button class="btn btn-danger btn-floating trashRow"
                                                        data-url="{{ route('tasks.destroy',$task->id) }}"
                                                        data-id="{{ $task->id }}" {{ $task->creator_id != auth()->id() ? 'disabled' : '' }}>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach

                                {{--                                @dd($users)--}}

                            </table>

                        </div>
                        <div class="m-2">{{ $tasks->appends(request()->all())->links() }}</div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
