@if($data->type_stock == 'IN')
    <a href="{{ route('historystock.fg.detailLot', encrypt($data->idGrnDetail)) }}" class="btn btn-sm btn-info shadow" class="btn">
        <span class="mdi mdi-view-list"></span> Detail Lot
    </a>
@else 
    -
@endif