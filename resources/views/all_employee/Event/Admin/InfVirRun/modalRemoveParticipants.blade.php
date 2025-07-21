<div class="modal-content modal-lg">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> {{ $data->getUser()->getFullName() }} Profile</h4>
    </div>
    <div class="modal-body">
        <table class="table table-borderless" style="border-color: white;">
            <tbody>
                <tr>
                    <th>Ebib</th>
                    <td>: {{ $data->ebib }}</td>
                </tr>
                <tr>
                    <th>Period</th>
                    <td>: {{ $data->periode }}</td>
                </tr>
                <tr>
                    <th>Strava Profile</th>
                    <td>
                        <form action="{{ route('admin/infinite-virtual-run/participant/edit/post', $data->id) }}"
                            method="post">
                            {{ csrf_field() }}
                            <input type="text" name="stravaProfile" value="{{ $data->profileUrl }}"
                                class="form-control">
                            <div style="margin-top: 5px;">
                                @if ($data->profileUrl)
                                    <a class="btn btn-xs btn-warning" target="_blank" href="{{ $data->profileUrl }}">Go
                                        to
                                        profile</a>
                                @endif
                                <button type="submit" class="btn btn-xs btn-primary"
                                    style="border-radius: 15px;">Update
                                    profile</button>
                            </div>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-danger" id="btnKickOut">Disqualified </button>
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<form action="{{ route('admin/infinite-virtual-run/disqualified', $data->id) }}" method="post" id="formKickOut">
    {{ csrf_field() }}
</form>
<script>
    $(document).ready(function() {
        $("button#btnKickOut").on('click', function(e) {
            $("form#formKickOut").submit();
        });
    });
</script>
