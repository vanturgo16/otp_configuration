@if($data->id && $data->tableJoin)
<a href="{{ route('historystock.fg.detail', [encrypt($data->id), $data->tableJoin]) }}" class="btn btn-sm btn-info waves-effect btn-label waves-light" title="Detail Stock">
    <i class="mdi mdi-information label-icon"></i> Detail
</a>
@else 
-
@endif