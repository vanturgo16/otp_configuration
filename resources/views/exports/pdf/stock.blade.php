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
                <td colspan="2">Kata Kunci</td>
                <td colspan="6">: {{ $request->keyword ?? '-' }}</td>
            </tr>

            @if(in_array($typeProd, ['WIP', 'FG', 'TA']))
                <tr>
                    <td colspan="2">Type</td>
                    <td colspan="8">: {{ $request->type ?? 'Semua' }}</td>
                </tr>
            @endif
            @if(in_array($typeProd, ['WIP', 'FG']))
                <tr>
                    <td colspan="2">Thickness</td>
                    <td colspan="8">: {{ $request->thickness ?? 'Semua' }}</td>
                </tr>
                <tr>
                    <td colspan="2">Sub Group</td>
                    <td colspan="8">: {{ $group_subs ?? 'Semua' }}</td>
                </tr>
            @endif

            <tr>
                <td colspan="2">Periode</td>
                <td colspan="6">
                    : {{ Carbon::parse($month)->translatedFormat('F Y') }}
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
                    <th>Code</th>
                    <th>Description</th>
                    <th>IN</th>
                    <th>OUT</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @if($datas->isEmpty())
                    <tr>
                        <td colspan="6" style="text-align: center;">- Tidak ada data -</td>
                    </tr>
                @else
                    @foreach($datas as $data)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-left">{{ $data->code ?? '-' }}</td>
                            <td class="text-left wrap">{{ $data->description ?? '-' }}</td>
                            <td class="text-right">
                                @if($data->type_stock == 'IN')
                                    {{ $data->qty 
                                        ? (strpos(strval($data->qty), '.') !== false 
                                            ? rtrim(rtrim(number_format($data->qty, 3, ',', '.'), '0'), ',') 
                                            : number_format($data->qty, 0, ',', '.')) 
                                        : '0' }}
                                    {{-- {{ $data->qty ??  '0' }} --}}
                                @else 
                                    0
                                @endif
                            </td>
                            <td class="text-right">
                                @if($data->type_stock == 'OUT')
                                    {{ $data->qty 
                                        ? (strpos(strval($data->qty), '.') !== false 
                                            ? rtrim(rtrim(number_format($data->qty, 3, ',', '.'), '0'), ',') 
                                            : number_format($data->qty, 0, ',', '.')) 
                                        : '0' }}
                                    {{-- {{ $data->qty ??  '0' }} --}}
                                @else 
                                    0
                                @endif
                            </td>
                            <td class="text-right">{{ $data->date ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td class="text-right" style="background-color: #D3D3D3;"></td>
                    <td colspan="2" style="font-weight: bold; background-color: #D3D3D3;">Jumlah</td>
                    <td style="text-align: right; background-color: #D3D3D3;">
                        <strong>
                            {{ $allTotal['totalIn']
                                ? (strpos(strval($allTotal['totalIn']), '.') !== false 
                                    ? rtrim(rtrim(number_format($allTotal['totalIn'], 3, ',', '.'), '0'), ',') 
                                    : number_format($allTotal['totalIn'], 0, ',', '.')) 
                                : '0' }}
                            {{-- {{ $allTotal['totalIn'] ??  '0' }} --}}
                        </strong>
                    </td>
                    <td style="text-align: right; background-color: #D3D3D3;">
                        <strong>
                            {{ $allTotal['totalOut']
                                ? (strpos(strval($allTotal['totalOut']), '.') !== false 
                                    ? rtrim(rtrim(number_format($allTotal['totalOut'], 3, ',', '.'), '0'), ',') 
                                    : number_format($allTotal['totalOut'], 0, ',', '.')) 
                                : '0' }}
                            {{-- {{ $allTotal['totalOut'] ??  '0' }} --}}
                        </strong>
                    </td>
                    <td style="text-align: right; background-color: #D3D3D3;"></td>
                </tr>
            </tbody>
        </table>
    </main>
    
</body>
</html>
