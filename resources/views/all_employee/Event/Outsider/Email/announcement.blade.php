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
                    <form action="#" method="post">
                        <div class="form-group">
                            <label class="sr-only" for="email">From:</label>
                            <div class="input-group">
                                <div class="input-group-addon">From:</div>
                                <input type="email" class="form-control" id="email"
                                    value="infinitevirtualrun@infinitestudios.id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message :</label>
                            <textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
                        </div>
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
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/8/tinymce.min.js" referrerpolicy="origin"
        crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: '#message',
            menubar: false,
            plugins: 'lists link',
            toolbar: 'bold italic underline | bullist numlist | link'
        });
    </script>
@endpush
