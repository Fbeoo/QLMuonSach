<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportCountBookMissing implements FromCollection, WithHeadings, WithStyles,WithEvents,ShouldAutoSize
{
    protected $data;
    protected $totalBookMissing;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        foreach ($data as $item) {
            $this->totalBookMissing += $item->countBookMissing;
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
               $lastRow = $event->sheet->getHighestRow() + 1;

               $event->sheet->setCellValue('E'.$lastRow,$this->totalBookMissing);
               $event->sheet->setCellValue('A'.$lastRow,'Tổng');
               $event->sheet->getStyle('E'.$lastRow)->applyFromArray([
                   'font' => [
                       'bold' => true
                   ]
               ]);
               $event->sheet->getStyle('A2:E'.$lastRow)->applyFromArray([
                   'font' => [
                       'size' => '13'
                   ]
               ]);
            },
            BeforeSheet::class => function(BeforeSheet $event) {
                $event->sheet->setCellValue('A1','Thống kê sách đang thất lạc');
                $event->getDelegate()->getRowDimension('1')->setRowHeight('50');
            }
        ];
    }

    public function headings(): array
    {
        return [
            ['ID','Tên sách','Thể loại','Tác giả','Số lượng sách đang thất lạc']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 20
            ]
        ]);
    }
}
