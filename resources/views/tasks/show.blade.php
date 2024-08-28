@extends('layouts.master')
@section('content')
    @php
        $isCreator = $task->creator_id == auth()->id();
        if (!$isCreator){
            $task_user = \Illuminate\Support\Facades\DB::table('task_user')->where(['task_id' => $task->id, 'user_id' => auth()->id()])->first();
            $task_done = $task_user->status == 'done' ? true : false;
        }
    @endphp

        <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="descriptionModal">
                    <h6 class="modal-title mb-2">توضیحات</h6>
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

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
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">مشاهده وظیفه "{{ $task->title }}"</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex justify-content-between align-items-center">

                                </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="table-responsive">
                                                <table class="table table-striped text-center table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>نام و نام خانوادگی</th>
                                                        <th>وضعیت</th>
                                                        <th>زمان انجام</th>
                                                        <th>توضیحات</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($task->users as $user)
                                                        <tr>
                                                            <td>{{ $user->fullName() }}</td>
                                                            <td>
                                                                @if($user->pivot->status == 'done')
                                                                    <span
                                                                        class="badge bg-success">{{ \App\Models\Task::STATUS[$user->pivot->status] }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-warning">{{ \App\Models\Task::STATUS[$user->pivot->status] }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $user->pivot->done_at ? convert_number_to_persian( verta($user->pivot->done_at)->format('H:i - Y/m/d')) : '---' }}</td>
                                                            <td>
                                                                <button
                                                                    class="btn btn-primary btn-floating btn_show_desc"
                                                                    data-toggle="modal"
                                                                    data-target="#exampleModalCenter"
                                                                    data-id="{{ $user->pivot->id }}" {{ $user->pivot->description ? '' : 'disabled' }}>
                                                                    <i class="fa fa-comment"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-script')
    <script>
        var task_id = "{{ $task->id }}";

        $(document).ready(function () {
            $(document).on('click', '.btn_show_desc', function () {
                let pivot_id = $(this).data('id');
                console.log(pivot_id)
                $.ajax({
                    url: `/api/get-task-desc`,
                    type: 'post',
                    data: {
                        pivot_id
                    },
                    success: function (res) {
                        $('#descriptionModal p').text(res.data)
                    }
                });
            });

        });
    </script>
@endsection


