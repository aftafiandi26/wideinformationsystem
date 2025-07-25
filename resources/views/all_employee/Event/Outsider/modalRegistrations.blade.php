<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Participant</h4>
    </div>
    <div class="modal-body">
        <form action="{{ route('outsider/infiniteVirRun/run/adminRegistration/store') }}" method="post" id="formRegis">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="Dede">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="Aftafiandi">
            </div>
            <div class="form-group">
                <label for="lastName">Gender:</label>
                <select name="gender" id="gender" class="form-control">
                    <option value=""></option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password"
                    value="Event{{ date('Y') }}">
                <input type="checkbox" id="showPass"> Show Password
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="dede.aftafiandi@infinitestudios.id">
            </div>
            <div class="form-group">
                <label for="strava">Strava Profile:</label>
                <input type="url" class="form-control" id="strava" name="strava"
                    value="https://www.strava.com/athletes/176234509">
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="085272559098">
            </div>
            <div class="form-group">
                <label for="company">Company:</label>
                <input type="text" class="form-control" id="company" name="company" value="PT Kinema">
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="regis">Register</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        document.getElementById('showPass').addEventListener('change', function() {
            var passInput = document.getElementById('password');
            if (this.checked) {
                passInput.type = 'text'; // tampilkan password
            } else {
                passInput.type = 'password'; // sembunyikan password
            }
        });

        $("button#regis").on('click', function(e) {
            e.preventDefault();
            $("form#formRegis").submit();
        });
    });
</script>
