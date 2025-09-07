<?php

namespace App\Http\Controllers;

use App\Models\AccomdationPlan;
use App\Models\AccomodationPlan;
use App\Models\Faq;
use App\Models\FrontpageSiteSetting;
use App\Models\HotelAward;
use App\Models\Room;
use App\Models\Promo;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Models\NearbyLocation;
use App\Models\HotelFacilities;
use App\Models\HotelFacility;
use App\Models\HotelService;
use App\Models\RoomReview;
use App\Models\Saldo;
use App\Models\SiteSettingPartner;
use App\Models\SiteSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FrontpageController extends Controller
{
    public function index() {
        // Menggunakan collect() untuk membuat collection kosong jika data tidak ditemukan
        $faqs = Faq::all();
        $nearby_locations = NearbyLocation::all();
        $hotel_facilities = HotelFacility::all();
        $rooms = Room::all();
        
        // Menggunakan first() dengan default fallback atau membuat objek kosong
        $site_setting = SiteSettings::where('id', 1)->first() ?? new SiteSettings();
        $frontpage_site_setting = FrontpageSiteSetting::where('id', 1)->first() ?? new FrontpageSiteSetting();
        
        $partners = SiteSettingPartner::all();
        $room_reviews = RoomReview::where('visibility', 'Tampilkan')->limit(4)->get();
        $hotel_services = HotelService::limit(4)->get();
        $hotel_awards = HotelAward::limit(4)->get();
        
        return view('frontpage.index', compact(
            'faqs', 
            'nearby_locations', 
            'hotel_facilities', 
            'rooms', 
            'site_setting', 
            'partners', 
            'room_reviews', 
            'hotel_services', 
            'hotel_awards', 
            'frontpage_site_setting'
        ));
    }

    public function checkout(String $id, Request $request){
        $room = Room::find($id);
        
        // Pastikan room ditemukan
        if (!$room) {
            return redirect()->route('frontpage.rooms')->with('error', 'Kamar tidak ditemukan');
        }

        $accomodation_plans = AccomodationPlan::all();
        $promos = Promo::where('is_all', true)->get();

        $user = Auth::user();
        if($user) {
            $checkIn = $request->check_in ?? session('check_in');
            $checkOut = $request->check_out ?? session('check_out');
            
            if ($checkIn && $checkOut) {
                session([
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ]);
                $saldo = Saldo::where('user_id', $user->id)
                        ->latest()
                        ->first() ?? new Saldo(['amount' => 0]);
                        
                return view('frontpage.checkout', compact('room', 'accomodation_plans', 'promos', 'saldo'));
            } else {
                return redirect()->back()->with('error', 'Tanggal check-in wajib diisi!');
            }
        } else {
            return redirect()->route('login')->with('error', 'Silahkan login untuk melakukan reservasi');
        }
    }


    public function promo() {
        $promos = Promo::all();
        return view('frontpage.promo', compact('promos'));
    }

    public function rooms(Request $request) {
        $room_id = $request->input('room_id');

        $rooms = Room::when($room_id, function($query) use ($room_id) {
            return $query->where('id', $room_id);
        })->get();

        return view('frontpage.rooms', compact('rooms', 'room_id'));
    }

    public function room_detail(Room $room) 
    {
        try {
            // Null checking untuk room yang diterima dari route model binding
            if (!$room || !$room->exists) {
                return redirect()->route('frontpage.rooms')
                    ->with('error', 'Kamar tidak ditemukan.');
            }

            // Safe retrieval dengan fallback
            $site_setting = SiteSettings::where('id', 1)->first();
            if (!$site_setting) {
                $site_setting = new SiteSettings(); // Create empty object untuk avoid null errors
            }

            // Get other rooms dengan exception handling
            $other_room = collect(); // Default empty collection
            try {
                $other_room = Room::whereNot('id', $room->id)
                    ->where('available_rooms', '>', 0) // Only show available rooms
                    ->limit(6) // Limit untuk performance
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Error fetching other rooms: ' . $e->getMessage());
            }

            // Get reviews dengan safe pagination
            $reviews = collect(); // Default empty collection
            try {
                $reviews = RoomReview::where('room_id', $room->id)
                    ->where('visibility', 'Tampilkan')
                    ->with('user') // Eager load user untuk avoid N+1 queries
                    ->paginate(5);
            } catch (\Exception $e) {
                Log::warning('Error fetching reviews: ' . $e->getMessage());
            }

            // Prepare additional safe data
            $room_data = $this->prepareRoomData($room);

            return view('frontpage.room-detail', compact(
                'room', 
                'other_room', 
                'reviews', 
                'site_setting',
                'room_data'
            ));

        } catch (\Exception $e) {
            Log::error('Room detail error: ' . $e->getMessage());
            
            return redirect()->route('frontpage.rooms')
                ->with('error', 'Terjadi kesalahan saat memuat detail kamar.');
        }
    }

    /**
     * Prepare room data dengan null-safe processing
     */
    private function prepareRoomData(Room $room)
    {
        $data = [
            'has_cover' => !empty($room->cover) && is_string($room->cover),
            'has_photos' => false,
            'photos_array' => [],
            'has_facilities' => false,
            'facilities_count' => 0,
            'has_description' => !empty($room->description) && is_string($room->description),
            'formatted_price' => null,
            'availability_status' => 'unknown',
            'availability_message' => '',
            'average_rating' => 0,
            'total_reviews' => 0,
        ];

        // Process photos
        if (!empty($room->photos) && is_string($room->photos)) {
            $photos = array_filter(explode('|', $room->photos)); // Remove empty elements
            if (count($photos) > 0) {
                $data['has_photos'] = true;
                $data['photos_array'] = $photos;
            }
        }

        // Process facilities
        try {
            if ($room->room_facility && is_countable($room->room_facility)) {
                $facilities = collect($room->room_facility)->filter(function($facility) {
                    return $facility && !empty($facility->name);
                });
                
                $data['has_facilities'] = $facilities->count() > 0;
                $data['facilities_count'] = $facilities->count();
            }
        } catch (\Exception $e) {
            Log::warning('Error processing room facilities: ' . $e->getMessage());
        }

        // Format price
        if ($room->price && is_numeric($room->price) && $room->price > 0) {
            $data['formatted_price'] = 'Rp. ' . number_format($room->price, 0, ',', '.');
        }

        // Availability status
        if (isset($room->available_rooms)) {
            if ($room->available_rooms == 0) {
                $data['availability_status'] = 'sold_out';
                $data['availability_message'] = 'SOLD OUT';
            } elseif ($room->available_rooms < 10 && $room->available_rooms > 0) {
                $data['availability_status'] = 'limited';
                $data['availability_message'] = "Tersisa {$room->available_rooms} kamar lagi!";
            } else {
                $data['availability_status'] = 'available';
            }
        }

        // Calculate reviews stats
        try {
            $reviews = RoomReview::where('room_id', $room->id)
                ->where('visibility', 'Tampilkan')
                ->get();
            
            if ($reviews->count() > 0) {
                $total_rating = $reviews->sum('rating');
                $data['average_rating'] = round($total_rating / $reviews->count(), 1);
                $data['total_reviews'] = $reviews->count();
            }
        } catch (\Exception $e) {
            Log::warning('Error calculating review stats: ' . $e->getMessage());
        }

        return $data;
    }

    public function services(Request $request) {
        $serviceCategories = ServiceCategory::all();
        $selectedCategory = $request->input('category', 'Semua');

        if ($selectedCategory === 'Semua') {
            $services = Service::all();
        } else {
            $services = Service::whereHas('serviceCategory', function ($query) use ($selectedCategory) {
                $query->where('name', $selectedCategory);
            })->get();
        }
        
        return view('frontpage.services', [
            'serviceCategories' => $serviceCategories,
            'selectedCategory' => $selectedCategory,
            'services' => $services,
        ]);
    }

    public function services_detail(String $id) {
        $service = Service::findOrFail($id);
        return view('frontpage.services-detail', compact('service'));
    }

    public function contact() {
        return view('frontpage.contact');
    }

    public function about() {
        return view('frontpage.about');
    }

    public function search(Request $request){
        if($request->input('room_id')){
            $room_id = $request->input('room_id');
            $room = Room::where('id', $room_id)->first();

            // Pastikan room ditemukan
            if (!$room) {
                return redirect()->route('frontpage.rooms')->with('error', 'Kamar tidak ditemukan');
            }

            if ($request->filled(['check_in', 'check_out'])) {
                session([
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                ]);
            }

            return redirect()->route('frontpage.rooms.detail', $room->slug);
        } else {
            return redirect()->route('frontpage.index')->with('error', 'Kamar atau tanggal reservasi belum dipilih!');
        }
    }
}