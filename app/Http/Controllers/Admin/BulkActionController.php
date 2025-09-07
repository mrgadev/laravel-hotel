<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Room;
use App\Models\User;
use App\Models\Promo;
use App\Models\Saldo;
use App\Models\Message;
use App\Models\Service;
use App\Models\RoomReview;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\NearbyLocation;
use App\Models\RoomFacilities;
use App\Models\AccomdationPlan;
use App\Models\AccomodationPlan;
use App\Models\Feedback;
use App\Models\HotelAward;
use App\Models\HotelFacilities;
use App\Models\HotelFacility;
use App\Models\HotelService;
use App\Models\RoomFacility;
use App\Models\RoomRule;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Gate;

class BulkActionController extends Controller
{
    public function hotelFacilitiesBulkDelete(Request $request) {
        $facilities_ids = $request->input('facilities_ids', []);

        if (empty($facilities_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        HotelFacility::whereIn('id', $facilities_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data fasilitas berhasil dihapus']);
    }

    public function nearbyLocationBulkDelete(Request $request)
    {
        $location_ids = $request->input('location_ids', []);

        if (empty($location_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        NearbyLocation::whereIn('id', $location_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data lokasi berhasil dihapus']);
    }

    public function faqBulkDelete(Request $request){
        $faq_ids = $request->input('faq_ids', []);

        if (empty($faq_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        Faq::whereIn('id', $faq_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data FAQ berhasil dihapus']);
    }

    public function roomBulkDelete(Request $request){
        $room_ids = $request->input('room_ids', []);

        if (empty($room_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        Room::whereIn('id', $room_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data kamar berhasil dihapus']);
    }

    public function roomFacilitiesBulkDelete(Request $request){
        $facilities_ids = $request->input('facilities_ids', []);

        if (empty($facilities_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        RoomFacility::whereIn('id', $facilities_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data fasilitas kamar berhasil dihapus']);
    }

    public function hotelAwardsBulkDelete(Request $request){
        $hotel_awards_ids = $request->input('awards_ids', []);

        if (empty($hotel_awards_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        HotelAward::whereIn('id', $hotel_awards_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data penghargaan hotel berhasil dihapus']);
    }

    public function hotelServicesBulkDelete(Request $request){
        $hotel_services_ids = $request->input('hotel_services_ids', []);

        if (empty($hotel_services_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        HotelService::whereIn('id', $hotel_services_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data layanan hotel berhasil dihapus']);
    }

    public function accomodationPlanBulkDelete(Request $request){
        $plan_ids = $request->input('plan_ids', []);

        if (empty($plan_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        AccomodationPlan::whereIn('id', $plan_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data paket kamar berhasil dihapus']);
    }

    public function serviceBulkDelete(Request $request){
        $service_ids = $request->input('service_ids', []);

        if (empty($service_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        Service::whereIn('id', $service_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data layanan berhasil dihapus']);
    }

    public function serviceCategoryBulkDelete(Request $request){
        $service_category_ids = $request->input('service_category_ids', []);

        if (empty($service_category_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        ServiceCategory::whereIn('id', $service_category_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data kategori layanan berhasil dihapus']);
    }

    public function promoBulkDelete(Request $request){
        $promo_ids = $request->input('promo_ids', []);

        if (empty($promo_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        Promo::whereIn('id', $promo_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data promo berhasil dihapus']);
    }

    public function roomRuleBulkDelete(Request $request){
        $room_rule_ids = $request->input('room_rule_ids', []);

        if (empty($room_rule_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        RoomRule::whereIn('id', $room_rule_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function pesanBulkDelete(Request $request){
        $message_ids = $request->input('message_ids', []);

        if (empty($message_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }

        Feedback::whereIn('id', $message_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Data Pesan berhasil dihapus']);
    }

    public function updateRole(Request $request)
    {
        $user_ids = $request->input('user_ids', []);
        $role_id = $request->input('role_id');

        if (empty($user_ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        if (empty($role_id)) {
            return redirect()->back()->with('error', 'tidak ada role yang dipilih');
        }

        foreach($user_ids as $user_id) {
            $user = User::find($user_id);
            if($user) {
                $user->syncRoles([$role_id]);
            }
        }

        return redirect()->route('dashboard.users-management.index')->with('success', 'Data pengguna berhasil diubah');
    }

    public function updateStatus(Request $request){
        $transaction_ids = $request->input('transaction_ids', []);
        $payment_status = $request->input('payment_status');

        if (empty($transaction_ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        if (empty($payment_status)) {
            return redirect()->back()->with('error', 'tidak ada status yang dipilih');
        }

        foreach($transaction_ids as $transaction_id) {
            $transaction = Transaction::find($transaction_id); // Perbaikan disini
            if($transaction->payment_status == 'PENDING') { // Perbaikan disini
                $transaction->update(['payment_status' => $payment_status]);
            }
        }

        return redirect()->route('dashboard.transaction.index')->with('success', 'Data pengguna berhasil diubah');
    }

    public function updateStatusCheckin(Request $request)
    {
        $transaction_ids = $request->input('transaction_ids', []);
        $checkin_status = $request->input('checkin_status');

        if (empty($transaction_ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        if (empty($checkin_status)) {
            return redirect()->back()->with('error', 'Tidak ada status yang dipilih');
        }

        foreach ($transaction_ids as $transaction_id) {
            // Ambil data transaksi
            $transaction = Transaction::find($transaction_id);

            if (!$transaction) {
                continue;
            }

            // Cek status checkin
            if ($transaction->checkin_status == 'Sudah' || $transaction->checkin_status == 'Belum') {
                if($checkin_status == 'Dibatalkan'){
                    // Ambil saldo terakhir user
                    $lastBalance = Saldo::where('user_id', $transaction->user_id)
                                    ->latest()
                                    ->first();

                    // Buat catatan saldo baru
                    Saldo::create([
                        'user_id' => $transaction->user_id,
                        'credit' => $transaction->total_price,
                        'debit' => 0,
                        'amount' => $lastBalance ? $lastBalance->amount + $transaction->total_price : $transaction->total_price,
                        'description' => ''
                    ]);
                }

                $transaction->update(['checkin_status' => $checkin_status]);
            }
        }

        return redirect()->route('dashboard.transaction.index')->with('success', 'Data pengguna berhasil diubah');
    }

    public function changeReviewVisibility(Request $request){
        Gate::authorize('changeVisibility', User::class);
        $review_ids = $request->input('review_ids', []);
        $visibility = $request->input('visibility');
        // dd($request->all());
        if (empty($review_ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        // if (empty($visibility)) {
        //     return redirect()->back()->with('error', 'Tidak ada status yang dipilih');
        // }

        foreach($review_ids as $review_id) {
            $review = RoomReview::find($review_id); // Perbaikan disini
            if($review) { // Perbaikan disini
                $review->update(['visibility' => $visibility]);
            }
        }

        return redirect()->route('dashboard.room-review.index')->with('success', 'Visibilitas berhasil diubah');
    }

}
