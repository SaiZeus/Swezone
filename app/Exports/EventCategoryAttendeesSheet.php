<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Attendee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EventCategoryAttendeesSheet implements FromCollection, WithTitle, WithMapping, ShouldAutoSize, WithStyles, WithCustomStartCell, WithColumnWidths
{
    protected $event;
    protected $categoryId;
    protected $sheetTitle;

    public function __construct(Event $event, $categoryId, $sheetTitle)
    {
        $this->event = $event;
        $this->categoryId = $categoryId;
        $this->sheetTitle = $sheetTitle;
    }

    public function collection()
    {
        $attendees = Attendee::with(['ticketCategory', 'promoCode', 'order'])
            ->where('ticket_category_id', $this->categoryId)
            ->whereNull('deleted_at')
            ->get();

        return $attendees->sort(function ($a, $b) {
            preg_match('/(\d+)/', (string)($a->ticket_code ?? ''), $matchA);
            preg_match('/(\d+)/', (string)($b->ticket_code ?? ''), $matchB);

            $numA = isset($matchA[1]) ? (int)$matchA[1] : PHP_INT_MAX;
            $numB = isset($matchB[1]) ? (int)$matchB[1] : PHP_INT_MAX;

            if ($numA === $numB) {
                return ((int)$a->id) <=> ((int)$b->id);
            }
            return $numA <=> $numB;
        })->values();
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function startCell(): string
    {
        return 'A7'; // Fixed clean start row for individual category sheets
    }

    public function map($attendee): array
    {
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

        return [
            $attendee->ticket_code ?? '',
            $attendee->full_name ?? '',
            $attendee->father_name ?? '',
            $bib,
            optional($attendee->ticketCategory)->name ?? '',
            $promoText,
            $contact,
            $attendee->emergency_contact ?? '',
            $attendee->nrc_passport ?? '',
            $attendee->country ?? '',
            $demographics,
            $healthText,
            $addressExp,
        ];
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
        $cat = \App\Models\TicketCategory::find($this->categoryId);
        $soldCount = $this->collection()->count();
        $catRevenue = $soldCount * ($cat->local_price ?? 0);

        // Title
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', strtoupper($this->event->title) . ' - ' . strtoupper($this->sheetTitle) . ' PARTICIPANTS REPORT');
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Summary
        $sheet->setCellValue('A2', 'CATEGORY REVENUE');
        $sheet->setCellValue('B2', number_format($catRevenue) . ' MMK');
        $sheet->setCellValue('D2', 'TOTAL TICKETS SOLD');
        $sheet->setCellValue('E2', $soldCount);
        $sheet->getStyle('A2:M2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => '374151']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F3F4F6']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(24);

        // Headers at Row 6
        $headings = [
            'Reg Code', 'Runner Name', 'Father Name', 'BIB', 'Category', 
            'Promo Applied', 'Contact', 'Emergency (ICE)', 'NRC / Passport', 
            'Country', 'Demographics', 'Health & ITRA', 'Address & Experience'
        ];

        $col = 'A';
        foreach ($headings as $heading) {
            $sheet->setCellValue($col . '6', $heading);
            $col++;
        }

        $sheet->getStyle('A6:M6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '3730A3']]],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(32);

        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 6) {
            $sheet->getStyle('A7:M' . $highestRow)->applyFromArray([
                'font' => ['size' => 9, 'color' => ['argb' => '1F2937']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'E5E7EB']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
            ]);

            for ($row = 7; $row <= $highestRow; $row++) {
                $sheet->getRowDimension($row)->setRowHeight(42);
                if ($row % 2 === 0) {
                    $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F9FAFB']],
                    ]);
                }
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->getStyle('B' . $row)->getFont()->setBold(true);
            }
            $sheet->setAutoFilter('A6:M' . $highestRow);
        }

        $sheet->freezePane('A7');
        $sheet->getSheetView()->setZoomScale(80);

        return [];
    }
}