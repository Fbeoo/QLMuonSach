<?php

namespace App\Exports;

use App\Models\HistoryRentBook;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportRequestRentBookInDay implements FromCollection, WithMapping, WithHeadings,ShouldAutoSize, WithStyles,WithEvents
{
    protected $data;

    protected $totalPrice;

    protected $totalBookRent;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        foreach ($data as $item) {
            foreach ($item->detailHistoryRentBook as $value) {
                $this->totalBookRent += $value->quantity;
                $this->totalPrice += $value->quantity*$value->book->price_rent;
            }
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function map($row): array
    {
        $data = [];
        $check = true;
        foreach ($row->detailHistoryRentBook as $detail) {
            if ($row->status === HistoryRentBook::statusPending) {
                $status = 'Đang chờ';
            }
            else if ($row->status === HistoryRentBook::statusBorrowing) {
                $status = 'Đang mượn';
            }
            else if ($row->status === HistoryRentBook::statusRefuse) {
                $status = 'Từ chối';
            }
            else if ($row->status === HistoryRentBook::statusReturned) {
                $status = 'Đã trả';
            }
            $data[] = [
                $check ? $row->id : '',
                $check ? $row->user->name : '',
                $check ? $row->user->mail : '',
                $check ? $row->user->address : '',
                $check ? $row->rent_date : '',
                $check ? $row->expiration_date : '',
                $check ? $status : '',
                $detail->book->name,
                $detail->quantity,
                $detail->quantity*$detail->book->price_rent,
                $check ? $row->total_price : '',
            ];
            $check = false;
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            ['ID','Tên người thuê','Email','Địa chỉ','Ngày mượn','Ngày đến hạn trả','Trạng thái yêu cầu','Tên sách','Số lượng mượn','Thành tiền','Tổng tiền']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('J')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('K')->getNumberFormat()->setFormatCode('#,##0');

        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 20
            ]
        ]);

        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 20
            ]
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow() + 1;

                $event->sheet->setCellValue('I'.$lastRow,$this->totalBookRent);
                $event->sheet->setCellValue('K'.$lastRow,$this->totalPrice);

                $event->sheet->getStyle('I'.$lastRow.':K'.$lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);
                $event->sheet->getStyle('A3:K'.$lastRow)->applyFromArray([
                    'font' => [
                        'size' => '13'
                    ]
                ]);
            },
            BeforeSheet::class => function(BeforeSheet $event) {
                $event->sheet->setCellValue('A1','Thống kê yêu cầu mượn');
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight('50');

                $event->sheet->setCellValue('A2','Ngày '.now()->format('Y/m/d'));
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight('50');
            }
        ];
    }
}
