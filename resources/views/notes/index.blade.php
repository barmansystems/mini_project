@extends('layouts.master')

@section('css-styles')
    <style>
        .card .title {
            background: transparent;
            border: none;
            width: 100%;
            color: #fff;
        }

        .card .title:focus-visible {
            outline: none !important;
        }

        .card .text {
            background: transparent;
            border: none;
            resize: none;
            width: 100%;
            height: 180px;
            text-align: justify;
        }

        .card .text:focus-visible {
            outline: none;
        }

        .loading {
            margin-right: 10px;
            margin-bottom: 8px;
        }

        .btn-remove {
            font-size: 1.5rem !important;
        }
    </style>
@endsection

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
                            <li class="breadcrumb-item"><a href="/notes">یادداشت ها</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">

                <div class="card-body">
                    <div class="card-title d-flex justify-content-end">

                        <button class="btn btn-primary mb-2" id="btn_add">
                            <i class="fa fa-plus mr-2"></i>
                            ایجاد یادداشت
                        </button>
                    </div>
                    <div class="row" id="list">
                        @foreach($notes as $note)
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-header bg-primary py-3 text-white">
                                        <div class="card-widgets text-left">
                                            <a href="javascript:void(0)" class="btn-remove">&times;</a>
                                        </div>
                                        <h5 class="card-title mb-0 text-white d-flex">
                                            <input type="text" name="note-title" class="title"
                                                   value="{{ $note->title }}" data-id="{{ $note->id }}" maxlength="30"
                                                   placeholder="عنوان یادداشت" autocomplete="off">
                                        </h5>
                                    </div>
                                    <div id="cardCollpase4" class="collapse show">
                                        <div class="card-body">
                                            <textarea class="text" name="note-text" spellcheck="false"
                                                      placeholder="متن یادداشت..."
                                                      autocomplete="off">{{ $note->text }}</textarea>
                                        </div>
                                        <div class="loading d-none">
                                            درحال ذخیره سازی ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center">{{ $notes->appends(request()->all())->links() }}</div>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('js-script')
    <script>

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRFToken': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // add note card
            $(document).on('click', '#btn_add', function () {
                $('#list').prepend(`<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <div class="card">
                                            <div class="card-header bg-primary py-3 text-white">
                                                <div class="card-widgets text-left">
                                                    <a href="javascript:void(0)" class="btn-remove">&times;</a>
                                                </div>
                                                <h5 class="card-title mb-0 text-white d-flex">
                                                    <input type="text" name="note-title" class="title" maxlength="30" placeholder="عنوان یادداشت" autocomplete="off">
                                                </h5>
                                            </div>
                                            <div id="cardCollpase4" class="collapse show">
                                                <div class="card-body">
                                                    <textarea class="text" name="note-text" spellcheck="false" placeholder="متن یادداشت..." autocomplete="off"></textarea>
                                                </div>
                                                <div class="loading d-none">
                                                    درحال ذخیره سازی ...
                                                </div>
                                            </div>
                                        </div>
                                    </div>`)

                $(this).attr('disabled', 'disabled')
            })
            // end add note card

            let timeout;
            // save title and text
            $(document).on('keyup', 'input[name="note-title"]', function () {
                let item = $(this);
                let loading = item.parent().parent().siblings().first().children('.loading');
                loading.removeClass('d-none')

                clearTimeout(timeout)

                timeout = setTimeout(function () {
                    let title = item.val()
                    let text = item.parent().parent().siblings().first().children('.card-body').children('.text').first().val()
                    let note_id = item.data('id')

                    $.ajax({
                        url: "{{ route('notes.store') }}",
                        type: 'post',
                        data: {
                            title,
                            text,
                            note_id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            item.data('id', res.id)
                            loading.addClass('d-none')
                            $('#btn_add').removeAttr('disabled')
                        }
                    })
                }, 1500)
            })

            $(document).on('keyup', 'textarea[name="note-text"]', function () {
                let item = $(this);
                let loading = item.parent().siblings().first();

                loading.removeClass('d-none')

                clearTimeout(timeout)

                timeout = setTimeout(function () {
                    let title = item.parent().parent().siblings('.card-header').find('.title').val()
                    let text = item.val()
                    let note_id = item.parent().parent().siblings('.card-header').find('.title').data('id')

                    $.ajax({
                        url: "{{ route('notes.store') }}",
                        type: 'post',
                        data: {
                            title,
                            text,
                            note_id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            item.parent().parent().siblings('.card-header').find('.title').data('id', res.id)
                            loading.addClass('d-none')
                            $('#btn_add').removeAttr('disabled')
                        }
                    })
                }, 1500)
            })
            // end title and text

            // btn remove
            $(document).on('click', '.btn-remove', function () {
                let self = $(this)
                self.addClass('confirm-delete')
                self.text('حذف')

                setTimeout(function () {
                    if (!self.hasClass('deleting')) {
                        self.removeClass('confirm-delete')
                        self.html('&times;')
                    }
                }, 3000)
            })

            $(document).on('click', '.confirm-delete', function () {
                let self = $(this)
                let note_id = self.parent().siblings().find('.title').data('id')

                self.addClass('deleting')
                self.text('درحال حذف...')

                if (note_id) {
                    $.ajax({
                        url: "{{ route('notes.destroy') }}",
                        type: 'post',
                        data: {
                            note_id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            self.parents('.card').first().parent().remove()
                        }
                    })
                } else {
                    self.parents('.card').first().parent().remove()
                    $('#btn_add').removeAttr('disabled')
                }
            })
            // end btn remove
        });


    </script>
@endsection



