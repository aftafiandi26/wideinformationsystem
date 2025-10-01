<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Event Account</h4>
    </div>
    <div class="modal-body">
        <form action="{{ route('outsider/infiniteVirRun/run/adminRegistration/post', $data->id) }}" method="post"
            id="formRegis">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="ebib">EBIB:</label>
                <input type="text" class="form-control" id="ebib" name="ebib" readonly
                    value="{{ $data->ebib }}">
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName"
                    value="{{ $data->user()->first_name }}">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName"
                    value="{{ $data->user()->last_name }}">
            </div>
            <div class="form-group">
                <label for="lastName">Gender:</label>
                <select name="gender" id="gender" class="form-control">
                    <option value=""></option>
                    <option value="Male" @if ($data->user()->gender == 'Male') selected @endif>Male</option>
                    <option value="Female" @if ($data->user()->gender == 'Female') selected @endif>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="{{ $data->user()->username }}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ $data->user()->email }}">
            </div>
            <div class="form-group">
                <label for="strava">Strava Profile:</label>
                <input type="url" class="form-control" id="strava" name="strava"
                    value="{{ $data->eventReg()->profileUrl }}">
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone"
                    value="{{ $data->user()->phone }}">
            </div>
            <div class="form-group">
                <label for="company">Company:</label>
                <input type="text" class="form-control" id="company" name="company" value="{{ $data->company }}">
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="regis">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("button#regis").on('click', function(e) {
            e.preventDefault();
            $("form#formRegis").submit();
        });
    });
</script>
