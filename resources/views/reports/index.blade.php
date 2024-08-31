@extends('layouts.master')
@section('css-styles')
    <style>
        .allLoading{
            background-color: rgba(208, 208, 208, 0.53);
            display: grid;
            grid-template-columns: 1fr;
            height: 100%;
            justify-content: center;
            position: fixed;
            right: 0;
            top: 0;
            width: 100%;
            z-index: 40000;

        }
        .allLoading .allLoadings {
            align-items: center;
            background: rgb(255, 255, 255);
            border-radius: .4rem;
            display: grid;
            height: 7rem;
            margin: auto;
            overflow: hidden;
            padding: 2rem;
            width: 7rem;
        }

        .loader {
            width: 48px;
            height: 48px;
            border: 3px solid #605f5f;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;

        }

        .loader::after {
            content: '';
            box-sizing: border-box;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 3px solid;
            border-color: #175ddc transparent;

        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
@section('content')
    <div class="allLoading" style="display: none">
        <div class="allLoadings">
            <span class="loader text-center"></span>
        </div>
    </div>

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
                                <button id="prev-page" class="btn btn-info" style="display: none;">قبلی</button>
                                <button id="next-page" class="btn btn-info" style="display: none;">بعدی</button>
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


    <div class="modal fade" id="reportDescModal" tabindex="-1" role="dialog"
         aria-labelledby="reportDescModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-2 text-center">توضیحات</h4>
                </div>
                <div class="modal-body text-right" id="descriptionModal">
                    <ol id="reportDesc">

                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary m-1" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js-script')
    <script src="{{asset('plugins/jdate/jdate.js')}}"></script>
    <script>


        $(document).ready(function () {

            var currentPage = 1;
            var apiUrl = 'https://parso.moshrefiholding.com/api/get-reports';
            var apiUrlDesc = 'https://parso.moshrefiholding.com/api/get-report-desc/';
            var tbody = $('#reports');

            $('.allLoading').show();
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


                            const createdAt = convertToPersianNumbers(moment(report.created_at).locale('fa').format('HH:mm YYYY/MM/DD'));

                            tbody.append(`
                    <tr>
                        <td>${convertToPersianNumbers((index + 1) + (page - 1) * 10)}</td>
                        <td>${report.user.name} ${report.user.family}</td>
                        <td>${report.user.role.label}</td>
                        <td>${convertToPersianNumbers(moment(report.date).locale('fa').format('YYYY/MM/DD'))}</td>
                        <td>${createdAt}</td>
                        <td><button class="btn btn-info report-info" data-toggle="modal" data-target="#reportDescModal" data-id="${report.id}">مشاهده</button></td>
                    </tr>
                `);
                        });

                        // مدیریت دکمه‌های صفحه‌بندی
                        $('#prev-page').toggle(!!response.prev_page_url);
                        $('#next-page').toggle(!!response.next_page_url);

                        currentPage = page;
                        $('.allLoading').hide();
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
                $('.allLoading').show();
                tbody.empty();
                apiUrl = 'https://parso.moshrefiholding.com/api/get-reports';
                apiUrlDesc = 'https://parso.moshrefiholding.com/api/get-report-desc/';
                $('.btn').removeClass('btn-primary').addClass('btn-light');
                $(this).removeClass('btn-light').addClass('btn-primary');
                fetchReports(1);
            });

            $('#adak-tejarat').click(function () {
                $('.allLoading').show();
                tbody.empty();
                apiUrl = 'https://adaktejarat.moshrefiholding.com/api/get-reports';
                apiUrlDesc = 'https://adaktejarat.moshrefiholding.com/api/get-report-desc/';
                console.log(apiUrlDesc)
                $('.btn').removeClass('btn-primary').addClass('btn-light');
                $(this).removeClass('btn-light').addClass('btn-primary');
                fetchReports(1);
            });

            $(document).on('click', '.report-info', function () {
                var reportDesc = $('#reportDesc');
                var reportId = $(this).data('id');
                reportDesc.empty();
                $.ajax({
                    url: apiUrlDesc + reportId,
                    type: 'POST',
                    data: {id: reportId},
                    success: function (response) {
                        $.each(response.data, function (i, item) {
                            $('#reportDesc').append(`<li>${item}</li>`);
                        });
                    }
                });
            });

            function convertToPersianNumbers(text) {
                text = String(text);
                const englishToPersianMap = {
                    '0': '۰',
                    '1': '۱',
                    '2': '۲',
                    '3': '۳',
                    '4': '۴',
                    '5': '۵',
                    '6': '۶',
                    '7': '۷',
                    '8': '۸',
                    '9': '۹'
                };

                return text.replace(/[0-9]/g, (match) => englishToPersianMap[match]);
            }
        });


    </script>

@endsection

