<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua tahun yang ada di kolom created_at
        $years = Transaction::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Mengambil tahun terakhir untuk default chart
        $latestYear = $years->first();

        // Mengambil tahun yang dipilih atau default ke tahun terakhir
        $selectedYear = $request->input('year', $latestYear);

        // Data untuk chart
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $total = Transaction::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $month)
                ->sum('total_price');

            $monthlyData[] = $total;
        }

        // Data untuk tabel
        $transactions = Transaction::query();

        // Filter berdasarkan tahun dan bulan jika ada
        if ($request->filled('year')) {
            $transactions->whereYear('created_at', $request->year);
        }

        if ($request->filled('month')) {
            $monthNumber = date('m', strtotime($request->month));
            $transactions->whereMonth('created_at', $monthNumber);
        }

        $transactions = $transactions->orderBy('created_at', 'desc')->get();
        $totalAmount = $transactions->sum('total_price');

        return view('dashboard.admin.report.index', compact(
            'years',
            'transactions',
            'totalAmount',
            'selectedYear',
            'monthlyData'
        ));
    }

    public function exportTransactions(Request $request)
    {
        // Ambil tahun dari request
        $year = $request->input('years');

        // Pemetaan nama bulan (huruf kecil) ke angka
        $monthsMapping = [
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'december' => 12,
        ];

        // Ambil bulan dari request dan konversi ke angka
        $monthName = $request->input('months');
        $month = $monthsMapping[$monthName]; // Mengambil angka bulan dari pemetaan

        // Ambil data transaksi berdasarkan tahun dan bulan
        $transactions = Transaction::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // Hitung total pengeluaran
        $totalExpenses = $transactions->sum('total_price');

        // Menggunakan streamDownload untuk mengunduh file
        return SimpleExcelWriter::streamDownload("transactions_export_{$year}_{$month}.xlsx")
            ->addHeader([
                'ID',
                'Nama',
                'Nama Kamar',
                'Nomor Innvoice',
                'Status Pembayaran',
                'Metode pembayaran',
                'Total Biaya',
                'Tanggal'
            ]) // Menambahkan header
            ->addRows($transactions->map(function ($transaction) {
                return [
                    $transaction->id,
                    $transaction->name,
                    $transaction->room->name,
                    $transaction->invoice,
                    $transaction->payment_status,
                    $transaction->payment_method,
                    $transaction->total_price,
                    $transaction->created_at->format('Y-m-d'), // Format tanggal
                ];
            }))
            ->addRow(['Total', $totalExpenses]) // Menambahkan total
            ->close();
    }
}
