@php use Carbon\Carbon; Carbon::setLocale('id'); @endphp
<table>
    <thead>
        <!-- Export Details -->
        <tr>
            @if($type == 'RM')
                <th colspan="10"><strong>Laporan Bahan Baku (Raw Material)</strong></th>
            @elseif($type == 'WIP')
                <th colspan="10"><strong>Laporan Work In Progress (WIP)</strong></th>
            @elseif($type == 'FG')
                <th colspan="10"><strong>Laporan Finish Good (FG)</strong></th>
            @elseif($type == 'TA')
                <th colspan="10"><strong>Laporan Tool/Aux/Other</strong></th>
            @endif
        </tr>
        <tr>
            <th colspan="10"><strong>PT Olefina Tifaplas Polikemindo</strong></th>
        </tr>
        <tr>
            @if($type == 'RM')
                <td colspan="2">Nama Raw Material</td>
                <td colspan="8">: {{ $detail->rm_code }} - {{ $detail->description }}</td>
            @elseif($type == 'WIP')
                <td colspan="2">Nama WIP</td>
                <td colspan="8">: {{ $detail->wip_code }} - {{ $detail->description }}</td>
            @elseif($type == 'FG')
                <td colspan="2">Nama Finish Good</td>
                <td colspan="8">: {{ $detail->product_code }} - {{ $detail->description }}</td>
            @elseif($type == 'TA')
                <td colspan="2">Nama Barang</td>
                <td colspan="8">: {{ $detail->code }} - {{ $detail->description }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Periode </td>
            <td colspan="8">
                : {{ Carbon::parse($month)->translatedFormat('F Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="2">Stok Awal </td>
            <td colspan="8">
                : {{ $allTotal['InitialStock'] ??  '0' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">Stok Akhir </td>
            <td colspan="8">
                : {{ $allTotal['EndingStock'] ??  '0' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">Di Export Oleh</td>
            <td colspan="8">: {{ $exportedBy }} at {{ $exportedAt }}</td>
        </tr>
        <tr><td colspan="10"></td></tr>

        <!-- Column Headers -->
        <tr>
            <th>No</th>
            <th>(Lot/Report/Packing) Number</th>
            <th>Tanggal</th>
            <th>IN</th>
            <th>OUT</th>
        </tr>
    </thead>
    <tbody>
        @if($datas->isEmpty())
            <tr>
                <td colspan="5" style="text-align: center;">- Tidak ada data -</td>
            </tr>
        @else
            @foreach($datas as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->number ?? '-' }}</td>
                    <td style="text-align: right;">{{ $data->date ?? '-' }}</td>
                    <td style="text-align: right;">
                        @if($data->type_stock == 'IN')
                            {{ $data->qty ??  '0' }}
                        @else 
                        0
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($data->type_stock == 'OUT')
                            {{ $data->qty ??  '0' }}
                        @else 
                        0
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td style="text-align: right; background-color: #D3D3D3;"></td>
            <td colspan="2" style="font-weight: bold; background-color: #D3D3D3;">Jumlah</td>
            <td style="text-align: right; background-color: #D3D3D3;"><strong>{{ $allTotal['totalIn'] ??  '0' }}</strong></td>
            <td style="text-align: right; background-color: #D3D3D3;"><strong>{{ $allTotal['totalOut'] ??  '0' }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: right; background-color: #D3D3D3;"></td>
            <td colspan="2" style="font-weight: bold; background-color: #D3D3D3;">Total</td>
            <td colspan="2" style="text-align: right; background-color: #D3D3D3;"><strong>{{ $allTotal['sumTotal'] ??  '0' }}</strong></td>
        </tr>
    </tbody>
</table>
