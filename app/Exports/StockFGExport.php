<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockFGExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $datas;

    public function __construct($datas, $request, $group_subs, $allTotal)
    {
        $this->datas = $datas;
        $this->keyword = $request->keyword ?? '-';
        $this->type = $request->type ?? '-';
        $this->thickness = $request->thickness ?? '-';
        $this->group_subs = $group_subs ?? '-';
        $this->allTotal = $allTotal;
        $this->dateFrom = $request->dateFrom ?? '-';
        $this->dateTo = $request->dateTo ?? '-';
        $this->exportedBy = auth()->user()->email;
        $this->exportedAt = now()->format('d-m-Y H:i:s');
    }

    public function view(): View
    {
        return view('exports.stock_fg', [
            'datas' => $this->datas,
            'keyword' => $this->keyword,
            'type' => $this->type,
            'thickness' => $this->thickness,
            'group_subs' => $this->group_subs,
            'allTotal' => $this->allTotal,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'exportedBy' => $this->exportedBy,
            'exportedAt' => $this->exportedAt,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $totalRows = $sheet->getHighestRow();

        // 1️⃣ Header Style (Bold, Centered, Gray Background)
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle("A10:{$lastColumn}10")->applyFromArray($headerStyle);

        // 2️⃣ Apply Borders, Align Top Left to All Data
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle("A10:{$lastColumn}{$totalRows}")->applyFromArray($borderStyle);
        
        $sheet->getStyle("A10:{$lastColumn}{$totalRows}")->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    }
}
