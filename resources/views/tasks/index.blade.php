@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">لیست وظایف</h1>
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
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-title d-flex justify-content-end mb-2">
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus mr-2"></i>
                                ایجاد وظیفه
                            </a>
                        </div>

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

                                    <tbody>
                                    @foreach($tasks as $key => $task)
                                        <tr>
                                            <td>{{ convert_number_to_persian(++$key) }}</td>
                                            <td>{{ $task->title }}</td>
                                            <td>{{ $task->creator_id == auth()->id() ? 'شما' : $task->creator->fullName() }}</td>
                                            <td>{{ convert_number_to_persian(verta($task->created_at)->format('H:i - Y/m/d')) }}</td>
                                            <td>
                                                <a class="btn btn-info btn-floating"
                                                   href="{{ route('tasks.show', $task->id) }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-warning btn-floating {{ $task->creator_id != auth()->id() ? 'disabled' : '' }}"
                                                   href="{{ route('tasks.edit', $task->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-floating trashRow"
                                                        data-toggle="modal"
                                                        data-target="#exampleModalCenter"
                                                        data-id="{{ $task->id }}" {{ $task->creator_id != auth()->id() ? 'disabled' : '' }}>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="m-2">{{ $tasks->appends(request()->all())->links() }}</div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-2 text-center">حذف</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body" id="descriptionModal">
                        <h5 class="text-center">آیا این وظیفه حذف شود؟</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-danger" data-dismiss="modal">حذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js-script')
    <script>
        $(document).on('click', '.trashRow', function () {
            console.log($(this).data('id'));
        });
    </script>
@endsection

