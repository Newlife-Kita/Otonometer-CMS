<?php

namespace App\Http\Controllers;

use App\Export\LogDataExport;
use App\Export\LogWilayahExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AppActivityController extends Controller
{

    public function indexForRegion()
    {
        return view("app_activity.region.index");
    }

    public function getRegionLogAjax()
    {
        $results = $this->queryLogWilayah();

        return response()->json(['valid' => true, 'items' => $results, 'message' => 'Success getting log data.']);
    }

    public function downloadLogWilayahExcel()
    {
        $logData = collect($this->queryLogWilayah());

        return Excel::download(new LogWilayahExport($logData), "Log_Wilayah.xlsx");
    }

    private function queryLogWilayah()
    {
        // Initial query
        $results = DB::select('SELECT
        nama_wilayah,
        SUM(total_view_jelajah) AS total_view_jelajah_sum,
        SUM(total_download_jelajah) AS total_download_jelajah_sum,
        SUM(total_save_jelajah) AS total_save_jelajah_sum,
        SUM(total_share_jelajah) AS total_share_jelajah_sum,
        SUM(total_view_utak_atik) AS total_view_utak_atik_sum,
        SUM(total_download_utak_atik) AS total_download_utak_atik_sum,
        SUM(total_save_utak_atik) AS total_save_utak_atik_sum,
        SUM(total_share_utak_atik) AS total_share_utak_atik_sum,
        SUM(total_view_berkaca) AS total_view_berkaca_sum,
        SUM(total_download_berkaca) AS total_download_berkaca_sum,
        SUM(total_save_berkaca) AS total_save_berkaca_sum,
        SUM(total_share_berkaca) AS total_share_berkaca_sum
    FROM (
        SELECT
            mw.nama AS nama_wilayah,
            SUM(CASE WHEN ma.activity = "view" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_view_jelajah,
            SUM(CASE WHEN ma.activity = "download" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_download_jelajah,
            SUM(CASE WHEN ma.activity = "save" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_save_jelajah,
            SUM(CASE WHEN ma.activity = "share" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_share_jelajah,
        	SUM(CASE WHEN ma.activity = "view" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_view_utak_atik,
            SUM(CASE WHEN ma.activity = "download" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_download_utak_atik,
            SUM(CASE WHEN ma.activity = "save" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_save_utak_atik,
            SUM(CASE WHEN ma.activity = "share" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_share_utak_atik,
         	SUM(CASE WHEN ma.activity = "view" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_view_berkaca,
            SUM(CASE WHEN ma.activity = "download" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_download_berkaca,
            SUM(CASE WHEN ma.activity = "save" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_save_berkaca,
            SUM(CASE WHEN ma.activity = "share" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_share_berkaca
        FROM
            member_aktifitas ma
        LEFT JOIN
            halaman h ON ma.id_halaman = h.id
        LEFT JOIN
            master_wilayah mw ON h.id_wilayah_1 = mw.id
        WHERE
            mw.has_data = 1
        GROUP BY
            mw.nama

        UNION ALL

        SELECT
            mw.nama AS nama_wilayah,
            SUM(CASE WHEN ma.activity = "view" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_view_jelajah,
            SUM(CASE WHEN ma.activity = "download" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_download_jelajah,
            SUM(CASE WHEN ma.activity = "save" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_save_jelajah,
            SUM(CASE WHEN ma.activity = "share" AND h.halaman_tipe = "JELAJAH" THEN 1 ELSE 0 END) AS total_share_jelajah,
        	SUM(CASE WHEN ma.activity = "view" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_view_utak_atik,
            SUM(CASE WHEN ma.activity = "download" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_download_utak_atik,
            SUM(CASE WHEN ma.activity = "save" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_save_utak_atik,
            SUM(CASE WHEN ma.activity = "share" AND h.halaman_tipe = "UTAK-ATIK" THEN 1 ELSE 0 END) AS total_share_utak_atik,
         	SUM(CASE WHEN ma.activity = "view" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_view_berkaca,
            SUM(CASE WHEN ma.activity = "download" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_download_berkaca,
            SUM(CASE WHEN ma.activity = "save" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_save_berkaca,
            SUM(CASE WHEN ma.activity = "share" AND h.halaman_tipe = "BERKACA" THEN 1 ELSE 0 END) AS total_share_berkaca
        FROM
            member_aktifitas ma
        LEFT JOIN
            halaman h ON ma.id_halaman = h.id
        LEFT JOIN
            master_wilayah mw ON h.id_wilayah_2 = mw.id
        WHERE
            mw.has_data = 1
        GROUP BY
            mw.nama

        UNION ALL

        SELECT
            nama AS nama_wilayah,
            0 AS total_view_jelajah_sum,
 			0 AS total_download_jelajah_sum,
        	0 AS total_save_jelajah_sum,
       		0 AS total_share_jelajah_sum,
        	0 AS total_view_utak_atik_sum,
        	0 AS total_download_utak_atik_sum,
        	0 AS total_save_utak_atik_sum,
        	0 AS total_share_utak_atik_sum,
        	0 AS total_view_berkaca_sum,
        	0 AS total_download_berkaca_sum,
        	0 AS total_save_berkaca_sum,
        	0 AS total_share_berkaca_sum
        FROM
            master_wilayah
        WHERE
            has_data = 1
    ) AS combined_data
    GROUP BY
        nama_wilayah
    ORDER BY (total_view_jelajah_sum + total_download_jelajah_sum + total_save_jelajah_sum + total_share_jelajah_sum + total_view_utak_atik_sum + total_download_utak_atik_sum + total_save_utak_atik_sum + total_share_utak_atik_sum + total_view_berkaca_sum + total_download_berkaca_sum + total_save_berkaca_sum + total_share_berkaca_sum) DESC;');

        return $results;
    }

    public function indexForLog()
    {
        return view("app_activity.log.index");
    }

    public function getLogDataAjax(Request $request)
    {
        $param = $request->query("params");
        $order = $request->query("order");

        if (is_null($param)) {
            $logData = DB::table("member_aktifitas")
                ->join("halaman as h", "member_aktifitas.id_halaman", "=", "h.id")
                ->selectRaw("DATE(created_at) AS tanggal_log,
                SUM(CASE WHEN member_aktifitas.activity = 'view' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_view_jelajah,
                SUM(CASE WHEN member_aktifitas.activity = 'download' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_download_jelajah,
                SUM(CASE WHEN member_aktifitas.activity = 'share' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_share_jelajah,
                SUM(CASE WHEN member_aktifitas.activity = 'save' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_save_jelajah,
                SUM(CASE WHEN member_aktifitas.activity = 'view' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_view_utak_atik,
                SUM(CASE WHEN member_aktifitas.activity = 'download' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_download_utak_atik,
                SUM(CASE WHEN member_aktifitas.activity = 'share' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_share_utak_atik,
                SUM(CASE WHEN member_aktifitas.activity = 'save' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_save_utak_atik,
                SUM(CASE WHEN member_aktifitas.activity = 'view' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_view_berkaca,
                SUM(CASE WHEN member_aktifitas.activity = 'download' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_download_berkaca,
                SUM(CASE WHEN member_aktifitas.activity = 'share' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_share_berkaca,
                SUM(CASE WHEN member_aktifitas.activity = 'save' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_save_berkaca")
                ->groupByRaw('DATE(created_at)')
                ->when(is_null($order) || "ASC" != strtoupper($order), function ($query) {
                    $query->orderByRaw('DATE(created_at) DESC');
                })->when("ASC" != strtoupper($order), function ($query) {
                    $query->orderByRaw('DATE(created_at) ASC');
                })
                ->get();
        }

        return response()->json(['valid' => true, 'items' => $logData, 'message' => 'Success getting log data.']);
    }

    public function downloadLogDataExcel()
    {
        $logData = DB::table("member_aktifitas")
            ->join("halaman as h", "member_aktifitas.id_halaman", "=", "h.id")
            ->selectRaw("DATE(created_at) AS tanggal_log,
        SUM(CASE WHEN member_aktifitas.activity = 'view' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_view_jelajah,
        SUM(CASE WHEN member_aktifitas.activity = 'download' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_download_jelajah,
        SUM(CASE WHEN member_aktifitas.activity = 'share' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_share_jelajah,
        SUM(CASE WHEN member_aktifitas.activity = 'save' AND h.halaman_tipe = 'JELAJAH' THEN 1 ELSE 0 END) AS total_save_jelajah,
        SUM(CASE WHEN member_aktifitas.activity = 'view' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_view_utak_atik,
        SUM(CASE WHEN member_aktifitas.activity = 'download' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_download_utak_atik,
        SUM(CASE WHEN member_aktifitas.activity = 'share' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_share_utak_atik,
        SUM(CASE WHEN member_aktifitas.activity = 'save' AND h.halaman_tipe = 'UTAK-ATIK' THEN 1 ELSE 0 END) AS total_save_utak_atik,
        SUM(CASE WHEN member_aktifitas.activity = 'view' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_view_berkaca,
        SUM(CASE WHEN member_aktifitas.activity = 'download' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_download_berkaca,
        SUM(CASE WHEN member_aktifitas.activity = 'share' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_share_berkaca,
        SUM(CASE WHEN member_aktifitas.activity = 'save' AND h.halaman_tipe = 'BERKACA' THEN 1 ELSE 0 END) AS total_save_berkaca")
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) DESC')
            ->get();

        return Excel::download(new LogDataExport($logData), "Log_Data_Harian.xlsx");
    }
}
