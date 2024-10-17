@if($data->type_product == 'RM')
<a href="{{ route('historystock.historyRM', encrypt($data->id_master_products)) }}" class="btn btn-sm btn-info shadow" class="btn">
    <span class="mdi mdi-view-list"></span> History Stock
</a>
@elseif($data->type_product == 'WIP')
<a href="{{ route('historystock.historyWIP', encrypt($data->id_master_products)) }}" class="btn btn-sm btn-info shadow" class="btn">
    <span class="mdi mdi-view-list"></span> History Stock
</a>
@elseif($data->type_product == 'FG')
<a href="{{ route('historystock.historyFG', encrypt($data->id_master_products)) }}" class="btn btn-sm btn-info shadow" class="btn">
    <span class="mdi mdi-view-list"></span> History Stock
</a>
@elseif($data->type_product == 'TA')
<a href="{{ route('historystock.historyTA', encrypt($data->id_master_products)) }}" class="btn btn-sm btn-info shadow" class="btn">
    <span class="mdi mdi-view-list"></span> History Stock
</a>
@endif