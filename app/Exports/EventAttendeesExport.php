<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventAttendeesExport implements WithMultipleSheets
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1: All Attendees & Summary Stats (using EventAttendeesSheet or EventAllAttendeesSheet)
        $sheets[] = new EventAttendeesSheet($this->event, null, 'All');

        // Subsequent Sheets: Individual Ticket Categories
        foreach ($this->event->ticketCategories as $category) {
            $sheetName = substr(preg_replace('/[[:punct:]]/', '', $category->name), 0, 31);
            $sheets[] = new EventCategoryAttendeesSheet($this->event, $category->id, $sheetName);
        }

        return $sheets;
    }
}