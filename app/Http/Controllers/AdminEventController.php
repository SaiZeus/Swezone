<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketCategory;
use App\Models\PromoCode;
use App\Models\Attendee;
use App\Models\Order;
use App\Models\EventItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Throwable;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::with([
                'ticketCategories',
                'items'
            ])
            ->withCount('ticketCategories')
            ->latest()
            ->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        if ($request->input('promo_ticket_category_id') === '') {
            $request->merge(['promo_ticket_category_id' => null]);
        }

        $validated = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string',
                'event_date' => 'required|date',
                'status' => 'required|in:upcoming,live,past',
                'creator_name' => 'required|string|max:255',
                'creator_phone' => 'required|string|max:50',
                'creator_email' => 'required|email|max:255',
                'overall_capacity' => 'nullable|integer|min:1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'english_waiver' => 'nullable|file|mimes:pdf|max:10240',
                'burmese_waiver' => 'nullable|file|mimes:pdf|max:10240',
                'english_race_guide' => 'nullable|file|mimes:pdf|max:10240',
                'burmese_race_guide' => 'nullable|file|mimes:pdf|max:10240',
                'categories' => 'required|array|min:1',
                'categories.*.name' => 'required|string|max:255',
                'categories.*.local_price' => 'required|numeric|min:0',
                'categories.*.foreign_price' => 'nullable|numeric|min:0',
                'categories.*.capacity' => 'nullable|integer|min:1',
                'categories.*.bib_prefix' => 'nullable|string|max:3',
                'categories.*.bib_start_number' => 'nullable|integer|min:1',
                'promo_code' => 'nullable|string|max:100',
                'promo_type' => 'nullable|in:fixed,percentage',
                'promo_value' => 'nullable|numeric|min:0',
                'promo_ticket_category_id' => 'nullable',
                'company_name' => 'nullable|string|max:255',
                'promo_quantity' => 'nullable|integer|min:1|max:500',
                'max_uses' => 'nullable|integer|min:1',
                'items' => 'nullable|array',
                'items.*.title' => 'nullable|string|max:255',
                'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'categories.required' => 'Please add at least one ticket category.',
                'categories.min' => 'Please add at least one ticket category.',
                'categories.*.name.required' => 'Every ticket category must have a name.',
                'categories.*.local_price.required' => 'Every ticket category must have a local price.',
                'categories.*.local_price.numeric' => 'Ticket category price must be a valid number.',
                'creator_name.required' => 'Please enter the event creator name.',
                'creator_phone.required' => 'Please enter the event creator phone number.',
                'creator_email.required' => 'Please enter the event creator email address.',
                'image.max' => 'The event banner must not be larger than 2MB.',
                'english_waiver.mimes' => 'The English waiver must be a valid PDF file.',
                'english_waiver.max' => 'The English waiver must not be larger than 10MB.',
                'burmese_waiver.mimes' => 'The Burmese waiver must be a valid PDF file.',
                'burmese_waiver.max' => 'The Burmese waiver must not be larger than 10MB.',
                'english_race_guide.mimes' => 'The English race guide must be a valid PDF file.',
                'english_race_guide.max' => 'The English race guide must not be larger than 10MB.',
                'burmese_race_guide.mimes' => 'The Burmese race guide must be a valid PDF file.',
                'items.*.image.max' => 'An event item image must not be larger than 2MB.',
            ]
        );

        try {
            DB::transaction(function () use ($request) {
                $imagePath = $request->hasFile('image') 
                    ? $request->file('image')->store('events', 'public') 
                    : null;

                $englishWaiverPath = $request->hasFile('english_waiver') 
                    ? $request->file('english_waiver')->store('waivers', 'public') 
                    : null;

                $burmeseWaiverPath = $request->hasFile('burmese_waiver') 
                    ? $request->file('burmese_waiver')->store('waivers', 'public') 
                    : null;

                $englishRaceGuidePath = $request->hasFile('english_race_guide') 
                    ? $request->file('english_race_guide')->store('race_guides', 'public') 
                    : null;

                $burmeseRaceGuidePath = $request->hasFile('burmese_race_guide') 
                    ? $request->file('burmese_race_guide')->store('race_guides', 'public') 
                    : null;

                $event = Event::create([
                    'title'                  => $request->title,
                    'description'            => $request->description,
                    'location'               => $request->location,
                    'event_date'             => $request->event_date,
                    'status'                 => $request->status,
                    'image'                  => $imagePath,
                    'english_waiver'         => $englishWaiverPath,
                    'burmese_waiver'         => $burmeseWaiverPath,
                    'english_race_guide'     => $englishRaceGuidePath,
                    'burmese_race_guide'     => $burmeseRaceGuidePath,
                    'creator_name'           => $request->creator_name,
                    'creator_phone'          => $request->creator_phone,
                    'creator_email'          => $request->creator_email,
                    'overall_capacity'       => $request->overall_capacity,
                    'enabled_fields'         => $request->input('enabled_fields', []),
                    'enable_bib_number'      => $request->has('enable_bib_number'),
                    'share_bib_prefix'       => $request->input('share_bib_prefix', '1') === '1',
                    'event_bib_prefix'       => strtoupper(substr($request->input('event_bib_prefix', ''), 0, 3)),
                    'event_bib_start_number' => $request->input('event_bib_start_number', 1),
                ]);

                $categoryMap = [];

                foreach ($request->categories as $index => $categoryData) {
                    $cat = TicketCategory::create([
                        'event_id'         => $event->id,
                        'name'             => $categoryData['name'],
                        'local_price'      => $categoryData['local_price'],
                        'foreign_price'    => $categoryData['foreign_price'] ?? null,
                        'capacity'         => $categoryData['capacity'] ?? null,
                        'tickets_sold'     => 0,
                        'bib_prefix'       => strtoupper(substr($categoryData['bib_prefix'] ?? '', 0, 3)),
                        'bib_start_number' => $categoryData['bib_start_number'] ?? 1,
                    ]);

                    $categoryMap[$index] = $cat;
                    $categoryMap[$cat->id] = $cat;
                }

                if ($request->filled('promo_code') && $request->filled('promo_value')) {
                    $promoTicketCategoryId = null;

                    if ($request->filled('promo_ticket_category_id')) {
                        $selectedVal = $request->promo_ticket_category_id;
                        if (isset($categoryMap[$selectedVal])) {
                            $promoTicketCategoryId = $categoryMap[$selectedVal]->id;
                        }
                    }

                    $quantity = (int) $request->input('promo_quantity', 1);
                    $companyName = $request->input('company_name');
                    $baseCode = strtoupper(trim($request->promo_code));

                    if ($quantity > 1) {
                        for ($i = 1; $i <= $quantity; $i++) {
                            $formattedCode = $baseCode . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

                            PromoCode::create([
                                'event_id'           => $event->id,
                                'ticket_category_id' => $promoTicketCategoryId,
                                'company_name'       => $companyName,
                                'code'               => $formattedCode,
                                'discount_type'      => $request->promo_type ?? 'fixed',
                                'discount_value'     => $request->promo_value,
                                'max_uses'           => 1,
                                'uses_count'         => 0,
                                'status'             => 'active',
                            ]);
                        }
                    } else {
                        PromoCode::create([
                            'event_id'           => $event->id,
                            'ticket_category_id' => $promoTicketCategoryId,
                            'company_name'       => $companyName,
                            'code'               => $baseCode,
                            'discount_type'      => $request->promo_type ?? 'fixed',
                            'discount_value'     => $request->promo_value,
                            'max_uses'           => $request->input('max_uses', 1),
                            'uses_count'         => 0,
                            'status'             => 'active',
                        ]);
                    }
                }

                if ($request->has('items')) {
                    foreach ($request->items as $itemData) {
                        if (empty($itemData['title']) && empty($itemData['image'])) {
                            continue;
                        }

                        $itemImage = null;
                        if (isset($itemData['image']) && $itemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $itemImage = $itemData['image']->store('event-items', 'public');
                        }

                        EventItem::create([
                            'event_id' => $event->id,
                            'title' => $itemData['title'] ?? null,
                            'image' => $itemImage,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('admin.events.index')
                ->with('success', 'Event created successfully!');

        } catch (Throwable $e) {
            \Log::error('Event creation failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'The event could not be published. Please check the form and try again.');
        }
    }

    public function edit($id)
    {
        $event = Event::with([
            'ticketCategories',
            'promoCodes',
            'items'
        ])->findOrFail($id);

        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::with([
            'ticketCategories',
            'promoCodes',
            'items'
        ])->findOrFail($id);

        if ($request->input('promo_ticket_category_id') === '') {
            $request->merge(['promo_ticket_category_id' => null]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'status' => 'required|in:upcoming,live,past',
            'creator_name' => 'required|string|max:255',
            'creator_phone' => 'required|string|max:50',
            'creator_email' => 'required|email|max:255',
            'overall_capacity' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'english_waiver' => 'nullable|file|mimes:pdf|max:10240',
            'burmese_waiver' => 'nullable|file|mimes:pdf|max:10240',
            'english_race_guide' => 'nullable|file|mimes:pdf|max:10240',
            'burmese_race_guide' => 'nullable|file|mimes:pdf|max:10240',
            'categories' => 'required|array|min:1',
            'categories.*.id' => 'nullable|integer',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.local_price' => 'required|numeric|min:0',
            'categories.*.foreign_price' => 'nullable|numeric|min:0',
            'categories.*.capacity' => 'nullable|integer|min:1',
            'categories.*.bib_prefix' => 'nullable|string|max:3',
            'categories.*.bib_start_number' => 'nullable|integer|min:1',
            'promo_code' => 'nullable|string|max:100',
            'promo_type' => 'nullable|in:fixed,percentage',
            'promo_value' => 'nullable|numeric|min:0',
            'promo_ticket_category_id' => 'nullable',
            'company_name' => 'nullable|string|max:255',
            'promo_quantity' => 'nullable|integer|min:1|max:500',
            'max_uses' => 'nullable|integer|min:1',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|integer',
            'items.*.title' => 'nullable|string|max:255',
            'items.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_items' => 'nullable|array',
            'deleted_items.*' => 'integer',
        ]);

        DB::transaction(function () use ($request, $event) {
            if ($request->hasFile('image')) {
                if ($event->image) {
                    Storage::disk('public')->delete($event->image);
                }
                $event->image = $request->file('image')->store('events', 'public');
            }

            if ($request->hasFile('english_waiver')) {
                if ($event->english_waiver) {
                    Storage::disk('public')->delete($event->english_waiver);
                }
                $event->english_waiver = $request->file('english_waiver')->store('waivers', 'public');
            }

            if ($request->hasFile('burmese_waiver')) {
                if ($event->burmese_waiver) {
                    Storage::disk('public')->delete($event->burmese_waiver);
                }
                $event->burmese_waiver = $request->file('burmese_waiver')->store('waivers', 'public');
            }

            if ($request->hasFile('english_race_guide')) {
                if ($event->english_race_guide) {
                    Storage::disk('public')->delete($event->english_race_guide);
                }
                $event->english_race_guide = $request->file('english_race_guide')->store('race_guides', 'public');
            }

            if ($request->hasFile('burmese_race_guide')) {
                if ($event->burmese_race_guide) {
                    Storage::disk('public')->delete($event->burmese_race_guide);
                }
                $event->burmese_race_guide = $request->file('burmese_race_guide')->store('race_guides', 'public');
            }

            $event->update([
                'title'                  => $request->title,
                'description'            => $request->description,
                'location'               => $request->location,
                'event_date'             => $request->event_date,
                'status'                 => $request->status,
                'creator_name'           => $request->creator_name,
                'creator_phone'          => $request->creator_phone,
                'creator_email'          => $request->creator_email,
                'overall_capacity'       => $request->overall_capacity,
                'enabled_fields'         => $request->input('enabled_fields', []),
                'enable_bib_number'      => $request->has('enable_bib_number'),
                'share_bib_prefix'       => $request->input('share_bib_prefix', '1') === '1',
                'event_bib_prefix'       => strtoupper(substr($request->input('event_bib_prefix', ''), 0, 3)),
                'event_bib_start_number' => $request->input('event_bib_start_number', 1),
                'image'                  => $event->image,
                'english_waiver'         => $event->english_waiver,
                'burmese_waiver'         => $event->burmese_waiver,
                'english_race_guide'     => $event->english_race_guide,
                'burmese_race_guide'     => $event->burmese_race_guide,
            ]);

            $submittedCategoryIds = [];
            $categoryMap = [];

            foreach ($request->categories as $index => $categoryData) {
                if (!empty($categoryData['id'])) {
                    $category = TicketCategory::where('id', $categoryData['id'])
                        ->where('event_id', $event->id)
                        ->first();

                    if ($category) {
                        $category->update([
                            'name'             => $categoryData['name'],
                            'local_price'      => $categoryData['local_price'],
                            'foreign_price'    => $categoryData['foreign_price'] ?? null,
                            'capacity'         => $categoryData['capacity'] ?? null,
                            'bib_prefix'       => strtoupper(substr($categoryData['bib_prefix'] ?? '', 0, 3)),
                            'bib_start_number' => $categoryData['bib_start_number'] ?? 1,
                        ]);

                        $submittedCategoryIds[] = $category->id;
                        $categoryMap[$category->id] = $category;
                        $categoryMap[$index] = $category;
                    }
                } else {
                    $category = TicketCategory::create([
                        'event_id'         => $event->id,
                        'name'             => $categoryData['name'],
                        'local_price'      => $categoryData['local_price'],
                        'foreign_price'    => $categoryData['foreign_price'] ?? null,
                        'capacity'         => $categoryData['capacity'] ?? null,
                        'tickets_sold'     => 0,
                        'bib_prefix'       => strtoupper(substr($categoryData['bib_prefix'] ?? '', 0, 3)),
                        'bib_start_number' => $categoryData['bib_start_number'] ?? 1,
                    ]);

                    $submittedCategoryIds[] = $category->id;
                    $categoryMap[$category->id] = $category;
                    $categoryMap[$index] = $category;
                }
            }

            $existingCategoryIds = $event->ticketCategories()->pluck('id')->toArray();
            $categoriesToDelete = array_diff($existingCategoryIds, $submittedCategoryIds);

            foreach ($categoriesToDelete as $categoryId) {
                $category = TicketCategory::find($categoryId);
                if ($category && $category->attendees()->count() === 0) {
                    $category->delete();
                }
            }

            if ($request->filled('deleted_items')) {
                $itemsToDelete = EventItem::where('event_id', $event->id)
                    ->whereIn('id', $request->deleted_items)
                    ->get();

                foreach ($itemsToDelete as $item) {
                    if ($item->image) {
                        Storage::disk('public')->delete($item->image);
                    }
                    $item->delete();
                }
            }

            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $title = $itemData['title'] ?? null;

                    if (!empty($itemData['id'])) {
                        $item = EventItem::where('id', $itemData['id'])
                            ->where('event_id', $event->id)
                            ->first();

                        if (!$item) {
                            continue;
                        }

                        $item->title = $title;

                        if (isset($itemData['image']) && $itemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            if ($item->image) {
                                Storage::disk('public')->delete($item->image);
                            }
                            $item->image = $itemData['image']->store('event-items', 'public');
                        }

                        $item->save();
                    } else {
                        if (empty($title) && empty($itemData['image'])) {
                            continue;
                        }

                        $itemImage = null;
                        if (isset($itemData['image']) && $itemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $itemImage = $itemData['image']->store('event-items', 'public');
                        }

                        EventItem::create([
                            'event_id' => $event->id,
                            'title' => $title,
                            'image' => $itemImage,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Display Promo Codes management page for a specific event.
     */
    public function promoCodes($id)
    {
        $event = Event::with(['ticketCategories', 'promoCodes'])->findOrFail($id);
        return view('admin.events.promo_codes', compact('event'));
    }

    /**
     * Store single or bulk promo codes for a specific event.
     */
    public function storePromoCode(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'company_name'   => 'nullable|string|max:255',
            'code'           => 'required|string|max:50',
            'discount_type'  => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'promo_quantity' => 'required|integer|min:1|max:500',
            'max_uses'       => 'required|integer|min:1',
            'ticket_category_id' => 'nullable|exists:ticket_categories,id',
        ]);

        $quantity = (int) $request->input('promo_quantity', 1);
        $baseCode = strtoupper(trim($request->input('code')));

        if ($quantity > 1) {
            for ($i = 1; $i <= $quantity; $i++) {
                $formattedIndex = str_pad($i, 3, '0', STR_PAD_LEFT);
                $generatedCode  = "{$baseCode}-{$formattedIndex}";

                PromoCode::create([
                    'event_id'           => $event->id,
                    'company_name'       => $request->input('company_name'),
                    'code'               => $generatedCode,
                    'discount_type'      => $request->input('discount_type'),
                    'discount_value'     => $request->input('discount_value'),
                    'max_uses'           => 1,
                    'uses_count'         => 0,
                    'ticket_category_id' => $request->input('promo_scope') === 'ticket' ? $request->input('ticket_category_id') : null,
                    'status'             => 'active',
                ]);
            }
            return redirect()->back()->with('success', "Generated {$quantity} promo codes starting with {$baseCode}-001!");
        }

        PromoCode::create([
            'event_id'           => $event->id,
            'company_name'       => $request->input('company_name'),
            'code'               => $baseCode,
            'discount_type'      => $request->input('discount_type'),
            'discount_value'     => $request->input('discount_value'),
            'max_uses'           => $request->input('max_uses', 1),
            'uses_count'         => 0,
            'ticket_category_id' => $request->input('promo_scope') === 'ticket' ? $request->input('ticket_category_id') : null,
            'status'             => 'active',
        ]);

        return redirect()->back()->with('success', "Promo code {$baseCode} created successfully!");
    }

    /**
     * Delete a promo code.
     */
    public function destroyPromoCode($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->delete();

        return redirect()->back()->with('success', 'Promo code deleted successfully!');
    }

    public function attendees($eventId)
    {
        $event = Event::with('ticketCategories')->findOrFail($eventId);

        $attendees = Attendee::whereHas('ticketCategory', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            ->whereHas('order', function ($q) {
                $q->whereRaw("LOWER(payment_status) IN ('paid', 'approved', 'completed')");
            })
            ->with(['ticketCategory', 'order', 'promoCode'])
            ->get();

        $totalRevenue = Order::where(function ($q) use ($eventId) {
                $q->where('event_id', $eventId)
                    ->orWhereHas('attendees.ticketCategory', function ($sub) use ($eventId) {
                        $sub->where('event_id', $eventId);
                    });
            })
            ->whereRaw("LOWER(payment_status) IN ('paid', 'approved', 'completed')")
            ->sum('total_amount');

        return view('admin.events.attendees', compact('event', 'attendees', 'totalRevenue'));
    }

    public function updateAttendee(Request $request, $id)
    {
        $attendee = Attendee::findOrFail($id);

        $validated = $request->validate([
            'full_name'             => 'required|string|max:255',
            'father_name'           => 'nullable|string|max:255',
            'email'                 => 'required|email|max:255',
            'phone'                 => 'required|string|max:50',
            'viber'                 => 'nullable|string|max:50',
            'emergency_contact'     => 'nullable|string|max:50',
            'nationality'           => 'required|string',
            'nrc_passport'          => 'required|string|max:100',
            'country'               => 'nullable|string|max:100',
            'gender'                => 'nullable|string',
            'date_of_birth'         => 'nullable|date',
            'bib_number'            => 'nullable|string|max:50',
            'bib_name'              => 'nullable|string|max:10',
            'tshirt_size'           => 'required|string',
            'blood_type'            => 'nullable|string',
            'has_medical_condition' => 'nullable|string',
            'medical_details'       => 'nullable|string',
            'itra'                  => 'nullable|string',
            'itra_details'          => 'nullable|string',
            'address'               => 'nullable|string',
            'experience'            => 'nullable|string',
        ]);

        $attendee->update($validated);

        return back()->with('success', 'Attendee updated successfully!');
    }

    public function destroyAttendee($id)
    {
        $attendee = Attendee::findOrFail($id);
        
        if ($attendee->ticketCategory && $attendee->ticketCategory->tickets_sold > 0) {
            $attendee->ticketCategory->decrement('tickets_sold');
        }

        $attendee->delete();

        return back()->with('success', 'Attendee deleted successfully!');
    }

    public function downloadAttendeeTicket($id)
{
    ini_set('memory_limit', '1024M');

    $attendee = Attendee::with('ticketCategory.event')->findOrFail($id);

    if (empty($attendee->verification_token)) {
        $attendee->verification_token = Str::random(64);
        $attendee->save();
    }

    $event = $attendee->ticketCategory->event;

    $bannerBase64 = null;
    if ($event->image && Storage::disk('public')->exists($event->image)) {
        $bannerPath = storage_path('app/public/' . $event->image);
        if (file_exists($bannerPath) && filesize($bannerPath) <= 2 * 1024 * 1024) {
            $bannerBase64 = $this->resizeImageToBase64($bannerPath, 800, 300);
        }
    }

    $logoPath = public_path('assets/img/logo/Swezon_Logo1.1V.png');
    $logoBase64 = (file_exists($logoPath) && filesize($logoPath) <= 2 * 1024 * 1024) 
        ? $this->resizeImageToBase64($logoPath, 300, 100) 
        : null;

    $verificationUrl = route('ticket.verify', ['token' => $attendee->verification_token]);
    $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=75x75&data=' . urlencode($verificationUrl);
    $qrImageData = @file_get_contents($qrApiUrl);
    $qrBase64 = $qrImageData ? 'data:image/png;base64,' . base64_encode($qrImageData) : null;

    $pdf = Pdf::setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true, // Enabled to allow loading Google Fonts for Myanmar text
        'defaultFont' => 'sans-serif',
        'isFontSubsettingEnabled' => true,
    ])->loadView('emails.ticket_pdf', [
        'attendee' => $attendee,
        'bannerBase64' => $bannerBase64,
        'logoBase64' => $logoBase64,
        'qrBase64' => $qrBase64
    ]);

    $filename = 'Ticket_' . ($attendee->ticket_uuid ?? $attendee->id) . '.pdf';

    return $pdf->download($filename);
}

private function resizeImageToBase64($filePath, $maxWidth, $maxHeight)
{
    if (!file_exists($filePath)) {
        return null;
    }

    $imageInfo = @getImageSize($filePath);
    if (!$imageInfo) {
        return null;
    }

    list($origWidth, $origHeight, $imageType) = $imageInfo;

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = @imagecreatefromjpeg($filePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = @imagecreatefrompng($filePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = @imagecreatefromgif($filePath);
            break;
        default:
            return null;
    }

    if (!$sourceImage) {
        return null;
    }

    $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
    if ($ratio > 1) {
        $ratio = 1;
    }
    
    $newWidth = max(1, round($origWidth * $ratio));
    $newHeight = max(1, round($origHeight * $ratio));

    $virtualImage = imagecreatetruecolor($newWidth, $newHeight);

    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagecolortransparent($virtualImage, imagecolorallocatealpha($virtualImage, 0, 0, 0, 127));
        imagealphablending($virtualImage, false);
        imagesavealpha($virtualImage, true);
    }

    imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

    ob_start();
    if ($imageType == IMAGETYPE_JPEG) {
        imagejpeg($virtualImage, null, 75);
        $mime = 'image/jpeg';
    } else {
        imagepng($virtualImage, null, 6);
        $mime = 'image/png';
    }
    $imageData = ob_get_clean();

    imagedestroy($sourceImage);
    imagedestroy($virtualImage);

    return 'data:' . $mime . ';base64,' . base64_encode($imageData);
}

    public function destroy($id)
    {
        $event = Event::with([
            'ticketCategories',
            'promoCodes',
            'items'
        ])->findOrFail($id);

        $categoryIds = $event->ticketCategories()->pluck('id');
        Attendee::whereIn('ticket_category_id', $categoryIds)->delete();

        Order::where('event_id', $event->id)->delete();
        $event->ticketCategories()->delete();
        $event->promoCodes()->delete();

        foreach ($event->items as $item) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->delete();
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        if ($event->english_waiver) {
            Storage::disk('public')->delete($event->english_waiver);
        }
        if ($event->burmese_waiver) {
            Storage::disk('public')->delete($event->burmese_waiver);
        }
        if ($event->english_race_guide) {
            Storage::disk('public')->delete($event->english_race_guide);
        }
        if ($event->burmese_race_guide) {
            Storage::disk('public')->delete($event->burmese_race_guide);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}