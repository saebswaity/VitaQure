@can('edit_patient')
<a class="btn btn-primary btn-sm" href="{{route('admin.patients.edit',$patient['id'])}}">
    <i class="fa fa-edit" aria-hidden="true"></i>
</a>
@endcan

@can('create_group')
<a class="btn btn-info btn-sm" href="{{ route('admin.groups.create', ['patient_id' => $patient['id']]) }}" title="{{ __('Create Report') }}">
    <i class="fa fa-file-medical"></i>
  </a>
@endcan

@can('delete_patient')
<form method="POST" action="{{route('admin.patients.destroy',$patient['id'])}}" class="d-inline">
    <input type="hidden" name="_method" value="delete">
    <button type="submit" class="btn btn-danger btn-sm delete_patient">
        <i class="fa fa-trash"></i>
    </button>
</form>
@endcan