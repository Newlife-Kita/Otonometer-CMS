<?php

namespace App\Http\Controllers;

use App\DataTables\BidangnilaiDataTable;
use App\DataTables\BidangnilaikeuanganDataTable;
use App\DataTables\BidangnilaistatistikDataTable;
use App\Exceptions\ListException;
use App\Export\BidangnilaiExport;
use App\Http\Requests;
use App\Http\Requests\CreateBidangnilaiRequest;
use App\Http\Requests\UpdateBidangnilaiRequest;
use App\Repositories\BidangnilaiRepository;
use App\Repositories\BidangRepository;
use App\Repositories\SettingRepository;
use App\Repositories\WilayahRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Import\BidangekonomiImport;
use App\Import\BidangkeuanganImport;
use App\Import\BidangnilaiImport;
use App\Import\BidangstatistikImport;
use App\Models\Bahasa;
use App\Models\Bidang;
use App\Models\Bidangcatatan;
use App\Models\Bidangekonomi;
use App\Models\Bidangkeuangan;
use App\Models\Bidangstatistik;
use App\Models\LogCms;
use App\Repositories\NomenklaturtahunRepository;
use Carbon\Carbon;
use Exception;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash as FlashFlash;
use PhpParser\Node\Stmt\TryCatch;
use ReflectionClass;
use Spatie\DbDumper\Databases\MySql;

class BidangnilaiController extends AppBaseController
{
    /** @var  BidangnilaiRepository */
    private $wilayahRepository;
    private $bidangRepository;
    private $tahunRepository;
    private $id_keuangan = 1;
    private $id_ekonomi = 2;
    private $id_statistik = 3;

    public function __construct(BidangRepository $bidangRepo, WilayahRepository $wilayahrepo, NomenklaturtahunRepository $tahunRepo)
    {
        $this->middleware('auth');
        $this->middleware('can:bidangnilai-edit', ['only' => ['edit']]);
        $this->middleware('can:bidangnilai-store', ['only' => ['store']]);
        $this->middleware('can:bidangnilai-show', ['only' => ['show']]);
        $this->middleware('can:bidangnilai-update', ['only' => ['update']]);
        $this->middleware('can:bidangnilai-delete', ['only' => ['delete']]);
        $this->middleware('can:bidangnilai-create', ['only' => ['create']]);
        $this->wilayahRepository = $wilayahrepo;
        $this->bidangRepository = $bidangRepo;
        $this->tahunRepository = $tahunRepo;
    }

    public function ajax_sektor(Request $request)
    {
        $tahun = $request->tahun;
        $bidang = $request->bidang;

        if (empty($tahun)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Tahun data belum dipilih']);
        }
        if (empty($bidang)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Sektor/Bidang belum dipilih']);
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        } else {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Data belum tersedia']);
        }

        $nilai = DB::table($tablename)
            ->selectRaw('
            distinct(' . $tablename . '.id_wilayah),
            ' . $tablename . '.nilai,
            master_wilayah.nama,
            nau.nilai as updated_nilai,'
                . $tablename . '.history_updated
        ')
            ->leftJoin('master_wilayah', 'master_wilayah.id', '=', $tablename . '.id_wilayah')
            ->leftJoin('nomenklatur_amount_update as nau', function ($join) use ($bidang, $tablename, $tahun) {
                $join->on('nau.id_wilayah', '=', $tablename . '.id_wilayah')
                    ->where('nau.id_bidang', '=', $bidang)
                    ->where('nau.tahun', '=', $tahun);
            })
            ->where($tablename . '.id_bidang', $bidang)
            ->whereNull($tablename . '.deleted_at')
            ->orderBy($tablename . '.nilai', 'desc')
            ->get();


        $nilai->map(function ($item) {
            $item->nama = @json_decode($item->nama, true)['id'];
            return $item;
        });


        return response()->json(['valid' => true, 'items' => $nilai, 'message' => '']);
    }

    public function ajax_update(Request $request)
    {
        $tahun = $request->tahun;
        $bidang = $request->bidang;
        $wilayah = $request->wilayah;
        $nilai = $request->nilai;

        // dd($request->all());

        if (empty($tahun)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Tahun data belum dipilih']);
        }
        if (empty($bidang)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Sektor/Bidang belum dipilih']);
        }

        if (empty($nilai) && $nilai != 0) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Nilai sektor belum ditentukan']);
        }

        if (!is_numeric($nilai)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Nilai sektor harus berupa angka']);
        }


        $record = DB::table("nomenklatur_amount_update")->where('.id_bidang', $bidang)->where('id_wilayah', $wilayah)->where('tahun', $tahun)->first();

        if ($record) {
            DB::table("nomenklatur_amount_update")
                ->where('id_bidang', $bidang)
                ->where('id_wilayah', $wilayah)
                ->where('tahun', $tahun)
                ->update(['nilai' => $nilai]);
        } else {
            DB::table("nomenklatur_amount_update")->insert([
                "id_bidang" => $bidang,
                "id_wilayah" => $wilayah,
                "tahun" => $tahun,
                "nilai" => $nilai
            ]);
        }

        return response()->json(['valid' => true, 'items' => $nilai, 'message' => 'Data berhasil diupdate']);
    }

    public function ajax_update_delete(Request $request)
    {
        $tahun = $request->tahun;
        $bidang = $request->bidang;
        $wilayah = $request->wilayah;

        if (empty($tahun)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Tahun data belum dipilih']);
        }
        if (empty($bidang)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Sektor/Bidang belum dipilih']);
        }

        DB::table("nomenklatur_amount_update")
            ->where('id_bidang', $bidang)
            ->where('id_wilayah', $wilayah)
            ->where('tahun', $tahun)
            ->delete();

        return response()->json(['valid' => true, 'items' => "$bidang $wilayah $tahun", 'message' => 'Data berhasil dihapus']);
    }

    public function ajax_update_delete_all(Request $request)
    {
        $tahun = $request->tahun;
        $bidang = $request->bidang;

        if (empty($tahun)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Tahun data belum dipilih']);
        }
        if (empty($bidang)) {
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Sektor/Bidang belum dipilih']);
        }

        DB::table("nomenklatur_amount_update")
            ->where('id_bidang', $bidang)
            ->where('tahun', $tahun)
            ->delete();

        return response()->json(['valid' => true, 'items' => "", 'message' => 'Data berhasil dihapus']);
    }

    public function ajax_update_publish(Request $request)
    {
        $tahun = $request->tahun;
        $bidang = $request->bidang;
        $wilayah = $request->wilayah; // array id wilayah dari nilai yang di checklist

        $tablename = 'nomenklatur_amount_' . @$tahun; // tabel target perubahan

        $updatedData = DB::table("nomenklatur_amount_update")
            ->where('id_bidang', $bidang)
            ->where('tahun', $tahun)->whereIn('id_wilayah', $wilayah)->get(); // query data perubahan yang sesuai


        // melalukan perubahan pada tabel asli
        foreach ($updatedData as $data) {
            DB::table($tablename)->where('id_bidang', $data->id_bidang)
                ->where('id_wilayah', $data->id_wilayah)
                ->whereNull('deleted_at')
                ->update(['nilai' => $data->nilai]);
        }


        // mengosongkan tabel update
        DB::table("nomenklatur_amount_update")
            ->where('id_bidang', $bidang)
            ->where('tahun', $tahun)->delete();

        return response()->json(['valid' => true, 'items' => "", 'message' => 'Data berhasil diupdate']);
    }

    ## KEUANGAN
    public function index_keuangan()
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $bahasa = Bahasa::where('status', 'tampil')->select('label', 'code')->get();
        $years = $this->tahunRepository->orderBY('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $province = $this->wilayahRepository->getArrayProvince();
        $province = ["" => "Semua"] + $province;
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }

        $data = [
            'bahasa' => $bahasa,
            'sektor' => $this->bidangRepository->get_bidang_options($this->id_keuangan),
            'tahun' => $tahun,
            'id_sektor' => $this->id_keuangan,
            'province' => $province
        ];

        return view('bidangnilais.keuangan.index')->with($data);
    }

    public function form_keuangan(BidangnilaikeuanganDataTable $dataTable)
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $years = $this->tahunRepository->orderBy('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [0 => 'Pilih Tahun Data'];
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }
        $sektor = $this->bidangRepository->get_bidang_options($this->id_keuangan);
        $wilayah = $this->wilayahRepository->getArrayOptionsWilayah();

        return $dataTable->renders('bidangnilais.keuangan.upload', ['tahun' => $tahun, 'wilayah' => $wilayah, 'sektor' => $sektor], [], []);
    }

    public function template_keuangan(Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(1);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[] = $data0;
        $data[] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $data[] = [$lokasi['id'], $lokasi['code'], $lokasi['name']];
        }
        return Excel::download(new BidangnilaiExport($data), 'Template_Keuangan.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function download_keuangan(Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $tahun = $request->tahun;
        if (empty($tahun)) {
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-keuangan.index'));
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        } else {
            Flash::error('Data belum tersedia');
            return redirect(route('data-keuangan.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(1);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[] = $data0;
        $data[] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = @DB::table($tablename)->select('nilai')
                    ->where('id_wilayah', $lokasi['id'])
                    ->where('id_bidang', $bidang['id'])
                    ->whereNull('deleted_at')
                    ->first()->nilai;
                $details[] = !empty($nilai) ? $nilai : 'N/A';
            }
            $data[] = $details;
        }
        return Excel::download(new BidangnilaiExport($data), 'Data_Keuangan_' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function download_keuangan_province(Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }
        $tahun = $request->tahun;
        $provinceId = $request->province_id;

        if (empty($tahun)) {
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-keuangan.index'));
        }

        if (empty($provinceId)) {
            Flash::error('Provinsi belum dipilih');
            return redirect(route('data-keuangan.index'));
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        } else {
            Flash::error('Data belum tersedia');
            return redirect(route('data-keuangan.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableProvince($provinceId);
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(1);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[] = $data0;
        $data[] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = @DB::table($tablename)->select('nilai')
                    ->where('id_wilayah', $lokasi['id'])
                    ->where('id_bidang', $bidang['id'])
                    ->whereNull('deleted_at')
                    ->first()->nilai;
                $details[] = !empty($nilai) ? $nilai : 'N/A';
            }
            $data[] = $details;
        }
        return Excel::download(new BidangnilaiExport($data), 'Data_Keuangan_' . $data_lokasi[0]['name'] . '_' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }


    public function upload_keuangan(Request $request, BidangkeuanganImport $import)
    {
        // mengeck apakah ada file yang sudah di upload, mencegah race condition
        if (DB::table('upload_tracker')->where('type', 'keuangan')->exists()) {
            return redirect(route('data-keuangan.uploadform'))->withErrors(['Telah terdapat file yang diupload.']);
        }


        $akses = Auth::user();

        // membuat log
        $uploadRecord = LogCms::create([
            'users_id' => $akses->id,
            'upload_date' => Carbon::now('Asia/Jakarta'),
            'upload_type' => 'Role Admin App: Upload Data Keuangan'
        ]);


        try {
            if ($request->hasFile('file')) {

                // insert ke upload tracker untuk menandai bahwa ada file yang diupload
                DB::table('upload_tracker')->insert([
                    'type' => 'keuangan',
                    'upload_id' => $uploadRecord->id
                ]);
                $arrayExcel = [];
                $file = $request->file('file');
                $import->setUploaderId($akses->id);
                $import->setUploadId($uploadRecord->id);
                // mengeset array excel untuk mendapatkan data sebelum proses insert database
                $import->setArrayExcel($arrayExcel);
                $uploadRecord->file_name = $request->file('file')->getClientOriginalName();
                $uploadRecord->save();

                // proses import
                Excel::import($import, $file);

                // menghitung record yang terupload
                $uploaded_count = Bidangkeuangan::where('upload_id', $uploadRecord->id)->count();

                // mengambil data dari database
                $arrayDb = Bidangkeuangan::select('id_bidang', 'id_wilayah', DB::raw('ROUND(nilai, 3) AS nilai'))->get()->toArray();

                Log::info('Data dari database:', $arrayDb);
                Log::info('Data dari excel:', $arrayExcel);

                // mengencode data
                $md5Excel = md5(json_encode($arrayExcel));
                $md5DB = md5(json_encode($arrayDb));

                $checksum = [
                    'excel' => $md5Excel,
                    'db' => $md5DB,
                    'status' => ($md5Excel == $md5DB)
                ];

                Session::put('checksum-keuangan', $checksum);

                // memeriksa checksum
                if ($md5Excel != $md5DB) {
                    $listCompared = $this->compareData($arrayExcel, $arrayDb);
                    $uploadRecord->status = 'not valid';
                    $uploadRecord->save();
                    return redirect(route('data-keuangan.uploadform'))->withErrors(array_merge(['Data Excel dan data yang terupload tidak sama.'], $listCompared));
                }

                // jika tidak ada data yang terupload hapus record upload tracker untuk melepas lock
                if ($uploaded_count == 0) {
                    DB::table('upload_tracker')->where('type', 'keuangan')->delete();
                }

                $uploadRecord->row_uploaded = $uploaded_count;

                $uploadRecord->save();


                // redirect
                return redirect(route('data-keuangan.uploadform'))->with('filename')->withSuccess("File successfully uploaded.  Uploaded {$uploaded_count} rows");
            } else {
                return redirect(route('data-keuangan.uploadform'))->withErrors(['No File Uploaded']);
            }
        } catch (Exception $e) {
            // handler error
            DB::table('upload_tracker')->where('type', 'keuangan')->delete();
            $uploadRecord->status = 'not valid';
            $uploadRecord->save();

            if ($e instanceof ListException) {
                return redirect(route('data-keuangan.uploadform'))->withErrors($e->getList());
            }
            return redirect(route('data-keuangan.uploadform'))->withErrors(['Error ketika upload.']);
        }
    }

    public function publish_keuangan(Request $request)
    {
        // mendapatkan tahun yang di pilih
        $tahun = $request->tahun_data;
        $valid = false;

        // cek apa table sudah pernah ada? jika belum buat tabel dengan schema dibawah
        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
            MySql::create()
                ->setDbName(env('DB_DATABASE'))
                ->setUserName(env('DB_USERNAME'))
                ->setPassword(env('DB_PASSWORD'))
                ->setHost(env('DB_HOST'))
                ->includeTables($tablename)
                ->dumpToFile(storage_path('backup_' . $tablename . '_' . date('Y_m_d_H_i_s') . '.sql'));

            Schema::drop($tablename);

            Schema::create($tablename, function ($table) use ($tahun) {
                $table->increments('id');
                $table->integer('id_bidang')->nullable();
                $table->integer('id_nomenklatur')->nullable();
                $table->integer('id_wilayah')->nullable();
                $table->year('tahun')->default($tahun);
                $table->decimal('nilai', 12, 3)->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->longText('history_updated')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::create($tablename, function ($table) use ($tahun) {
                $table->increments('id');
                $table->integer('id_bidang')->nullable();
                $table->integer('id_nomenklatur')->nullable();
                $table->integer('id_wilayah')->nullable();
                $table->year('tahun')->default($tahun);
                $table->decimal('nilai', 12, 3)->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->longText('history_updated')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // mmendapatkan upload id, untuk mengetahui nilai yang di publish berasal dari upload mana
        $uploadId = @Bidangkeuangan::first()->upload_id ?? 0;

        // parameter force berguna untuk memaksa publish saat ada nilai pada tabel utama yang diinput secara manual
        if (!$request->has('force')) {
            // mendapatkan nilai yang berbeda dari nilai pada tabel asli yang telah diupdate secara manual
            $updated = Bidangkeuangan::join("$tablename as na", function ($join) {
                $join->on('nomenklatur_amount_keuangan.id_bidang', '=', 'na.id_bidang')
                    ->on('nomenklatur_amount_keuangan.id_wilayah', '=', 'na.id_wilayah');
            })
                ->whereNull('na.deleted_at')
                ->whereNotNUll('na.history_updated')
                ->select(DB::raw('nomenklatur_amount_keuangan.id_bidang, nomenklatur_amount_keuangan.id_wilayah'))->get();
        }

        // memberikan response jika pada tabel tujuan sudah ada nilai yang di edit manual, akan muncul prompt untuk overide nilai
        if (isset($updated) && count($updated) > 0) {
            return response()->json(['valid' => false, 'message' => 'Terdapat data yang sudah diupdate oleh admin wilayah'], 200);
        }

        //menghapus potensi nilai duplikat pada tabel utama (legacy code, model saat ini hampir tidak mungkin menimbulkan duplikat)
        DB::statement("
    DELETE t1 FROM $tablename t1
    JOIN (
        SELECT id_bidang, id_wilayah, created_at, id,
               ROW_NUMBER() OVER (PARTITION BY id_bidang, id_wilayah ORDER BY created_at DESC, id DESC) as rn
        FROM $tablename
    ) t2
    ON t1.id = t2.id
    WHERE t2.rn > 1
");

        // mendapatkan data hasil upload
        $select = Bidangkeuangan::selectRaw("nomenklatur_amount_keuangan.id_bidang, nomenklatur_amount_keuangan.id_wilayah, nomenklatur_amount_keuangan.nilai, nomenklatur_amount_keuangan.created_at, $tahun as tahun")
            ->distinct()
            ->get();

        //menghitung jumlah data yang berbedaa
        $changed = (int) Bidangkeuangan::join("$tablename as na", function ($join) {
            $join->on('nomenklatur_amount_keuangan.id_bidang', '=', 'na.id_bidang')
                ->on('nomenklatur_amount_keuangan.id_wilayah', '=', 'na.id_wilayah');
        })
            ->whereNull('na.deleted_at')
            ->select(DB::raw('
            COALESCE(SUM(CASE WHEN na.nilai != nomenklatur_amount_keuangan.nilai OR nomenklatur_amount_keuangan.nilai IS NULL THEN 1 ELSE 0 END), 0) AS changed
        '))
            ->first()['changed'];

        // menghitung jumlah data baru
        $inserted = (int) Bidangkeuangan::leftJoin("$tablename as na", function ($join) {
            $join->on('nomenklatur_amount_keuangan.id_bidang', '=', 'na.id_bidang')
                ->on('nomenklatur_amount_keuangan.id_wilayah', '=', 'na.id_wilayah');
        })
            ->whereNull('na.deleted_at')
            ->whereNotNull("nomenklatur_amount_keuangan.nilai")
            ->select(DB::raw('
                    COALESCE(SUM(CASE WHEN na.id_bidang  IS NULL THEN 1 ELSE 0 END), 0) AS inserted
                '))
            ->first()['inserted'];


        if ($select->isNotEmpty()) {
            $jakartaTime = Carbon::now('Asia/Jakarta')->toDateTimeString();

            // Insert data yang akan di hapus pada tabel backup
            $arrayTransfer = DB::table('nomenklatur_amount_keuangan')
                ->join("$tablename as na", function ($join) {
                    $join->on('nomenklatur_amount_keuangan.id_bidang', '=', 'na.id_bidang')
                        ->on('nomenklatur_amount_keuangan.id_wilayah', '=', 'na.id_wilayah');
                })
                ->select(
                    'nomenklatur_amount_keuangan.id_bidang',
                    'nomenklatur_amount_keuangan.id_wilayah',
                    'nomenklatur_amount_keuangan.tahun',
                    'nomenklatur_amount_keuangan.nilai',
                    'nomenklatur_amount_keuangan.created_at',
                    DB::raw("'{$jakartaTime}' as deleted_at") // Use the Jakarta time as deleted_at
                )
                ->get();

            foreach (array_chunk($arrayTransfer->toArray(), 500) as $chunk) {
                foreach ($chunk as &$item) {
                    $item = (array) $item;
                }
                DB::table('backup_old_data')->insert($chunk);
            }

            // Hapus data pada tabel temporari
            DB::table("$tablename")
                ->join('nomenklatur_amount_keuangan', 'nomenklatur_amount_keuangan.id_bidang', '=', "$tablename.id_bidang")
                ->whereColumn('nomenklatur_amount_keuangan.id_wilayah', '=', "$tablename.id_wilayah")
                ->delete();
        }

        // insert data baru
        foreach (array_chunk($select->toArray(), 500) as $chunk) {
            foreach ($chunk as &$item) {
                // Format the created_at field to a specific timezone and format
                $item['created_at'] = Carbon::parse($item['created_at'])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
            }
            DB::table("$tablename")->insert($chunk);
        }



        $akses = Auth::user();

        $submitted = Bidangkeuangan::count();

        $uploadRecord = LogCms::where('id', $uploadId)->first();

        LogCms::create(["users_id" => $akses->id, "row_submitted" => $submitted, "row_uploaded" => $changed + $inserted, 'status' => 'submitted', 'submit_date' => Carbon::now(),  'upload_type' => 'Role Admin App: Publish Data Keuangan tahun ' . $tahun, 'file_name' => @$uploadRecord->file_name]);

        LogCms::where('id', $uploadId)->update(['submit_date' => Carbon::now(), 'status' => 'submitted']);


        DB::table('upload_tracker')->where('type', 'keuangan')->delete();
        // truncate Temp
        Bidangkeuangan::truncate();


        // clear cache BE
        $ch = curl_init(env('API_URL') . "clear-cache");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        $valid = true;
        return response()->json(['valid' => $valid, 'message' => 'Data Keuangan Tahun ' . $tahun . ' berhasil di publish', 'data' => [
            'data_terpublish' => $submitted,
            'data_terganti' => $changed,
            'data_terinput' => $inserted
        ]], 200);
    }

    public function form_delete_keuangan()
    {
        $years = $this->tahunRepository->orderBY('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $tahun[0] = '  Pilih tahun Data  ';
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }

        $sektor = 'keuangan';
        return view('bidangnilais.show')->with('sektor', $sektor)->with('tahun', $tahun);
    }

    public function get_sektor_keuangan(Request $request)
    {
        $tahun = $request->tahun;
        $keuangan = $this->bidangRepository->get_sektor_table(1);
        $tablename = 'nomenklatur_amount_' . @$tahun;
        $nilai = null;
        if (Schema::hasTable($tablename)) {
            $nilai = DB::table($tablename)->distinct('id_bidang')->pluck('id_bidang')->toArray();
        }
        return response()->json(['sektor' => $keuangan, 'nilai' => $nilai], 200);
    }

    public function delete_keuangan(Request $request)
    {
        $id = @$request->sektor;
        $tahun = @$request->tahun;

        $bidang = $this->bidangRepository->findWithoutFail($id);
        if (empty($bidang)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang Keuangan not found'], 200);
        }

        $parent = $this->bidangRepository->findWithoutFail($bidang->id_parent);
        $firstParent = $this->bidangRepository->get_first_sektor($id);

        if (empty($parent)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang Keuangan not found'], 200);
        }

        if (strtolower(@$firstParent['nama']) !== strtolower('keuangan')) {
            return response()->json(['valid' => false, 'message' => 'Invalid Sektor/Bidang Keuangan'], 200);
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (!Schema::hasTable($tablename)) {
            return response()->json(['valid' => true, 'message' => 'Data  ' . @$tahun . ' already deleted'], 200);
        }

        DB::table($tablename)->where('id_bidang', $id)->delete();
        return response()->json(['valid' => true, 'message' => 'Data Keuangan successfully deleted'], 200);
    }

    public function delete_upload_keuangan(Request $request)
    {

        $uploadId = Bidangkeuangan::select('upload_id')->distinct()->get()->pluck('upload_id');

        LogCms::whereIn('id', $uploadId)->update(['status' => 'deleted', 'submit_date' => Carbon::now()]);
        DB::table('upload_tracker')->where('type', 'keuangan')->delete();
        // truncate Temp
        Bidangkeuangan::truncate();

        return response()->json(['valid' => true, 'message' => 'Data Upload Keuangan successfully deleted'], 200);
    }


    ## EKONOMI
    public function index_ekonomi()
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $bahasa = Bahasa::where('status', 'tampil')->select('label', 'code')->get();
        $years = $this->tahunRepository->orderBY('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $province = $this->wilayahRepository->getArrayProvince();
        $province = ["" => "Semua"] + $province;
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }

        $data = [
            'bahasa' => $bahasa,
            'sektor' => $this->bidangRepository->get_bidang_options($this->id_ekonomi),
            'tahun' => $tahun,
            'province' => $province
        ];

        return view('bidangnilais.ekonomi.index')->with($data);
    }

    public function form_ekonomi(BidangnilaiDataTable $dataTable)
    {
        $years = $this->tahunRepository->orderBy('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [0 => 'Pilih Tahun Data'];
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }
        $sektor = $this->bidangRepository->get_bidang_options($this->id_ekonomi);
        $wilayah = $this->wilayahRepository->getArrayOptionsWilayah();

        return $dataTable->renders('bidangnilais.ekonomi.upload', ['tahun' => $tahun, 'wilayah' => $wilayah, 'sektor' => $sektor], [], []);
    }

    public function template_ekonomi(Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(2);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[] = $data0;
        $data[] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $data[] = [$lokasi['id'], $lokasi['code'], $lokasi['name']];
        }
        return Excel::download(new BidangnilaiExport($data), 'Template_Ekonomi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function download_upload_ekonomi()
    {
        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(2);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[0] = $data0;
        $data[1] = $data1;

        $ids = array_column($data_bidang, 'id');

        $bidangNilaiUpload = Bidangekonomi::select('id_wilayah', 'id_bidang', 'nilai')->whereIn('id_bidang', $ids)->get()->toArray(); // Convert collection to array

        // Group bidangNilaiUpload array by id_wilayah and id_bidang for efficient lookup
        $groupedBidangNilaiUpload = [];
        foreach ($bidangNilaiUpload as $nilaiData) {
            $groupedBidangNilaiUpload[$nilaiData['id_wilayah']][$nilaiData['id_bidang']] = $nilaiData;
        }


        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = $groupedBidangNilaiUpload[$lokasi['id']][$bidang['id']]['nilai'] ?? null;
                $details[] = $nilai;
            }
            $data[] = $details;
        }

        return Excel::download(new BidangnilaiExport($data), 'Data_Upload_Ekonomi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }


    public function download_upload_keuangan()
    {
        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(1);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[0] = $data0;
        $data[1] = $data1;

        $ids = array_column($data_bidang, 'id');

        $bidangNilaiUpload = Bidangkeuangan::select('id_wilayah', 'id_bidang', 'nilai')->whereIn('id_bidang', $ids)->get()->toArray(); // Convert collection to array

        // Group bidangNilaiUpload array by id_wilayah and id_bidang for efficient lookup
        $groupedBidangNilaiUpload = [];
        foreach ($bidangNilaiUpload as $nilaiData) {
            $groupedBidangNilaiUpload[$nilaiData['id_wilayah']][$nilaiData['id_bidang']] = $nilaiData;
        }


        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = $groupedBidangNilaiUpload[$lokasi['id']][$bidang['id']]['nilai'] ?? null;
                $details[] = $nilai;
            }
            $data[] = $details;
        }

        return Excel::download(new BidangnilaiExport($data), 'Data_Upload_Keuangan.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }


    public function download_upload_statistik()
    {
        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(3);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[0] = $data0;
        $data[1] = $data1;


        $ids = array_column($data_bidang, 'id');

        $bidangNilaiUpload = Bidangstatistik::select('id_wilayah', 'id_bidang', 'nilai')->whereIn('id_bidang', $ids)->get()->toArray(); // Convert collection to array

        // Group bidangNilaiUpload array by id_wilayah and id_bidang for efficient lookup
        $groupedBidangNilaiUpload = [];
        foreach ($bidangNilaiUpload as $nilaiData) {
            $groupedBidangNilaiUpload[$nilaiData['id_wilayah']][$nilaiData['id_bidang']] = $nilaiData;
        }

        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = $groupedBidangNilaiUpload[$lokasi['id']][$bidang['id']]['nilai'] ?? null;
                $details[] = $nilai;
                // $details[] = !empty($nilai) ? $nilai:null;
            }
            $data[] = $details;
        }

        return Excel::download(new BidangnilaiExport($data), 'Data_Upload_statistik.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function download_ekonomi(Request $request)
    {
        $tahun = $request->tahun;
        if (empty($tahun)) {
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-ekonomi.index'));
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        } else {
            Flash::error('Data belum tersedia');
            return redirect(route('data-ekonomi.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(2);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[0] = $data0;
        $data[1] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = @DB::table($tablename)->select('nilai')
                    ->where('id_wilayah', $lokasi['id'])
                    ->where('id_bidang', $bidang['id'])
                    ->whereNull('deleted_at')
                    ->first()->nilai;

                $details[] = !empty($nilai) ? $nilai : "N/A";
            }
            $data[] = $details;
        }

        return Excel::download(new BidangnilaiExport($data), 'Data_Ekonomi_' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function download_ekonomi_province(Request $request)
    {
        $tahun = $request->tahun;
        $provinceId = $request->province_id;
        if (empty($tahun)) {
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-ekonomi.index'));
        }

        if (empty($provinceId)) {
            Flash::error('Provinsi belum dipilih');
            return redirect(route('data-ekonomi.index'));
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        } else {
            Flash::error('Data belum tersedia');
            return redirect(route('data-ekonomi.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableProvince($provinceId);
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(2);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[0] = $data0;
        $data[1] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai =  @DB::table($tablename)->select('nilai')
                    ->where('id_wilayah', $lokasi['id'])
                    ->where('id_bidang', $bidang['id'])
                    ->whereNull('deleted_at')
                    ->first()->nilai;
                $details[] = !empty($nilai) ? $nilai : 'N/A`';
            }
            $data[] = $details;
        }

        return Excel::download(new BidangnilaiExport($data), 'Data_Ekonomi_' . $data_lokasi[0]['name'] . '_' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }


    public function upload_ekonomi(Request $request, BidangekonomiImport $import)
    {
        if (DB::table('upload_tracker')->where('type', 'ekonomi')->exists()) {
            return redirect(route('data-ekonomi.uploadform'))->withErrors(['Telah terdapat file yang diupload.']);
        }

        $akses = Auth::user();

        $uploadRecord = LogCms::create([
            'users_id' => $akses->id,
            'upload_date' => Carbon::now(),
            'upload_type' => 'Role Admin App: Upload Data Ekonomi'
        ]);


        try {
            if ($request->hasFile('file')) {
                DB::table('upload_tracker')->insert([
                    'type' => 'ekonomi',
                    'upload_id' => $uploadRecord->id
                ]);
                $arrayExcel = [];
                $file = $request->file('file');
                $import->setUploaderId($akses->id);
                $import->setUploadId($uploadRecord->id);
                $import->setArrayExcel($arrayExcel);
                $uploadRecord->file_name = $request->file('file')->getClientOriginalName();
                $uploadRecord->save();


                Excel::import($import, $file);

                $uploaded_count = Bidangekonomi::where('upload_id', $uploadRecord->id)->count();
                $arrayDb = Bidangekonomi::select('id_bidang', 'id_wilayah', DB::raw('ROUND(nilai, 3) AS nilai'))->get()->toArray();

                $md5Excel = md5(json_encode($arrayExcel));
                $md5DB = md5(json_encode($arrayDb));

                $checksum = [
                    'excel' => $md5Excel,
                    'db' => $md5DB,
                    'status' => ($md5Excel == $md5DB)
                ];

                Session::put('checksum-ekonomi', $checksum);

                if ($md5Excel != $md5DB) {
                    $listCompared = $this->compareData($arrayExcel, $arrayDb);
                    $uploadRecord->status = 'not valid';
                    $uploadRecord->save();
                    return redirect(route('data-ekonomi.uploadform'))->withErrors(array_merge(['Data Excel dan data yang terupload tidak sama.'], $listCompared));
                }

                if ($uploaded_count == 0) {
                    DB::table('upload_tracker')->where('type', 'ekonomi')->delete();
                }

                $uploadRecord->row_uploaded = $uploaded_count;

                $uploadRecord->save();

                return redirect(route('data-ekonomi.uploadform'))->withSuccess("File successfully uploaded. Uploaded {$uploaded_count} rows");
            } else {
                return redirect(route('data-ekonomi.uploadform'))->withErrors(['No File Uploaded']);
            }
        } catch (Exception $e) {
            DB::table('upload_tracker')->where('type', 'ekonomi')->delete();
            $uploadRecord->status = 'not valid';
            $uploadRecord->save();

            if ($e instanceof ListException) {
                return redirect(route('data-ekonomi.uploadform'))->withErrors($e->getList());
            }
            return redirect(route('data-ekonomi.uploadform'))->withErrors(['Error ketika upload.']);
        }
    }

    public function publish_ekonomi(Request $request)
    {
        $tahun = $request->tahun_data;
        $valid = false;

        // cek apa table sudah pernah ada?
        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
            MySql::create()
                ->setDbName(env('DB_DATABASE'))
                ->setUserName(env('DB_USERNAME'))
                ->setPassword(env('DB_PASSWORD'))
                ->setHost(env('DB_HOST'))
                ->includeTables($tablename)
                ->dumpToFile(storage_path('backup_' . $tablename . '_' . date('Y_m_d_H_i_s') . '.sql'));

            Schema::drop($tablename);

            Schema::create($tablename, function ($table) use ($tahun) {
                $table->increments('id');
                $table->integer('id_bidang')->nullable();
                $table->integer('id_nomenklatur')->nullable();
                $table->integer('id_wilayah')->nullable();
                $table->year('tahun')->default($tahun);
                $table->decimal('nilai', 12, 3)->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->longText('history_updated')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::create($tablename, function ($table) use ($tahun) {
                $table->increments('id');
                $table->integer('id_bidang')->nullable();
                $table->integer('id_nomenklatur')->nullable();
                $table->integer('id_wilayah')->nullable();
                $table->year('tahun')->default($tahun);
                $table->decimal('nilai', 12, 3)->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->longText('history_updated')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }


        $uploadId = @Bidangekonomi::first()->upload_id ?? 0;


        if (!$request->has('force')) {
            // The 'force' parameter exists in the query string
            $updated = Bidangekonomi::join("$tablename as na", function ($join) {
                $join->on('nomenklatur_amount_ekonomi.id_bidang', '=', 'na.id_bidang')
                    ->on('nomenklatur_amount_ekonomi.id_wilayah', '=', 'na.id_wilayah');
            })
                ->whereNull('na.deleted_at')
                ->whereNotNUll('na.history_updated')
                ->select(DB::raw('nomenklatur_amount_ekonomi.id_bidang, nomenklatur_amount_ekonomi.id_wilayah'))->get();
        }



        if (isset($updated) && count($updated) > 0) {
            return response()->json(['valid' => false, 'message' => 'Terdapat data yang sudah diupdate oleh admin wilayah'], 200);
        }

        DB::statement("
    DELETE t1 FROM $tablename t1
    JOIN (
        SELECT id_bidang, id_wilayah, created_at, id,
               ROW_NUMBER() OVER (PARTITION BY id_bidang, id_wilayah ORDER BY created_at DESC, id DESC) as rn
        FROM $tablename
    ) t2
    ON t1.id = t2.id
    WHERE t2.rn > 1
");

        $select = Bidangekonomi::selectRaw("nomenklatur_amount_ekonomi.id_bidang, nomenklatur_amount_ekonomi.id_wilayah, nomenklatur_amount_ekonomi.nilai, nomenklatur_amount_ekonomi.created_at, $tahun as tahun")
            ->distinct()
            ->get();
        // ->whereNotExists(function (Builder $query) use ($tablename){
        //     $query->select(DB::raw(1))
        //     ->from($tablename)
        //     ->where($tablename.'.id_bidang', 'nomenklatur_amount_ekonomi.id_bidang')
        //     ->where($tablename.'.id_wilayah', 'nomenklatur_amount_ekonomi.id_wilayah');
        // })
        // ->limit(1);

        $changed = (int) Bidangekonomi::join("$tablename as na", function ($join) {
            $join->on('nomenklatur_amount_ekonomi.id_bidang', '=', 'na.id_bidang')
                ->on('nomenklatur_amount_ekonomi.id_wilayah', '=', 'na.id_wilayah');
        })
            ->whereNull('na.deleted_at')
            ->select(DB::raw('
            COALESCE(SUM(CASE WHEN na.nilai != nomenklatur_amount_ekonomi.nilai or nomenklatur_amount_ekonomi.nilai IS NULL THEN 1 ELSE 0 END), 0) AS changed
        '))
            ->first()['changed'];

        $inserted = (int) Bidangekonomi::leftJoin("$tablename as na", function ($join) {
            $join->on('nomenklatur_amount_ekonomi.id_bidang', '=', 'na.id_bidang')
                ->on('nomenklatur_amount_ekonomi.id_wilayah', '=', 'na.id_wilayah');
        })
            ->whereNull('na.deleted_at')
            ->whereNotNull('nomenklatur_amount_ekonomi.nilai')
            ->select(DB::raw('
                COALESCE(SUM(CASE WHEN na.id_bidang  IS NULL THEN 1 ELSE 0 END), 0) AS inserted
            '))
            ->first()['inserted'];



        if ($select->isNotEmpty()) {

            $jakartaTime = Carbon::now('Asia/Jakarta')->toDateTimeString();

            $arrayTransfer = DB::table('nomenklatur_amount_ekonomi')
                ->join("$tablename as na", function ($join) {
                    $join->on('nomenklatur_amount_ekonomi.id_bidang', '=', 'na.id_bidang')
                        ->on('nomenklatur_amount_ekonomi.id_wilayah', '=', 'na.id_wilayah');
                })
                ->select(
                    'nomenklatur_amount_ekonomi.id_bidang',
                    'nomenklatur_amount_ekonomi.id_wilayah',
                    'nomenklatur_amount_ekonomi.tahun',
                    'nomenklatur_amount_ekonomi.nilai',
                    'nomenklatur_amount_ekonomi.created_at',
                    DB::raw("'{$jakartaTime}' as deleted_at") // Use the Jakarta time as deleted_at
                )
                ->get();
            foreach (array_chunk($arrayTransfer->toArray(), 500) as $chunk) {
                foreach ($chunk as &$item) {
                    $item = (array) $item;
                }
                DB::table('backup_old_data')->insert($chunk);
            }

            DB::table("$tablename")
                ->join('nomenklatur_amount_ekonomi', 'nomenklatur_amount_ekonomi.id_bidang', '=', "$tablename.id_bidang")
                ->whereColumn('nomenklatur_amount_ekonomi.id_wilayah', '=', "$tablename.id_wilayah")
                ->delete();
        }


        foreach (array_chunk($select->toArray(), 500) as $chunk) {
            foreach ($chunk as &$item) {
                // Format the created_at field to a specific timezone and format
                $item['created_at'] = Carbon::parse($item['created_at'])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
            }
            DB::table("$tablename")->insert($chunk);
        }

        $akses = Auth::user();

        $submitted = Bidangekonomi::count();

        $uploadRecord = LogCms::where('id', $uploadId)->first();

        LogCms::create(["users_id" => $akses->id, "row_submitted" => $submitted, 'row_uploaded' => $changed + $inserted, 'status' => 'submitted', 'submit_date' => Carbon::now(), 'upload_type' => 'Role Admin App: Publish Data Ekonomi tahun ' . $tahun,  'file_name' => @$uploadRecord->file_name]);

        @LogCms::where('id', $uploadId)->update(['submit_date' => Carbon::now(), 'status' => 'submitted']);


        // DB::raw(`INSERT into `.$tablename.` (id_bidang,id_wilayah,nilai,created_at)
        // select nomenklatur_amount_ekonomi.id_bidang, nomenklatur_amount_ekonomi.id_wilayah, nomenklatur_amount_ekonomi.nilai, nomenklatur_amount_ekonomi.created_at from nomenklatur_amount_ekonomi where not exists (select 1 from `.$tablename.` where `.$tablename.`.id_bidang = nomenklatur_amount_ekonomi.id_bidang and `.$tablename.`.id_wilayah = nomenklatur_amount_ekonomi.id_wilayah) and nomenklatur_amount_ekonomi.deleted_at is null`);

        // DB::raw(`INSERT into `.$tablename.` (id_bidang,id_wilayah,nilai,created_at)
        // select nomenklatur_amount_ekonomi.id_bidang, nomenklatur_amount_ekonomi.id_wilayah, nomenklatur_amount_ekonomi.nilai, nomenklatur_amount_ekonomi.created_at from nomenklatur_amount_ekonomi`);

        DB::table('upload_tracker')->where('type', 'ekonomi')->delete();
        // truncate Temp
        Bidangekonomi::truncate();


        // clear cache BE
        $ch = curl_init(env('API_URL') . "clear-cache");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        $valid = true;
        return response()->json(['valid' => $valid, 'message' => 'Data Ekonomi Tahun ' . $tahun . ' berhasil di publish', 'data' =>
        [
            'data_terpublish' => $submitted,
            'data_terganti' => $changed,
            'data_terinput' => $inserted
        ]], 200);
    }

    public function form_delete_ekonomi()
    {
        $years = $this->tahunRepository->orderBY('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $tahun[0] = '  Pilih tahun Data  ';
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }

        $sektor = 'ekonomi';
        return view('bidangnilais.show')->with('sektor', $sektor)->with('tahun', $tahun);
    }

    public function get_sektor_ekonomi(Request $request)
    {
        $tahun = $request->tahun;
        $ekonomi = $this->bidangRepository->get_sektor_table(2);
        $tablename = 'nomenklatur_amount_' . @$tahun;
        $nilai = null;
        if (Schema::hasTable($tablename)) {
            $nilai = DB::table($tablename)->distinct('id_bidang')->pluck('id_bidang')->toArray();
        }
        return response()->json(['sektor' => $ekonomi, 'nilai' => $nilai], 200);
    }

    public function delete_ekonomi(Request $request)
    {
        $id = @$request->sektor;
        $tahun = @$request->tahun;

        $bidang = $this->bidangRepository->findWithoutFail($id);
        if (empty($bidang)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang Ekonomi not found'], 200);
        }

        $parent = $this->bidangRepository->findWithoutFail($bidang->id_parent);
        $firstParent = $this->bidangRepository->get_first_sektor($id);

        if (empty($parent)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang Ekonomi not found'], 200);
        }

        if (strtolower(@$firstParent['nama']) !== strtolower('ekonomi')) {
            return response()->json(['valid' => false, 'message' => 'Invalid Sektor/Bidang Ekonomi'], 200);
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (!Schema::hasTable($tablename)) {
            return response()->json(['valid' => true, 'message' => 'Data  ' . @$tahun . ' already deleted'], 200);
        }

        DB::table($tablename)->where('id_bidang', $id)->delete();
        return response()->json(['valid' => true, 'message' => 'Data Ekonomi successfully deleted'], 200);
    }

    public function delete_upload_ekonomi(Request $request)
    {
        $uploadId = Bidangekonomi::select('upload_id')->distinct()->get()->pluck('upload_id');

        LogCms::whereIn('id', $uploadId)->update(['status' => 'deleted', 'submit_date' => Carbon::now()]);
        // truncate Temp
        Bidangekonomi::truncate();
        DB::table('upload_tracker')->where('type', 'ekonomi')->delete();
        return response()->json(['valid' => true, 'message' => 'Data Upload Ekonomi successfully deleted'], 200);
    }


    ## Statistik
    public function index_statistik()
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $bahasa = Bahasa::where('status', 'tampil')->select('label', 'code')->get();
        $years = $this->tahunRepository->orderBY('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $province = $this->wilayahRepository->getArrayProvince();
        $province = ["" => "Semua"] + $province;
        $tahun = [];
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }

        $data = [
            'bahasa' => $bahasa,
            'sektor' => $this->bidangRepository->get_bidang_options($this->id_statistik),
            'tahun' => $tahun,
            'province' => $province
        ];

        return view('bidangnilais.statistik.index')->with($data);
    }

    public function form_statistik(BidangnilaistatistikDataTable $dataTable)
    {
        $years = $this->tahunRepository->orderBy('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [0 => 'Pilih Tahun Data'];
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }
        $sektor = $this->bidangRepository->get_bidang_options($this->id_statistik);
        $wilayah = $this->wilayahRepository->getArrayOptionsWilayah();

        return $dataTable->renders('bidangnilais.statistik.upload', ['tahun' => $tahun, 'wilayah' => $wilayah, 'sektor' => $sektor], [], []);
    }

    public function template_statistik(Request $request)
    {
        $akses = Auth::user();
        if (!$akses->can('bidangnilai-show')) {
            abort(403, 'THIS PAGE IS UNAUTHORIZED.');
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(3);
        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[] = $data0;
        $data[] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $data[] = [$lokasi['id'], $lokasi['code'], $lokasi['name']];
        }

        return Excel::download(new BidangnilaiExport($data), 'Template_Statistik.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function download_statistik(Request $request)
    {
        $tahun = $request->tahun;
        if (empty($tahun)) {
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-statistik.index'));
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        } else {
            Flash::error('Data belum tersedia');
            return redirect(route('data-keuangan.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(3);

        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[0] = $data0;
        $data[1] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = @DB::table($tablename)->select('nilai')
                    ->where('id_wilayah', $lokasi['id'])
                    ->where('id_bidang', $bidang['id'])
                    ->whereNull('deleted_at')
                    ->first()->nilai;

                $details[] = !empty($nilai) ? $nilai : 'N/A';
            }
            $data[] = $details;
        }
        return Excel::download(new BidangnilaiExport($data), 'Data_Statistik_' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function download_statistik_province(Request $request)
    {
        $tahun = $request->tahun;
        $provinceId = $request->province_id;
        if (empty($tahun)) {
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-statistik.index'));
        }

        if (empty($provinceId)) {
            Flash::error('Provinsi belum dipilih');
            return redirect(route('data-statistik.index'));
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
        } else {
            Flash::error('Data belum tersedia');
            return redirect(route('data-keuangan.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableProvince($provinceId);
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(3);

        $data = [];
        $data0 = ['', '', ''];
        $data1 = ['ID <br />(No Removeable)', 'Kode<br />(No Removeable)', 'Prov/Kab/Kota'];
        foreach ($data_bidang as $bidang) {
            $data0[] = $bidang['code'];
            $data1[] = htmlspecialchars($bidang['name']);
        }
        $data[0] = $data0;
        $data[1] = $data1;

        foreach ($data_lokasi as $lokasi) {
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach ($data_bidang as $bidang) {
                $nilai = @DB::table($tablename)->select('nilai')
                    ->where('id_wilayah', $lokasi['id'])
                    ->where('id_bidang', $bidang['id'])
                    ->whereNull('deleted_at')
                    ->first()->nilai;

                $details[] = !empty($nilai) ? $nilai : 'N/A';
            }
            $data[] = $details;
        }
        return Excel::download(new BidangnilaiExport($data), 'Data_Statistik_' . $data_lokasi[0]['name'] . '_' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function upload_statistik(Request $request, BidangstatistikImport $import)
    {
        if (DB::table('upload_tracker')->where('type', 'statistik')->exists()) {
            return redirect(route('data-statistik.uploadform'))->withErrors(['Telah terdapat file yang diupload.']);
        }

        $akses = Auth::user();

        $uploadRecord = LogCms::create([
            'users_id' => $akses->id,
            'upload_date' => Carbon::now(),
            'upload_type' => 'Role Admin App: Upload Data Statistik'
        ]);

        try {
            if ($request->hasFile('file')) {
                DB::table('upload_tracker')->insert([
                    'type' => 'statistik',
                    'upload_id' => $uploadRecord->id
                ]);

                $arrayExcel = [];
                $file = $request->file('file');
                $import->setUploaderId($akses->id);
                $import->setUploadId($uploadRecord->id);
                $import->setArrayExcel($arrayExcel);
                $uploadRecord->file_name = $request->file('file')->getClientOriginalName();
                $uploadRecord->save();


                Excel::import($import, $file);

                $uploaded_count = Bidangstatistik::where('upload_id', $uploadRecord->id)->count();

                $arrayDb = Bidangstatistik::select('id_bidang', 'id_wilayah', DB::raw('ROUND(nilai, 3) AS nilai'))->get()->toArray();

                $md5Excel = md5(json_encode($arrayExcel));
                $md5DB = md5(json_encode($arrayDb));

                $checksum = [
                    'excel' => $md5Excel,
                    'db' => $md5DB,
                    'status' => ($md5Excel == $md5DB)
                ];

                Session::put('checksum-statistik', $checksum);

                if ($md5Excel != $md5DB) {
                    $listCompared = $this->compareData($arrayExcel, $arrayDb);
                    $uploadRecord->status = 'not valid';
                    $uploadRecord->save();
                    return redirect(route('data-statistik.uploadform'))->withErrors(array_merge(['Data Excel dan data yang terupload tidak sama.'], $listCompared));
                }

                if ($uploaded_count == 0) {
                    DB::table('upload_tracker')->where('type', 'statistik')->delete();
                }

                $uploadRecord->row_uploaded = $uploaded_count;

                $uploadRecord->save();

                return redirect(route('data-statistik.uploadform'))->withSuccess("File successfully uploaded. Uploaded {$uploaded_count} rows");
            } else {
                return redirect(route('data-statistik.uploadform'))->withErrors(['No File Uploaded']);
            }
        } catch (Exception $e) {
            DB::table('upload_tracker')->where('type', 'statistik')->delete();
            $uploadRecord->status = 'not valid';
            $uploadRecord->save();

            if ($e instanceof ListException) {
                return redirect(route('data-statistik.uploadform'))->withErrors($e->getList());
            }
            return redirect(route('data-statistik.uploadform'))->withErrors(['Error ketika upload.']);
        }
    }

    public function publish_statistik(Request $request)
    {
        $tahun = $request->tahun_data;
        $valid = false;

        // cek apa table sudah pernah ada?
        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (Schema::hasTable($tablename)) {
            MySql::create()
                ->setDbName(env('DB_DATABASE'))
                ->setUserName(env('DB_USERNAME'))
                ->setPassword(env('DB_PASSWORD'))
                ->setHost(env('DB_HOST'))
                ->includeTables($tablename)
                ->dumpToFile(storage_path('backup_' . $tablename . '_' . date('Y_m_d_H_i_s') . '.sql'));

            Schema::drop($tablename);

            Schema::create($tablename, function ($table) use ($tahun) {
                $table->increments('id');
                $table->integer('id_bidang')->nullable();
                $table->integer('id_nomenklatur')->nullable();
                $table->integer('id_wilayah')->nullable();
                $table->year('tahun')->default($tahun);
                $table->decimal('nilai', 12, 3)->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->longText('history_updated')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::create($tablename, function ($table) use ($tahun) {
                $table->increments('id');
                $table->integer('id_bidang')->nullable();
                $table->integer('id_nomenklatur')->nullable();
                $table->integer('id_wilayah')->nullable();
                $table->year('tahun')->default($tahun);
                $table->decimal('nilai', 12, 3)->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->longText('history_updated')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        $uploadId = @Bidangstatistik::first()->upload_id ?? 0;

        if (!$request->has('force')) {
            // The 'force' parameter exists in the query string
            $updated = Bidangstatistik::join("$tablename as na", function ($join) {
                $join->on('nomenklatur_amount_statistik.id_bidang', '=', 'na.id_bidang')
                    ->on('nomenklatur_amount_statistik.id_wilayah', '=', 'na.id_wilayah');
            })
                ->whereNull('na.deleted_at')
                ->whereNotNUll('na.history_updated')
                ->select(DB::raw('nomenklatur_amount_statistik.id_bidang, nomenklatur_amount_statistik.id_wilayah'))->get();
        }



        if (isset($updated) && count($updated) > 0) {
            return response()->json(['valid' => false, 'message' => 'Terdapat data yang sudah diupdate oleh admin wilayah'], 200);
        }


        DB::statement("
    DELETE t1 FROM $tablename t1
    JOIN (
        SELECT id_bidang, id_wilayah, created_at, id,
               ROW_NUMBER() OVER (PARTITION BY id_bidang, id_wilayah ORDER BY created_at DESC, id DESC) as rn
        FROM $tablename
    ) t2
    ON t1.id = t2.id
    WHERE t2.rn > 1
");

        // Select dahulu
        $select = Bidangstatistik::selectRaw("nomenklatur_amount_statistik.id_bidang, nomenklatur_amount_statistik.id_wilayah, nomenklatur_amount_statistik.nilai, nomenklatur_amount_statistik.created_at, ? as tahun", [$tahun])
            ->distinct()
            ->get();



        // ->whereNotExists(function (Builder $query) use ($tablename){
        //     $query->select(DB::raw(1))
        //     ->from($tablename)
        //     ->where($tablename.'.id_bidang', 'nomenklatur_amount_statistik.id_bidang')
        //     ->where($tablename.'.id_wilayah', 'nomenklatur_amount_statistik.id_wilayah');
        // });

        $changed = (int) BidangStatistik::join("$tablename as na", function ($join) {
            $join->on('nomenklatur_amount_statistik.id_bidang', '=', 'na.id_bidang')
                ->on('nomenklatur_amount_statistik.id_wilayah', '=', 'na.id_wilayah');
        })
            ->whereNull('na.deleted_at')
            ->select(DB::raw('
            COALESCE(SUM(CASE WHEN na.nilai != nomenklatur_amount_statistik.nilai or nomenklatur_amount_statistik.nilai IS NULL THEN 1 ELSE 0 END), 0) AS changed
        '))
            ->first()['changed'];


        $inserted = (int) Bidangstatistik::leftJoin("$tablename as na", function ($join) {
            $join->on('nomenklatur_amount_statistik.id_bidang', '=', 'na.id_bidang')
                ->on('nomenklatur_amount_statistik.id_wilayah', '=', 'na.id_wilayah');
        })
            ->whereNull('na.deleted_at')
            ->whereNotNull('nomenklatur_amount_statistik.nilai')
            ->select(DB::raw('
                        COALESCE(SUM(CASE WHEN na.id_bidang  IS NULL THEN 1 ELSE 0 END), 0) AS inserted
                    '))
            ->first()['inserted'];



        if ($select->isNotEmpty()) {
            $jakartaTime = Carbon::now('Asia/Jakarta')->toDateTimeString();

            $arrayTransfer = DB::table('nomenklatur_amount_statistik')
                ->join("$tablename as na", function ($join) {
                    $join->on('nomenklatur_amount_statistik.id_bidang', '=', 'na.id_bidang')
                        ->on('nomenklatur_amount_statistik.id_wilayah', '=', 'na.id_wilayah');
                })
                ->select(
                    'nomenklatur_amount_statistik.id_bidang',
                    'nomenklatur_amount_statistik.id_wilayah',
                    'nomenklatur_amount_statistik.tahun',
                    'nomenklatur_amount_statistik.nilai',
                    'nomenklatur_amount_statistik.created_at',
                    DB::raw("'{$jakartaTime}' as deleted_at") // Use the Jakarta time as deleted_at
                )
                ->get();


            foreach (array_chunk($arrayTransfer->toArray(), 500) as $chunk) {
                foreach ($chunk as &$item) {
                    $item = (array) $item;
                }
                DB::table('backup_old_data')->insert($chunk);
            }



            DB::table("$tablename")
                ->join('nomenklatur_amount_statistik', 'nomenklatur_amount_statistik.id_bidang', '=', "$tablename.id_bidang")
                ->whereColumn('nomenklatur_amount_statistik.id_wilayah', '=', "$tablename.id_wilayah")
                ->delete();
        }


        foreach (array_chunk($select->toArray(), 500) as $chunk) {
            foreach ($chunk as &$item) {
                // Format the created_at field to a specific timezone and format
                $item['created_at'] = Carbon::parse($item['created_at'])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
            }
            DB::table("$tablename")->insert($chunk);
        }



        $akses = Auth::user();


        $submitted = Bidangstatistik::count();

        $uploadRecord = LogCms::where('id', $uploadId)->first();

        LogCms::create(["users_id" => $akses->id, "row_submitted" => $submitted, 'row_uploaded' => $changed + $inserted, 'status' => 'submitted', 'submit_date' => Carbon::now(), 'upload_type' => 'Role Admin App: Publish Data Statistik tahun ' . $tahun,  'file_name' => @$uploadRecord->file_name]);

        @LogCms::where('id', $uploadId)->update(['submit_date' => Carbon::now(), 'status' => 'submitted',]);

        // DB::raw(`INSERT into `.$tablename.` (id_bidang,id_wilayah,nilai,created_at)
        // select nomenklatur_amount_statistik.id_bidang, nomenklatur_amount_statistik.id_wilayah, nomenklatur_amount_statistik.nilai, nomenklatur_amount_statistik.created_at from nomenklatur_amount_statistik where not exists (select 1 from `.$tablename.` where `.$tablename.`.id_bidang = nomenklatur_amount_statistik.id_bidang and `.$tablename.`.id_wilayah = nomenklatur_amount_statistik.id_wilayah) and nomenklatur_amount_statistik.deleted_at is null`);

        // DB::statement(`INSERT into `.$tablename.` (id_bidang,id_wilayah,nilai,created_at)
        // select nomenklatur_amount_statistik.id_bidang, nomenklatur_amount_statistik.id_wilayah, nomenklatur_amount_statistik.nilai, nomenklatur_amount_statistik.created_at from nomenklatur_amount_statistik`);

        DB::table('upload_tracker')->where('type', 'statistik')->delete();
        // truncate Temp
        Bidangstatistik::truncate();

        // clear cache BE
        $ch = curl_init(env('API_URL') . "clear-cache");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        $valid = true;
        return response()->json(['valid' => $valid, 'message' => 'Data Statistik Tahun ' . $tahun . ' berhasil di publish', 'data' => [
            'data_terpublish' => $submitted,
            'data_terganti' => $changed,
            'data_terinput' => $inserted
        ]], 200);
    }

    public function form_delete_statistik()
    {
        $years = $this->tahunRepository->orderBY('tahun', 'desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $tahun[0] = '  Pilih tahun Data  ';
        foreach ($years as $year) {
            $tahun[$year] = $year;
        }

        $sektor = 'statistik';
        return view('bidangnilais.show')->with('sektor', $sektor)->with('tahun', $tahun);
    }

    public function get_sektor_statistik(Request $request)
    {
        $tahun = $request->tahun;
        $statistik = $this->bidangRepository->get_sektor_table(3);
        $tablename = 'nomenklatur_amount_' . @$tahun;
        $nilai = null;
        if (Schema::hasTable($tablename)) {
            $nilai = DB::table($tablename)->distinct('id_bidang')->pluck('id_bidang')->toArray();
        }
        return response()->json(['sektor' => $statistik, 'nilai' => $nilai], 200);
    }

    public function delete_statistik(Request $request)
    {
        $id = @$request->sektor;
        $tahun = @$request->tahun;

        $bidang = $this->bidangRepository->findWithoutFail($id);
        if (empty($bidang)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang Statistik not found'], 200);
        }

        $parent = $this->bidangRepository->findWithoutFail($bidang->id_parent);
        $firstParent = $this->bidangRepository->get_first_sektor($id);

        if (empty($parent)) {
            return response()->json(['valid' => false, 'message' => 'Sektor/Bidang Statistik not found'], 200);
        }

        if (strtolower(@$firstParent['nama']) !== strtolower('statistik')) {
            return response()->json(['valid' => false, 'message' => 'Invalid Sektor/Bidang Statistik'], 200);
        }

        $tablename = 'nomenklatur_amount_' . @$tahun;
        if (!Schema::hasTable($tablename)) {
            return response()->json(['valid' => true, 'message' => 'Data ' . @$tahun . ' already deleted'], 200);
        }

        DB::table($tablename)->where('id_bidang', $id)->delete();
        return response()->json(['valid' => true, 'message' => 'Data Statistik successfully deleted'], 200);
    }

    public function delete_upload_statistik(Request $request)
    {
        $uploadId = Bidangstatistik::select('upload_id')->distinct()->get()->pluck('upload_id');
        LogCms::whereIn('id', $uploadId)->update(['status' => 'deleted', 'submit_date' => Carbon::now()]);
        // truncate Temp
        Bidangstatistik::truncate();
        DB::table('upload_tracker')->where('type', 'statistik')->delete();
        return response()->json(['valid' => true, 'message' => 'Data Upload Statistik successfully deleted'], 200);
    }

    public function catatan(Request $request)
    {
        $tahun = $request->tahun;
        $bidang = $request->bidang;
        $wilayah = $request->wilayah;

        $bahasa = Bahasa::where('status', 'tampil')->select('label', 'code')->get();
        $catatan = Bidangcatatan::where('tahun', $tahun)->where('id_bidang', $bidang)->where('id_wilayah', $wilayah)->select('id', 'catatan')->get();
        $notes = null;
        foreach ($catatan as $cat) {
            $cat_lg = [];
            foreach ($bahasa as $lg) {
                $cat_lg[$lg->code] = $cat->getTranslation('catatan', $lg->code);
            }
            $notes[] = [
                'id' => $cat->id,
                'catatan' => $cat_lg,
            ];
        }
        return response()->json(['valid' => true, 'data' => $notes], 200);
    }

    public function catatan_submit(Request $request)
    {
        $input = $request->all();
        $tahun = @$input["tahun"];
        $bidang = @$input["bidang"];
        $wilayah = @$input["wilayah"];
        $notes = @$input["notes"] ?? [];
        $arr_id = [];

        foreach ($notes as $note) {
            if (intval(@$note['id']) > 0) {
                $catatan = Bidangcatatan::whereId(@$note['id'])->update([
                    'catatan' => $note['note'],
                    'updated_at' => Carbon::now('Asia/Jakarta'),
                ]);
                $arr_id[] = $note['id'];
            } else {
                $catatan = Bidangcatatan::create([
                    'id_wilayah' => $wilayah,
                    'id_bidang' => $bidang,
                    'tahun' => $tahun,
                    'catatan' => $note['note'],
                    'created_at' => Carbon::now('Asia/Jakarta'),
                ]);
                $arr_id[] = $catatan->id;
            }
        }
        // Hapus Catatan yg id nya gak ada disini
        Bidangcatatan::where('id_wilayah', $wilayah)->where('id_bidang', $bidang)->where('tahun', $tahun)->whereNotIn('id', $arr_id)->forceDelete();

        return response()->json(['valid' => true, 'message' => 'Catatan berhasil di simpan'], 200);
    }

    private function compareData(array $array1, array $array2)
    {
        if (count($array1) != count($array2)) {
            return ['Data tidak sama jumlahnya'];
        }

        $listerror = [];
        for ($i = 0; $i < count($array1); $i++) {
            if ($array1[$i]['nilai'] != $array2[$i]['nilai']) {
                $listerror[] = "Terdapat data tidak match pada id wilayah : {$array1[$i]['id_wilayah']}, id sektor : {$array1[$i]['id_bidang']}, dan nilai array excel = {$array1[$i]['nilai']} || array db = {$array2[$i]['nilai']} ";
            }
        }

        return $listerror;
    }
}
