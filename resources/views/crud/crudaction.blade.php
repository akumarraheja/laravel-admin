
<a class="btn btn-sm btn-primary mr-1" href="crud/{{$slug}}/edit"><i class="mdi mdi-pencil"></i></a>
<form action='{{admin_url("crud/$slug")}}' method="POST" style="display: inline">
    @method('delete')
    @csrf
    <button type="submit" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
</form>