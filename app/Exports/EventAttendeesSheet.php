<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Attendee;
use App\Models\Order;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class EventAttendeesSheet implements
    FromCollection,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected $event;
    protected $categoryId;
    protected $sheetTitle;

    public function __construct(Event $event, $categoryId = null, $sheetTitle = 'All')
    {
        $this->event = $event;
        $this->categoryId = $categoryId;
        $this->sheetTitle = $sheetTitle;
    }

    public function collection()
    {
        return collect([]);
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, 'B' => 25, 'C' => 25, 'D' => 15, 'E' => 20,
            'F' => 25, 'G' => 42, 'H' => 25, 'I' => 22, 'J' => 15,
            'K' => 50, 'L' => 50, 'M' => 55,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $query = Attendee::with(['ticketCategory', 'promoCode', 'order'])
                    ->whereHas('ticketCategory', function ($q) {
                        $q->where('event_id', $this->event->id);
                    });

                if (!is_null($this->categoryId)) {
                    $query->where('ticket_category_id', $this->categoryId);
                }

                $attendees = $query->get()->sort(function ($a, $b) {
                    preg_match('/(\d+)/', (string)($a->ticket_code ?? ''), $matchA);
                    preg_match('/(\d+)/', (string)($b->ticket_code ?? ''), $matchB);

                    $numA = isset($matchA[1]) ? (int)$matchA[1] : PHP_INT_MAX;
                    $numB = isset($matchB[1]) ? (int)$matchB[1] : PHP_INT_MAX;

                    if ($numA === $numB) {
                        return ((int)$a->id) <=> ((int)$b->id);
                    }
                    return $numA <=> $numB;
                })->values();

                $totalRevenue = Order::where(function ($q) {
                    $q->where('event_id', $this->event->id)
                      ->orWhereHas('attendees.ticketCategory', function ($sub) {
                          $sub->where('event_id', $this->event->id);
                      });
                })
                ->whereIn(\DB::raw('LOWER(payment_status)'), ['paid', 'approved', 'completed'])
                ->sum('total_amount');

                $totalParticipants = Attendee::whereHas('ticketCategory', function ($q) {
                    $q->where('event_id', $this->event->id);
                })->count();

                // Title
                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', strtoupper($this->event->title) . ' - PARTICIPANTS & TICKET SALES REPORT');
                $sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4F46E5']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(32);

                // Summary
                $sheet->setCellValue('A2', 'TOTAL REVENUE');
                $sheet->setCellValue('B2', number_format($totalRevenue) . ' MMK');
                $sheet->setCellValue('D2', 'TOTAL PARTICIPANTS');
                $sheet->setCellValue('E2', $totalParticipants);
                $sheet->getStyle('A2:M2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => '374151']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F3F4F6']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(24);

                // Category Breakdown
                $sheet->setCellValue('A4', 'TICKET CATEGORY BREAKDOWN');
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(11)->getColor()->setARGB('4F46E5');

                $sheet->setCellValue('A5', 'Category Name');
                $sheet->setCellValue('B5', 'Tickets Sold');
                $sheet->setCellValue('C5', 'Revenue Generated');
                $sheet->getStyle('A5:C5')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '6366F1']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '4338CA']]],
                ]);

                $currentRow = 6;
                foreach ($this->event->ticketCategories as $category) {
                    $soldCount = Attendee::where('ticket_category_id', $category->id)->count();
                    $catRevenue = $soldCount * $category->local_price;

                    $sheet->setCellValue('A' . $currentRow, $category->name);
                    $sheet->setCellValue('B' . $currentRow, $soldCount);
                    $sheet->setCellValue('C' . $currentRow, number_format($catRevenue) . ' MMK');

                    $sheet->getStyle('A' . $currentRow . ':C' . $currentRow)->applyFromArray([
                        'font' => ['size' => 9, 'color' => ['argb' => '1F2937']],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'E5E7EB']]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                    $currentRow++;
                }

                $tableHeaderRow = $currentRow + 1;
                $headings = [
                    'Reg Code', 'Runner Name', 'Father Name', 'BIB', 'Category', 
                    'Promo Applied', 'Contact', 'Emergency (ICE)', 'NRC / Passport', 
                    'Country', 'Demographics', 'Health & ITRA', 'Address & Experience'
                ];

                $col = 'A';
                foreach ($headings as $heading) {
                    $sheet->setCellValue($col . $tableHeaderRow, $heading);
                    $col++;
                }

                $sheet->getStyle('A' . $tableHeaderRow . ':M' . $tableHeaderRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4F46E5']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '3730A3']]],
                ]);
                $sheet->getRowDimension($tableHeaderRow)->setRowHeight(32);

                $dataRow = $tableHeaderRow + 1;
                foreach ($attendees as $attendee) {
                    $promoText = '';
                    if ($attendee->promoCode) {
                        $promo = $attendee->promoCode;
                        $promoValue = $promo->discount_type === 'percentage' ? $promo->discount_value . '%' : number_format($promo->discount_value) . ' MMK';
                        $promoText = $promo->code . ' (' . $promoValue . ')';
                    }

                    $healthText = 'Blood: ' . ($attendee->blood_type ?? 'N/A');
                    if (strtolower((string)$attendee->has_medical_condition) === 'yes') {
                        $healthText .= ' | Medical: ' . ($attendee->medical_details ?? 'Yes');
                    }
                    if (strtolower((string)$attendee->itra) === 'yes') {
                        $healthText .= ' | ITRA: ' . ($attendee->itra_details ?: 'Yes');
                    }

                    $dob = 'N/A';
                    if ($attendee->date_of_birth) {
                        try { $dob = Carbon::parse($attendee->date_of_birth)->format('Y-m-d'); } catch (\Exception $e) { $dob = $attendee->date_of_birth; }
                    }

                    $demographics = 'Gender: ' . ucfirst($attendee->gender ?? 'N/A') . ' | DOB: ' . $dob . ' | Nationality: ' . ($attendee->nationality ?? 'N/A') . ' | Size: ' . ($attendee->tshirt_size ?? 'N/A');
                    $contact = 'Email: ' . ($attendee->email ?? '') . ' | Phone: ' . ($attendee->phone ?? '') . ($attendee->viber ? ' | Viber: ' . $attendee->viber : '');
                    $addressExp = 'Address: ' . ($attendee->address ?? 'N/A') . ' | Experience: ' . ($attendee->experience ?? 'N/A');
                    $bib = $attendee->bib_number ?? $attendee->bib_name ?? '';

                    $sheet->setCellValue('A' . $dataRow, $attendee->ticket_code ?? '');
                    $sheet->setCellValue('B' . $dataRow, $attendee->full_name ?? '');
                    $sheet->setCellValue('C' . $dataRow, $attendee->father_name ?? '');
                    $sheet->setCellValue('D' . $dataRow, $bib);
                    $sheet->setCellValue('E' . $dataRow, optional($attendee->ticketCategory)->name ?? '');
                    $sheet->setCellValue('F' . $dataRow, $promoText);
                    $sheet->setCellValue('G' . $dataRow, $contact);
                    $sheet->setCellValue('H' . $dataRow, $attendee->emergency_contact ?? '');
                    $sheet->setCellValue('I' . $dataRow, $attendee->nrc_passport ?? '');
                    $sheet->setCellValue('J' . $dataRow, $attendee->country ?? '');
                    $sheet->setCellValue('K' . $dataRow, $demographics);
                    $sheet->setCellValue('L' . $dataRow, $healthText);
                    $sheet->setCellValue('M' . $dataRow, $addressExp);

                    $sheet->getStyle('A' . $dataRow . ':M' . $dataRow)->applyFromArray([
                        'font' => ['size' => 9, 'color' => ['argb' => '1F2937']],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'E5E7EB']]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                    ]);

                    $sheet->getRowDimension($dataRow)->setRowHeight(42);

                    if ($dataRow % 2 === 0) {
                        $sheet->getStyle('A' . $dataRow . ':M' . $dataRow)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F9FAFB']],
                        ]);
                    }

                    $sheet->getStyle('A' . $dataRow)->getFont()->setBold(true);
                    $sheet->getStyle('B' . $dataRow)->getFont()->setBold(true);

                    $dataRow++;
                }

                $highestRow = $dataRow - 1;
                if ($highestRow > $tableHeaderRow) {
                    $sheet->setAutoFilter('A' . $tableHeaderRow . ':M' . $highestRow);
                }

                $sheet->freezePane('A' . ($tableHeaderRow + 1));
                $sheet->getSheetView()->setZoomScale(80);

                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($tableHeaderRow, $tableHeaderRow);
            },
        ];
    }
}