<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportCountBookRent implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{

    protected $data;

    protected $totalPrice;

    protected $minDate;

    protected $maxDate;

    protected $totalBookRent;

    /**
     * @param $data
     */
    public function __construct($data,$minDate,$maxDate)
    {
        $this->data = $data;
        $this->minDate = $minDate;
        $this->maxDate = $maxDate;
        foreach ($this->data as $item) {
            $this->totalBookRent += $item->countBookRentInMonth;
            $this->totalPrice +=  $item->totalPriceRentInMonth;
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }


    public function headings(): array
    {
        return ['ID','Tên sách','Thể loại','Tác giả','Số lượng sách mượn trong tháng','Tiền mượn'];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('F')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A2:F2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => '20',
            ]
        ]);

        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => '20',
            ]
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow() + 1;

                $event->sheet->setCellValue('A' . $lastRow, 'Tổng');
                $event->sheet->setCellValue('E' . $lastRow, $this->totalBookRent);
                $event->sheet->setCellValue('F' . $lastRow, $this->totalPrice);
                $event->sheet->getStyle('E'.$lastRow.':F'.$lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
                $event->sheet->getStyle('A3:F'.$lastRow)->applyFromArray([
                    'font' => [
                        'size' => '13'
                    ]
                ]);
            },
            BeforeSheet::class=>function(BeforeSheet $event) {
                $event->sheet->mergeCells('A1:F1');
                $event->sheet->setCellValue('A1','THỐNG KÊ SÁCH');
                $event->getDelegate()->getRowDimension('1')->setRowHeight(50);

                $event->sheet->mergeCells('A2:F2');
                $event->sheet->setCellValue('A2','Từ ngày '.$this->minDate.' đến ngày '.$this->maxDate);
            }
        ];
    }
}
