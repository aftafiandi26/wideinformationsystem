<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Register Event</h4>
    </div>
    <div class="modal-body">
        <form action="{{ route('infiniteVirRun/register/post') }}" method="post" id="formREG">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="nik">NIK:</label>
                <input type="text" class="form-control" value="{{ auth()->user()->nik }}" readonly>
            </div>
            <div class="form-group">
                <label for="emp">Employee:</label>
                <input type="text" class="form-control" value="{{ auth()->user()->getFullName() }}" readonly>
            </div>
            <div class="from-group">
                <label for="profile">Stava Profile:</label>
                <input type="text" class="form-control" placeholder="https://www.strava.com/athletes/176234509"
                    required name="profile">
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label><input type="checkbox" id="joinEvent"> Join event</label>
                </div>
            </div>

        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-success" id="buttonRegSubmit">Register</button>
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        // e.preventDefault();
        $("button#buttonRegSubmit").on('click', function(e) {
            e.preventDefault();
            let checkbox = document.getElementById('joinEvent');
            let checkValue = null;
            let form = document.getElementById('formREG');

            if (checkbox.checked) {
                checkValue = true;
                form.submit();
            } else {
                checkValue = false;
                alert("Please check the checkbox first");
            }
        });
    });
</script>
