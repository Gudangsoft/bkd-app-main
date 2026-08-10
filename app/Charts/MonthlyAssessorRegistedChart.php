<?php

namespace App\Charts;

use App\Models\Payment;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class MonthlyAssessorRegistedChart
{
    protected $chart, $countData, $sumPaidSuccess, $month, $year;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;

    }

    public function setDate($year)
    {
        $this->year = $year;

        $payments = Payment::where('status', 0)->whereYear('created_at', $this->year)->select(DB::raw('MONTH(created_at) as month'), DB::raw('sum(amount) as count'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

        $payment_paid_success = Payment::where('status', 1)->whereYear('created_at', $this->year)->select(DB::raw('MONTH(created_at) as month'), DB::raw('sum(amount) as paid_success'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

        $monthKey = array_fill(0, 12, 0);
        $month_paid_success = array_fill(0, 12, 0);
        $monthName = array(
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );

        $this->month = $monthName;

        foreach ($payments as $item) {
            $monthKey[$item->month - 1] = $item->count;
        }
        // dd($monthKey);
        foreach ($payment_paid_success as $item_count) {
            $month_paid_success[$item_count->month - 1] = $item_count->paid_success;
        }
        // dd($month_paid_success);
        $this->sumPaidSuccess = $month_paid_success;
        $this->countData = $monthKey;
    }

    public function build($set_year): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $this->setDate($set_year);

        return $this->chart->barChart()
            // ->setTitle('Grafik Asset Pembayaran BKD '.$this->year)
            ->addData('lunas (rupiah)', $this->sumPaidSuccess)
            ->addData('pending (rupiah)', $this->countData)
            ->setColors(['#139f91', '#ff6384'])
            ->setXAxis($this->month)
            ->setMarkers(['#FF5722', '#E040FB'], 7, 10);
    }
}
