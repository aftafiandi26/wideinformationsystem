<a class="btn btn-sm btn-info" id="verify" data-role="{{ route('admin/infinite-virtual-run/verify/verified', $id) }}"
    data-toggle="modal" data-target="#modalVerify">Verify</a>
<a href="#" class="btn btn-sm btn-danger"
    onclick="event.preventDefault(); document.getElementById('formDelete-{{ $id }}').submit();">
    Delete
</a>
<form id="formDelete-{{ $id }}" action="{{ route('admin/infinite-virtual-run/delete', $id) }}" method="POST"
    style="display:none;">
    {{ csrf_field() }}
</form>
