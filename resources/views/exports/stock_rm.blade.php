@php use Carbon\Carbon; Carbon::setLocale('id'); @endphp
<table>
    <thead>
        <!-- Export Details -->
        <tr>
            <th colspan="10"><strong>Laporan Bahan Baku (Raw Material)</strong></th>
        </tr>
        <tr>
            <th colspan="10"><strong>PT Olefina Tifaplas Polikemindo</strong></th>
        </tr>
        <tr>
            <td colspan="2">Kata Kunci</td>
            <td colspan="8">: {{ $keyword }}</td>
        </tr>
        <tr>
            <td colspan="2">Periode </td>
            <td colspan="8">
                : {{ Carbon::parse($dateFrom)->translatedFormat('d F Y') }} - {{ Carbon::parse($dateTo)->translatedFormat('d F Y') }}
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
            <th>Code</th>
            <th>Description</th>
            <th>Stock Awal</th>
            <th>IN</th>
            <th>OUT</th>
            <th>Tanggal</th>
            <th>Stock Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->code ?? '-' }}</td>
                <td>{{ $data->description ?? '-' }}</td>
                <td style="text-align: right;">??</td>
                <td style="text-align: right;">
                    @if($data->type_stock == 'IN')
                        {{ $data->qty ??  '0' }}
                    @else 
                    -
                    @endif
                </td>
                <td style="text-align: right;">
                    @if($data->type_stock == 'OUT')
                        {{ $data->qty ??  '0' }}
                    @else 
                    -
                    @endif
                </td>
                <td style="text-align: right;">{{ $data->date ?? '-' }}</td>
                <td style="text-align: right;">??</td>
            </tr>
        @endforeach
        <tr>
            <td style="text-align: right; background-color: #D3D3D3;"></td>
            <td colspan="2" style="font-weight: bold; background-color: #D3D3D3;">Jumlah</td>
            <td style="text-align: right; background-color: #D3D3D3;">??</td>
            <td style="text-align: right; background-color: #D3D3D3;"><strong>{{ $allTotal['totalIn'] }}</strong></td>
            <td style="text-align: right; background-color: #D3D3D3;"><strong>{{ $allTotal['totalOut'] }}</strong></td>
            <td style="text-align: right; background-color: #D3D3D3;"></td>
            <td style="text-align: right; background-color: #D3D3D3;"></td>
        </tr>
    </tbody>
</table>
