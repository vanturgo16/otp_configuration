@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>
        Cetak Stok @if($typeProd == 'RM') Raw Material @elseif($typeProd == 'WIP') Work In Progress @elseif($typeProd == 'FG') Finish Good @elseif($typeProd == 'TA') Tool/Aux/Other @endif PDF
    </title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        th { background-color: #d3d3d3; text-align: center; }
        .no-border td { border: none; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .wrap { white-space: pre-wrap; }

        main {
            margin-top: 10px;
            margin-bottom: 60px;
        }
        footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
        }
        .page-number:after {
            content: counter(page);
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <footer>
        <hr>
        PT Olefina Tifaplas Polikemindo - Confidential Document<br>
        Halaman <span class="page-number"></span>
    </footer>

    <main>
        <h3 style="text-align: center; margin-bottom: 5px;">
            Laporan 
            @if($typeProd == 'RM')
                Bahan Baku (Raw Material)
            @elseif($typeProd == 'WIP')
                Work In Progress (WIP)
            @elseif($typeProd == 'FG')
                Finish Good (FG)
            @elseif($typeProd == 'TA')
                Tool/Aux/Other
            @endif
        </h3>
        <h4 style="text-align: center; margin-top: 0; margin-bottom: 5px;">PT Olefina Tifaplas Polikemindo</h4>

        <table class="no-border">
            <tr>
                @if($typeProd == 'RM')
                    <td colspan="2">Nama Raw Material</td>
                    <td colspan="8">: {{ $detail->rm_code }} - {{ $detail->description }}</td>
                @elseif($typeProd == 'WIP')
                    <td colspan="2">Nama WIP</td>
                    <td colspan="8">: {{ $detail->wip_code }} - {{ $detail->description }}</td>
                @elseif($typeProd == 'FG')
                    <td colspan="2">Nama Finish Good</td>
                    <td colspan="8">: {{ $detail->product_code }} - {{ $detail->description }}</td>
                @elseif($typeProd == 'TA')
                    <td colspan="2">Nama Barang</td>
                    <td colspan="8">: {{ $detail->code }} - {{ $detail->description }}</td>
                @endif
            </tr>

            <tr>
                <td colspan="2">Periode</td>
                <td colspan="6">
                    : {{ Carbon::parse($month)->translatedFormat('F Y') }}
                </td>
            </tr>
            <tr>
                <td colspan="2">Stok Awal </td>
                <td colspan="6">
                    : {{ $allTotal['InitialStock'] ??  '0' }}
                </td>
            </tr>
            <tr>
                <td colspan="2">Stok Akhir </td>
                <td colspan="6">
                    : {{ $allTotal['EndingStock'] ??  '0' }}
                </td>
            </tr>
            <tr>
                <td colspan="2">Di Cetak Oleh</td>
                <td colspan="6">: {{ $exportedBy }} at {{ $exportedAt }}</td>
            </tr>
        </table>

        <br>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>(Lot/Report/Packing/LMTS) Number</th>
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
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $data->number ?? '-' }}</td>
                            <td class="text-right">{{ $data->date ?? '-' }}</td>
                            <td class="text-right">
                                @if($data->type_stock == 'IN')
                                    {{ $data->qty ??  '0' }}
                                @else 
                                    0
                                @endif
                            </td>
                            <td class="text-right">
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
                    <td class="text-right" style="background-color: #D3D3D3;"></td>
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
    </main>
    
</body>
</html>
