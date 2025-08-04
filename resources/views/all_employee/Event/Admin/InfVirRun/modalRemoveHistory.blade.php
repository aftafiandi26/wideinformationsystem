<div class="modal-content modal-lg">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> Delete Submission Activity</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-borderless" style="border-color: white;">
                    <tbody>
                        <tr>
                            <th>Participant</th>
                            <td>: <strong>{{ $data->EventRegister()->getUser()->getFullName() }}</strong></td>
                        </tr>
                        <tr>
                            <th>EBIB</th>
                            <td>: <strong>{{ $data->EventRegister()->ebib }}</strong></td>
                        </tr>
                        <tr>
                            <th>Strava Profile</th>
                            <td>
                                : <a href="{{ $data->EventRegister()->profileUrl }}" target="_blank"
                                    rel="noopener noreferrer" class="btn btn-xs btn-default">Strava Profile</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:
                                @if ($data->verify == 1)
                                    <span class="badge badge-success">Verified</span>
                                @endif
                                @if ($data->verify == 0)
                                    <span class="badge badge-info">Pending Verify</span>
                                @endif
                                @if ($data->verify == 2)
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Distance</th>
                            <td>: {{ $data->distance }}</td>
                        </tr>
                        <tr>
                            <th>Moving Time</th>
                            <td>: {{ $data->mvtime }}</td>
                        </tr>
                        <tr>
                            <th>Created at</th>
                            <td>: {{ date('M, d Y - h:m:s', strtotime($data->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <iframe src="{{ $data->url }}" frameborder="1" width="100%"
                                    height="400px;"></iframe>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-danger" id="btnDelete">Delete</button>
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<form action="{{ route('admin/infinite-virtual-run/history/delete/post', $data->id) }}" method="post" id="formDelete">
    {{ csrf_field() }}
</form>
<script>
    $(document).ready(function() {
        $("button#btnDelete").on('click', function(e) {
            $("form#formDelete").submit();
        });
    });
</script>
