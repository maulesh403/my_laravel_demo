<form action="{{ $action }}" onsubmit="return confirm('Your data will be removed permanently, Are you sure?')" method="POST" style="display: inline;">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-sm btn-circle btn-outline-danger"
    title="Delete Permanently"><i
        class="fa fa-times"></i></button>
</form>