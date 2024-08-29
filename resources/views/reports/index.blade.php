@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">لیست گزارشات</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item">
                                <a href="/">خانه</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/reports">گزارشات</a>
                            </li>
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
                            <div class="card-body table-responsive p-0">
                                <div class="btn-group-sm p-3">
                                    <button id="perso-tejarat" type="button" class="btn btn-primary">
                                        پرسو تجارت
                                    </button>
                                    <button id="adak-tejarat" type="button" class="btn btn-light">
                                        آداک تجارت
                                    </button>
                                </div>
                                <table class="table table-hover">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>همکار</th>
                                        <th>سمت</th>
                                        <th>تاریخ گزارش</th>
                                        <th>تاریخ ثبت</th>
                                        <th>مشاهده</th>
                                    </tr>

                                    <tbody id="reports">

                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination m-2">
                                <button id="prev-page" style="display: none;">قبلی</button>
                                <button id="next-page" style="display: none;">بعدی</button>
                            </div>
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
                <form action="" method="post" id="myForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center" id="descriptionModal">
                        <i class="fa fa-trash text-danger mb-2" style="font-size: 3rem"></i>
                        <h5 class="text-center">آیا این وظیفه حذف شود؟</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js-script')
    <script src="{{asset('plugins/jdate/jdate.js')}}"></script>
    <script>
        $(document).ready(function () {
            var currentPage = 1;
            var apiUrl = 'http://127.0.0.1:7000/api/get-reports';
            var tbody = $('#reports');

            function fetchReports(page) {
                $.ajax({
                    url: apiUrl,
                    type: 'POST',
                    data: {page: page},
                    success: function (response) {
                        var reports = response.data;
                        tbody.empty();
                        console.log(response);
                        $.each(reports, function (index, report) {
                            tbody.append(`
                    <tr>
                        <td>${(index + 1) + (page - 1) * 10}</td>
                        <td>${report.user.name} ${report.user.family}</td>
                        <td>${report.user.role.label}</td>
                        <td>${moment(report.date , 'YYYY/MM/DD').locale('fa').format('YYYY/MM/DD')}</td>
                        <td>${moment(report.created_at, 'YYYY/MM/DD').locale('fa').format('YYYY/MM/DD')}</td>
                        <td><a class="btn btn-info" href="/reports/${report.id}">مشاهده</a></td>
                    </tr>
                `);
                        });

                        // مدیریت دکمه‌های صفحه‌بندی
                        if (response.prev_page_url) {
                            $('#prev-page').show();
                        } else {
                            $('#prev-page').hide();
                        }

                        if (response.next_page_url) {
                            $('#next-page').show();
                        } else {
                            $('#next-page').hide();
                        }

                        currentPage = page;
                    }
                });
            }

            // بارگذاری اولیه
            fetchReports(1);

            $('#prev-page').click(function () {
                if (currentPage > 1) {
                    fetchReports(currentPage - 1);
                }
            });

            $('#next-page').click(function () {
                fetchReports(currentPage + 1);
            });

            // تغییر آدرس API و به‌روزرسانی دکمه‌ها
            $('#perso-tejarat').click(function () {
                tbody.empty();
                apiUrl = 'http://127.0.0.1:7000/api/get-reports';
                $('.btn').removeClass('btn-primary').addClass('btn-light');
                $(this).removeClass('btn-light').addClass('btn-primary');
                fetchReports(1);
            });

            $('#adak-tejarat').click(function () {
                tbody.empty();
                apiUrl = 'http://127.0.0.1:7000/api/get-reports';
                $('.btn').removeClass('btn-primary').addClass('btn-light');
                $(this).removeClass('btn-light').addClass('btn-primary');
                fetchReports(1);
            });
        });


    </script>

@endsection

