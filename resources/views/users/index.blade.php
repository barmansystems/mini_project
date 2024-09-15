@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">کارمندان هلدینگ مشرفی</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="/">داشبورد</a></li>
                            <li class="breadcrumb-item active"><a href="/users">کارمندان</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"></h3>
                                <div class="btn-group-sm float-l">
                                    <a href="/users?company=parsoTejarat" class="btn {{ request('company') == 'parsoTejarat' ? 'btn-primary' : 'btn-light' }}">پرسو تجارت</a>
                                    <a href="/users?company=adakTejarat" class="btn  {{ request('company') == 'adakTejarat' ? 'btn-primary' : 'btn-light' }}">آداک تجارت</a>
                                    <a href="/users?company=adakHamrah" class="btn  {{ request('company') == 'adakHamrah' ? 'btn-primary' : 'btn-light' }}">آداک همراه</a>
                                    <a href="/users?company=barman" class="btn  {{ request('company') == 'barman' ? 'btn-primary' : 'btn-light' }}">بارمان سیستم</a>
                                    <a href="/users?company=sayman" class="btn  {{ request('company') == 'sayman' ? 'btn-primary' : 'btn-light' }}">سایمان داده</a>
                                    <a href="/users?company=adakPetro" class="btn  {{ request('company') == 'adakPetro' ? 'btn-primary' : 'btn-light' }}">آداک پترو</a>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام</th>
                                        <th>نام خانوادگی</th>
                                        <th>سمت</th>
                                        <th>تاریخ ایجاد</th>
                                    </tr>

                                    {{--                                @dd($users)--}}
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{convert_number_to_persian($loop->index + 1)}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->family}}</td>
                                            <td>{{$user->role_name}}</td>
                                            <td>{{convert_number_to_persian(verta($user->created_at)->format('Y/m/d'))}}</td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                            <div class="m-2">{{ $users->appends(request()->all())->links() }}</div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
