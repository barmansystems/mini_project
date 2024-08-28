@extends('layouts.master')
@section('css-styles')
    <link rel="stylesheet" href="{{asset('/dist/clockpicker/bootstrap-clockpicker.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('/dist/datepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/dist/datepicker-jalali/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/select2/select2.min.css')}}">

@endsection
@section('content')
    <div class="content-wrapper p-4">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">تغییر وظیفه</h1>
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
        <div class="col-12 card">
            <div class="p-3">
                <form action="{{ route('tasks.update',$task->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <div class="mb-2">
                                <label for="title" class="form-label">عنوان <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title"
                                       value="{{ old('title',$task->title) }}">
                                @error('title')
                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-8"></div>
                            <div class="mb-2">
                                <label for="description" class="form-label">توضیحات <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" class="form-control" name="description"
                                          id="description"
                                          rows="5">{{ old('description',$task->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-8"></div>
                            <div class="mb-2">
                                <label for="start_at" class="form-label">تاریخ شروع <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="start_at"
                                       class="form-control date-picker-shamsi-list"
                                       id="start_at" value="{{ old('start_at',verta($task->start_at)->format('Y/m/d')) }}" autocomplete="off">
                                @error('start_at')
                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="expire_at" class="form-label">تاریخ انقضا <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="expire_at"
                                       class="form-control date-picker-shamsi-list"
                                       id="expire_at" value="{{ old('expire_at',verta($task->expire_at)->format('Y/m/d')) }}" autocomplete="off">
                                @error('expire_at')
                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-8"></div>
                            <div class="mb-2">
                                <label for="users" class="mb-1">تخصیص به</label>
                                <select class="form-control" data-toggle="select2" name="users[]"
                                        id="user_select"
                                        multiple>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{$user->id}}"  {{ $task->users->pluck('id')->toArray() ? (in_array($user->id, $task->users->pluck('id')->toArray()) ? 'selected' : '') : '' }}>{{$user->name.' '.$user->family}} - {{company_name($user->company_name)}}</option>
                                    @endforeach
                                </select>
                                @error('expire_at')
                                <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">ثبت فرم</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js-script')
    <script src="{{asset('/dist/datepicker-jalali/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('/dist/datepicker-jalali/bootstrap-datepicker.fa.min.js')}}"></script>
    <script src="{{asset('/dist/datepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/dist/js/datepicker.js')}}"></script>
    <script src="{{asset('/dist/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
    <script src="{{asset('/dist/js/clockpicker.js')}}"></script>
    <script src="{{asset('/plugins/select2/select2.min.js')}}"></script>
    <script>
        $('#user_select').select2({
            placeholder: 'انتخاب کنید',
            multiple: true,
            dir:'rtl'
        });
    </script>
@endsection
