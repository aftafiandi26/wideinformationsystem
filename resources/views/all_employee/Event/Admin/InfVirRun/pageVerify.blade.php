<div class="modal-content modal-lg">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Verify Submission</h4>
    </div>
    <div class="modal-body">
        <form action="#" method="post" id="formREG">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="nik">EBIB:</label>
                    <input type="text" class="form-control" value="{{ $event->ebib }}" readonly>
                </div>
                <div class="form-group col-lg-6">
                    <label for="nik">Participant:</label>
                    <input type="text" class="form-control"
                        value="{{ $event->EventRegister()->getUser()->getFullName() }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="nik">Distance:</label>
                    <input type="text" class="form-control" value="{{ $event->distance }}" readonly>
                </div>
                <div class="form-group col-lg-6">
                    <label for="nik">Moving Time:</label>
                    <input type="text" class="form-control" value="{{ $event->mvtime }}" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-12">
                    <iframe src="{{ $event->url }}" frameborder="1" width="100%" height="400px;"></iframe>
                </div>
            </div>

        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" id="approve">Approve</button>
        <button type="button" class="btn btn-sm btn-warning" id="disapprove">Disapprove</button>
        <button type="button" class="btn btn-sm btn-danger" id="delete">Delete</button>
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
    </div>

    <div class="row" hidden>
        <form action="{{ route('admin/infinite-virtual-run/approve', $event->id) }}" method="post" id="formApprove">
            {{ csrf_field() }}
        </form>
        <form action="{{ route('admin/infinite-virtual-run/disapprove', $event->id) }}" method="post"
            id="formDisapprove">
            {{ csrf_field() }}
        </form>
        <form action="{{ route('admin/infinite-virtual-run/delete', $event->id) }}" method="post" id="formDelete">
            {{ csrf_field() }}
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("button#approve").on('click', function(e) {
            $("form#formApprove").submit();
        });
        $("button#disapprove").on('click', function(e) {
            $("form#formDisapprove").submit();
        });
        $("button#delete").on('click', function(e) {
            $("form#formDelete").submit();
        });
    });
</script>
