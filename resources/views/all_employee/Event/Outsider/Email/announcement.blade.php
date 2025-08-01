@extends('layout')

@section('title')
    Registration - Infinite Virtual Run
@stop

@section('top')
    @include('assets_css_1')
    @include('assets_css_2')
    @include('assets_css_3')
    @include('assets_css_4')
@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left', [
        'c2' => 'active',
    ])

@stop

@push('style')
    @include('asset_message_editor')
    <style></style>
@endpush
@section('body')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Announcement for External Participant</h1>
        </div>
    </div>

    @include('asset_feedbackErrors')

    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('outsider/infiniteVirRun/run/adminRegistration') }}"
                class="btn btn-sm btn-default pull-right"><i class="fa fa-arrow-alt-circle-right"></i> back</a>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Announcement mail</div>
                <div class="panel-body">
                    <form action="{{ route('admin/infinite-virtual-run/announcement/post') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="sr-only" for="email">From:</label>
                            <div class="input-group">
                                <div class="input-group-addon">From:</div>
                                <input type="email" class="form-control" id="email"
                                    value="infinitevirtualrun@infinitestudios.id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="to">Bcc:</label>
                            <div class="input-group">
                                <div class="input-group-addon">bcc:</div>
                                <select name="email" id="email" class="form-control">
                                    <option value=""></option>
                                    <optgroup label="Administrator">
                                        @foreach ($admin as $a)
                                            <option value="{{ $a->id }}">{{ $a->getFullName() }}</option>
                                        @endforeach
                                        <option value="all">All Participant</option>
                                    </optgroup>
                                    <optgroup label="Participant">
                                        @foreach ($external as $e)
                                            <option value="{{ $e->id }}" disabled>{{ $e->getFullName() }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message :</label>
                            <input type="hidden" name="inputEditor" id="inputEditor">
                            <div id="toolbar-container">
                                <span class="ql-formats">
                                    <select class="ql-font"></select>
                                    <select class="ql-size"></select>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                    <button class="ql-strike"></button>
                                </span>
                                <span class="ql-formats">
                                    <select class="ql-color"></select>
                                    <select class="ql-background"></select>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-script" value="sub"></button>
                                    <button class="ql-script" value="super"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-header" value="1"></button>
                                    <button class="ql-header" value="2"></button>
                                    <button class="ql-blockquote"></button>
                                    <button class="ql-code-block"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                    <button class="ql-indent" value="-1"></button>
                                    <button class="ql-indent" value="+1"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-direction" value="rtl"></button>
                                    <select class="ql-align"></select>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-link"></button>
                                    <button class="ql-image"></button>
                                    <button class="ql-video"></button>
                                    <button class="ql-formula"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-clean"></button>
                                </span>
                            </div>
                            <div id="editor">
                                <p>Hey [participant],</p>
                                <p><br></p>
                                <p>Apakah ini bisa dijadikan announcement untuk semua anggota partisipan, </p>
                                <p>karena hal ini berhubungan dengan client kita.. </p>
                                <p><br></p>
                                <p>ini hanya testing untuk pengiriman pesan email, <strong>dihubungkan</strong> antara
                                    konduktor dengan <s>isioloator</s> maksudah isolator, </p>
                                <p>beginilah hasil dari email nya,</p>
                                <p><br></p>
                                <p><br></p>
                                <p><br></p>
                                <p><img src="{{ asset('assets/signature-infinitevirtualrun.png') }}"
                                        alt="signature-infinitestudiosvirtualrun" srcset=""></p>

                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-default">Kirim</button>
                    </form>
                </div>
                <div class="panel-footer">footer</div>
            </div>
        </div>
    </div>

    <div id="modalCreate" class="modal fade" role="dialog">
        <div class="modal-dialog" id="modalRegister">

        </div>
    </div>


@stop
@section('bottom')
    @include('assets_script_1')
    @include('assets_script_2')
    @include('assets_script_3')
    @include('assets_script_7')

@stop
@push('js')
    <script>
        const quill = new Quill('#editor', {
            modules: {
                syntax: true,
                toolbar: '#toolbar-container',
            },
            placeholder: '******.',
            theme: 'snow',
        });
        var form = document.querySelector('form');
        form.onsubmit = function() {
            // simpan isi editor ke input tersembunyi dalam format HTML
            var contentInput = document.querySelector('input[name=inputEditor]');
            contentInput.value = quill.root.innerHTML;
        };
    </script>
@endpush
