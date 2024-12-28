@if($data->barcode != null)
    <u>
        <a href="{{ route('historystock.barcode', $data->barcode) }}" title="Lihat Detail Barcode">
            {{ $data->barcode }}
        </a>
    </u>
@else
    -
@endif