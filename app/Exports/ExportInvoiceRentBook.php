<?php

namespace App\Exports;

use App\Models\Book;
use App\Models\DetailHistoryRentBook;
use App\Models\HistoryRentBook;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Shared\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportInvoiceRentBook implements FromCollection,WithDrawings,ShouldAutoSize,WithEvents,WithStyles,WithHeadings,WithMapping,WithStartRow,WithColumnWidths
{
    protected $data;
    protected $numDateRent;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $dateRent = Carbon::createFromFormat('Y-m-d', $this->data[0]->rent_date);;
        $dateExpire = Carbon::createFromFormat('Y-m-d', $this->data[0]->expiration_date);
        $this->numDateRent = $dateExpire->diffInDays($dateRent);
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }
    public function startRow(): int
    {
        return 10;
    }
    public function drawings()
    {
        $draw = [];
        $i = 11;
        foreach ($this->data[0]->detailHistoryRentBook as $item) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('image');
            $drawing->setDescription('This is my image');
            $drawing->setPath(public_path('/storage/'.$item->book->thumbnail));

            $drawing->setHeight(90);
            $drawing->setCoordinates('A'.$i++);

            $drawing->setOffsetX(10);
            $drawing->setOffsetY(10);

            $draw[] = $drawing;
        }

        return $draw;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                for ($i = 11;$i<=$lastRow;$i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(90);
                }

                $event->sheet->getStyle('A6:E'.$lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $event->sheet->setCellValue('D'.$lastRow + 2 ,'Tổng tiền');
                $event->sheet->getStyle('D'.$lastRow + 2)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => [
                            'rgb' => '0099ff'
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $event->sheet->setCellValue('E'.$lastRow + 2,$this->data[0]->total_price);
                $event->sheet->getStyle('E'.$lastRow + 2)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $event->sheet->mergeCells('A'.($lastRow+4).':E'.($lastRow+4));
                $event->sheet->setCellValue('A'.($lastRow+4),'Cảm ơn vì đã mượn sách');
                $event->sheet->getStyle('A'.$lastRow+4)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $event->sheet->mergeCells('A'.($lastRow+5).':E'.($lastRow+5));
                $event->sheet->setCellValue('A'.($lastRow+5),'Chúc bạn có những khoảng thời gian đọc sách vui vẻ');
                $event->sheet->getStyle('A'.$lastRow+5)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $event->sheet->mergeCells('A'.($lastRow+6).':E'.($lastRow+6));
                $event->sheet->setCellValue('A'.($lastRow+6),'Mọi thắc mắc xin hãy liên hệ tới số 039-417-8940');
                $event->sheet->getStyle('A'.$lastRow+6)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $event->sheet->getStyle('A1:E'.($lastRow + 6))->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ]
                    ]
                ]);

                $event->sheet->getStyle('A10:E'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ]
                    ]
                ]);
            },
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->setCellValue('A1','YÊU CẦU MƯỢN SÁCH');
                $event->sheet->setCellValue('A3','Người mượn : '.$this->data[0]->user->name);
                $event->sheet->setCellValue('A4','Địa chỉ : '.$this->data[0]->user->address);
                $event->sheet->setCellValue('A5','Email : '.$this->data[0]->user->mail);
                $event->sheet->setCellValue('D3','Ngày mượn : '.$this->data[0]->rent_date);
                $event->sheet->setCellValue('D4','Ngày trả : '.$this->data[0]->expiration_date);
                $event->sheet->insertNewRowBefore(6,4);
            }
        ];
    }

    public function headings(): array
    {
        return [
            ['Ảnh sách','Tên sách','Đơn giá thuê','Số lượng thuê','Thành tiền']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getStyle('A10:E10')->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => '0099ff'
                ]
            ]
        ]);
        $sheet->getStyle('B')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode('#,##0');
    }

    public function map($row): array
    {
        $data = [];
        foreach ($row->detailHistoryRentBook as $item) {
            $data[] = [
                '',
                $item->book->name,
                number_format($item->book->price_rent, 0, ',', ',').'/ 1 ngày',
                $item->quantity,
                $item->quantity*$item->book->price_rent*$this->numDateRent
            ];
        }
        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 22,
            'D' => 14
        ];
    }
}
