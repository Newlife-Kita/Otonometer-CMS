<?php

namespace App\Http\Controllers;

use App\DataTables\BidangnilaiDataTable;
use App\DataTables\BidangnilaikeuanganDataTable;
use App\DataTables\BidangnilaistatistikDataTable;
use App\Export\BidangnilaiExport;
use App\Http\Requests;
use App\Http\Requests\CreateBidangnilaiRequest;
use App\Http\Requests\UpdateBidangnilaiRequest;
use App\Repositories\BidangnilaiRepository;
use App\Repositories\BidangRepository;
use App\Repositories\SettingRepository;
use App\Repositories\WilayahRepository;
use Laracasts\Flash\Flash;
use App\Http\Controllers\AppBaseController;
use App\Import\BidangekonomiImport;
use App\Import\BidangkeuanganImport;
use App\Import\BidangnilaiImport;
use App\Import\BidangstatistikImport;
use App\Models\Bidangekonomi;
use App\Models\Bidangkeuangan;
use App\Models\Bidangstatistik;
use App\Repositories\NomenklaturtahunRepository;
use Response;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel; 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

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

    public function ajax_sektor(Request $request){
        $tahun = $request->tahun;
        $bidang = $request->bidang;

        if(empty($tahun)){
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Tahun data belum dipilih']);
        }
        if(empty($bidang)){
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Sektor/Bidang belum dipilih']);
        }

        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(Schema::hasTable($tablename)){}
        else{            
            return response()->json(['valid' => false, 'items' => '', 'message' => 'Data belum tersedia']);
        }

        $nilai = DB::table($tablename)->selectRaw('distinct('.$tablename.'.id_wilayah), '.$tablename.'.nilai, master_wilayah.nama')
        ->leftJoin('master_wilayah','master_wilayah.id','=', $tablename.'.id_wilayah')
        ->where($tablename.'.id_bidang', $bidang)->whereNull($tablename.'.deleted_at')
        ->orderBY($tablename.'.nilai','desc')->get();

        return response()->json(['valid' => true, 'items' => $nilai, 'message' => '']);
    }

    ## KEUANGAN
    public function index_keuangan()
    {
        $years = $this->tahunRepository->orderBY('tahun','desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        foreach($years as $year){
            $tahun[$year] = $year;
        }

        $data = [
            'sektor' => $this->bidangRepository->get_bidang_options($this->id_keuangan),
            'tahun' => $tahun,
            'id_sektor' => $this->id_keuangan
        ];

        return view('bidangnilais.keuangan.index')->with($data);
    }

    public function form_keuangan(BidangnilaikeuanganDataTable $dataTable){
        $years = $this->tahunRepository->all()->pluck('tahun')->toArray();
        $tahun = [0 => 'Pilih Tahun Data'];
        foreach($years as $year){
            $tahun[$year] = $year;
        }
        $sektor = $this->bidangRepository->get_bidang_options($this->id_keuangan);
        $wilayah = $this->wilayahRepository->getArrayOptionsWilayah();

        return $dataTable->renders('bidangnilais.keuangan.upload', ['tahun' => $tahun, 'wilayah' => $wilayah, 'sektor' => $sektor], [],[]);
    }

    public function download_keuangan(Request $request){
        $tahun = $request->tahun;
        if(empty($tahun)){
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-keuangan.index'));
        }

        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(Schema::hasTable($tablename)){}
        else{            
            Flash::error('Data belum tersedia');
            return redirect(route('data-keuangan.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(1);
        $data = [];
        $data0 = ['','',''];
        $data1 = ['ID <br />(No Removeable)','Kode<br />(No Removeable)','Prov/Kab/Kota'];
        foreach($data_bidang as $bidang){
            $data0[] = $bidang['code'];
            $data1[] = $bidang['name'];
        }
        $data[] = $data0;
        $data[] = $data1;
        
        foreach($data_lokasi as $lokasi){
            $details = [];
            $details[] = $lokasi['id'];
            $details[] = $lokasi['code'];
            $details[] = $lokasi['name'];
            foreach($data_bidang as $bidang){
                $details[] = null;
                // @DB::table($tablename)->select('nilai')
                // ->where('id_wilayah', $lokasi['id'])
                // ->where('id_bidang', $bidang['id'])
                // ->whereNull('deleted_at')
                // ->first()->nilai;
            }
            $data[] = $details;
        }
        return Excel::download(new BidangnilaiExport ($data), 'Data_Keuangan.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    
    public function upload_keuangan(Request $request){
        if ($request->hasFile('file')) {
            $file = $request->file('file');   
            $import = new BidangkeuanganImport;
            $import->setRepo($this->bidangRepository);
            Excel::import($import, $file);
            
            return redirect(route('data-keuangan.uploadform'))->withSuccess('File successfully uploaded');
        }
        else{
            return redirect(route('data-keuangan.uploadform'))->withErrors(['No File Uploaded']);
        }
    }

    public function publish_keuangan(Request $request){
        $tahun = $request->tahun_data;
        $valid = false;
        
        // cek apa table sudah pernah ada?
        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(Schema::hasTable($tablename)){}
        else{
            Schema::create($tablename,function($table) use ($tahun)
            {
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

        //Select dahulu
        $select = Bidangkeuangan::select('nomenklatur_amount_keuangan.id_bidang', 'nomenklatur_amount_keuangan.id_wilayah', 'nomenklatur_amount_keuangan.nilai', 'nomenklatur_amount_keuangan.created_at')
            ->whereNotExists(function (Builder $query) use ($tablename){
                $query->select(DB::raw(1))
                ->from($tablename)
                ->where($tablename.'.id_bidang', 'nomenklatur_amount.id_bidang')
                ->where($tablename.'.id_wilayah', 'nomenklatur_amount.id_wilayah');
            });

        // Binding parameter
        $bindings = $select->getBindings();

        // Insert new table
        $insertQuery = 'INSERT into '.$tablename.' (id_bidang,id_wilayah,nilai,created_at) ' . $select->toSql();
        DB::insert($insertQuery, $bindings);

        // truncate Temp
        Bidangkeuangan::truncate();
        $valid = true;
        return response()->json(['valid' => $valid, 'message' => 'Data Keuangan Tahun '.$tahun.' berhasil di publish'], 200);
    }

    public function form_delete_keuangan(){
        $years = $this->tahunRepository->orderBY('tahun','desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $tahun[0] = '  Pilih tahun Data  ';
        foreach($years as $year){
            $tahun[$year] = $year;
        }

        $sektor = 'keuangan';
        return view('bidangnilais.show')->with('sektor', $sektor)->with('tahun', $tahun);
    }

    public function get_sektor_keuangan(Request $request){
        $tahun = $request->tahun;
        $keuangan = $this->bidangRepository->get_sektor_table(1);
        $tablename = 'nomenklatur_amount_'.@$tahun;
        $nilai = null;
        if(Schema::hasTable($tablename)){
            $nilai = DB::table($tablename)->distinct('id_bidang')->pluck('id_bidang')->toArray();
        }
        return response()->json(['sektor' => $keuangan, 'nilai' => $nilai], 200);
    }
    
    public function delete_keuangan(Request $request){
        $id = @$request->sektor;
        $tahun = @$request->tahun;

        $bidang = $this->bidangRepository->findWithoutFail($id);
        if(empty($bidang)){
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

        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(!Schema::hasTable($tablename)){
            return response()->json(['valid' => true, 'message' => 'Data  '.@$tahun.' already deleted'], 200);
        }

        DB::table($tablename)->where('id_bidang',$id)->delete();
        return response()->json(['valid' => true, 'message' => 'Data Keuangan successfully deleted'], 200);
    }

    public function delete_upload_keuangan(Request $request){
        // truncate Temp
        Bidangkeuangan::truncate();
        return response()->json(['valid' => true, 'message' => 'Data Upload Keuangan successfully deleted'], 200);
    }


    ## EKONOMI
    public function index_ekonomi()
    {
        $years = $this->tahunRepository->orderBY('tahun','desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        foreach($years as $year){
            $tahun[$year] = $year;
        }

        $data = [
            'sektor' => $this->bidangRepository->get_bidang_options($this->id_ekonomi),
            'tahun' => $tahun
        ];

        return view('bidangnilais.ekonomi.index')->with($data);
    }

    public function form_ekonomi(BidangnilaiDataTable $dataTable){
        $years = $this->tahunRepository->all()->pluck('tahun')->toArray();
        $tahun = [0 => 'Pilih Tahun Data'];
        foreach($years as $year){
            $tahun[$year] = $year;
        }
        $sektor = $this->bidangRepository->get_bidang_options($this->id_ekonomi);
        $wilayah = $this->wilayahRepository->getArrayOptionsWilayah();

        return $dataTable->renders('bidangnilais.ekonomi.upload', ['tahun' => $tahun, 'wilayah' => $wilayah, 'sektor' => $sektor], [],[]);
    }

    public function download_ekonomi(Request $request){
        $tahun = $request->tahun;
        if(empty($tahun)){
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-keuangan.index'));
        }

        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(Schema::hasTable($tablename)){}
        else{            
            Flash::error('Data belum tersedia');
            return redirect(route('data-keuangan.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(2);
        $data = [];
        $data0 = ['','',''];
        $data1 = ['ID <br />(No Removeable)','Kode<br />(No Removeable)','Prov/Kab/Kota'];
        foreach($data_bidang as $bidang){
            $data0[] = $bidang['code'];
            $data1[] = $bidang['name'];
        }
        $data[0] = $data0;
        $data[1] = $data1;

        foreach($data_lokasi as $lokasi){
            $details = [];
            $details[0] = $lokasi['id'];
            $details[1] = "'".$lokasi['code'];
            $details[2] = $lokasi['name'];
            foreach($data_bidang as $bidang){
                $details[] = null;
                // $nilai = @DB::table($tablename)->select($tablename.'.nilai')
                // ->where('id_wilayah', $lokasi['id'])
                // ->where('id_bidang', $bidang['id'])
                // ->whereNull($tablename.'.deleted_at')
                // ->first()->nilai;
                // $details[] = !empty($nilai) ? $nilai:null;
            }
            $data[] = $details;
        }
        
        return Excel::download(new BidangnilaiExport ($data), 'Data_Ekonomi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    
    public function upload_ekonomi(Request $request){
        if ($request->hasFile('file')) {
            $file = $request->file('file');   

            $import = new BidangekonomiImport;
            $import->setRepo($this->bidangRepository);

            Excel::import($import, $file);
            
            return redirect(route('data-ekonomi.uploadform'))->withSuccess('File successfully uploaded');
        }
        else{
            return redirect(route('data-ekonomi.uploadform'))->withErrors(['No File Uploaded']);
        }
    }

    public function publish_ekonomi(Request $request){
        $tahun = $request->tahun_data;
        $valid = false;
        
        // cek apa table sudah pernah ada?
        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(Schema::hasTable($tablename)){}
        else{
            Schema::create($tablename,function($table) use ($tahun)
            {
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

        //Select dahulu
        $select = Bidangekonomi::select('nomenklatur_amount_ekonomi.id_bidang', 'nomenklatur_amount_ekonomi.id_wilayah', 'nomenklatur_amount_ekonomi.nilai', 'nomenklatur_amount_ekonomi.created_at')
            ->whereNotExists(function (Builder $query) use ($tablename){
                $query->select(DB::raw(1))
                ->from($tablename)
                ->where($tablename.'.id_bidang', 'nomenklatur_amount.id_bidang')
                ->where($tablename.'.id_wilayah', 'nomenklatur_amount.id_wilayah');
            });

        // Binding parameter
        $bindings = $select->getBindings();

        // Insert new table
        $insertQuery = 'INSERT into '.$tablename.' (id_bidang,id_wilayah,nilai,created_at) ' . $select->toSql();
        DB::insert($insertQuery, $bindings);

        // truncate Temp
        Bidangekonomi::truncate();
        $valid = true;
        return response()->json(['valid' => $valid, 'message' => 'Data Ekonomi Tahun '.$tahun.' berhasil di publish'], 200);
    }
    
    public function form_delete_ekonomi(){
        $years = $this->tahunRepository->orderBY('tahun','desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $tahun[0] = '  Pilih tahun Data  ';
        foreach($years as $year){
            $tahun[$year] = $year;
        }

        $sektor = 'ekonomi';
        return view('bidangnilais.show')->with('sektor', $sektor)->with('tahun', $tahun);
    }

    public function get_sektor_ekonomi(Request $request){
        $tahun = $request->tahun;
        $ekonomi = $this->bidangRepository->get_sektor_table(2);
        $tablename = 'nomenklatur_amount_'.@$tahun;
        $nilai = null;
        if(Schema::hasTable($tablename)){
            $nilai = DB::table($tablename)->distinct('id_bidang')->pluck('id_bidang')->toArray();
        }
        return response()->json(['sektor' => $ekonomi, 'nilai' => $nilai], 200);
    }
    
    public function delete_ekonomi(Request $request){
        $id = @$request->sektor;
        $tahun = @$request->tahun;

        $bidang = $this->bidangRepository->findWithoutFail($id);
        if(empty($bidang)){
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

        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(!Schema::hasTable($tablename)){
            return response()->json(['valid' => true, 'message' => 'Data  '.@$tahun.' already deleted'], 200);
        }

        DB::table($tablename)->where('id_bidang',$id)->delete();
        return response()->json(['valid' => true, 'message' => 'Data Ekonomi successfully deleted'], 200);
    }

    public function delete_upload_ekonomi(Request $request){
        // truncate Temp
        Bidangekonomi::truncate();
        return response()->json(['valid' => true, 'message' => 'Data Upload Ekonomi successfully deleted'], 200);
    }


    ## Statistik
    public function index_statistik()
    {
        $years = $this->tahunRepository->orderBY('tahun','desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        foreach($years as $year){
            $tahun[$year] = $year;
        }

        $data = [
            'sektor' => $this->bidangRepository->get_bidang_options($this->id_statistik),
            'tahun' => $tahun
        ];

        return view('bidangnilais.statistik.index')->with($data);
    }

    public function form_statistik(BidangnilaistatistikDataTable $dataTable){
        $years = $this->tahunRepository->all()->pluck('tahun')->toArray();
        $tahun = [0 => 'Pilih Tahun Data'];
        foreach($years as $year){
            $tahun[$year] = $year;
        }
        $sektor = $this->bidangRepository->get_bidang_options($this->id_statistik);
        $wilayah = $this->wilayahRepository->getArrayOptionsWilayah();

        return $dataTable->renders('bidangnilais.statistik.upload', ['tahun' => $tahun, 'wilayah' => $wilayah, 'sektor' => $sektor], [],[]);
    }

    public function download_statistik(Request $request){
        $tahun = $request->tahun;
        if(empty($tahun)){
            Flash::error('Tahun data belum dipilih');
            return redirect(route('data-statistik.index'));
        }

        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(Schema::hasTable($tablename)){}
        else{            
            Flash::error('Data belum tersedia');
            return redirect(route('data-keuangan.index'));
        }

        $data_lokasi = $this->wilayahRepository->getArrayWilayahTableHead();
        $data_bidang = $this->bidangRepository->getArrayBidangTableChild(3);
        
        $data = [];
        $data0 = ['','',''];
        $data1 = ['ID <br />(No Removeable)','Kode<br />(No Removeable)','Prov/Kab/Kota'];
        foreach($data_bidang as $bidang){
            $data0[] = $bidang['code'];
            $data1[] = $bidang['name'];
        }
        $data[0] = $data0;
        $data[1] = $data1;

        foreach($data_lokasi as $lokasi){
            $details = [];
            $details[0] = $lokasi['id'];
            $details[1] = "'".$lokasi['code'];
            $details[2] = $lokasi['name'];
            foreach($data_bidang as $bidang){
                $details[] = null;
                // $nilai = @DB::table($tablename)->select($tablename.'.nilai')
                // ->where('id_wilayah', $lokasi['id'])
                // ->where('id_bidang', $bidang['id'])
                // ->whereNull($tablename.'.deleted_at')
                // ->first()->nilai;
                // $details[] = $nilai;
            }
            $data[] = $details;
        }
        return Excel::download(new BidangnilaiExport ($data), 'Data_Statistik.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    
    public function upload_statistik(Request $request){
        if ($request->hasFile('file')) {
            $file = $request->file('file');   

            $import = new BidangstatistikImport; //mganti statistik
            $import->setRepo($this->bidangRepository);

            Excel::import($import, $file);
            
            return redirect(route('data-statistik.uploadform'))->withSuccess('File successfully uploaded');
        }
        else{
            return redirect(route('data-statistik.uploadform'))->withErrors(['No File Uploaded']);
        }
    }

    public function publish_statistik(Request $request){
        $tahun = $request->tahun_data;
        $valid = false;
        
        // cek apa table sudah pernah ada?
        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(Schema::hasTable($tablename)){}
        else{
            Schema::create($tablename,function($table) use ($tahun)
            {
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

        //Select dahulu
        $select = Bidangstatistik::select('nomenklatur_amount_statistik.id_bidang', 'nomenklatur_amount_statistik.id_wilayah', 'nomenklatur_amount_statistik.nilai', 'nomenklatur_amount_statistik.created_at')
            ->whereNotExists(function (Builder $query) use ($tablename){
                $query->select(DB::raw(1))
                ->from($tablename)
                ->where($tablename.'.id_bidang', 'nomenklatur_amount_statistik.id_bidang')
                ->where($tablename.'.id_wilayah', 'nomenklatur_amount_statistik.id_wilayah');
            });

        // Binding parameter
        $bindings = $select->getBindings();

        // Insert new table
        $insertQuery = 'INSERT into '.$tablename.' (id_bidang,id_wilayah,nilai,created_at) ' . $select->toSql();
        DB::insert($insertQuery, $bindings);

        // truncate Temp
        Bidangstatistik::truncate(); 
        $valid = true;
        return response()->json(['valid' => $valid, 'message' => 'Data Statistik Tahun '.$tahun.' berhasil di publish'], 200);
    }

    public function form_delete_statistik(){
        $years = $this->tahunRepository->orderBY('tahun','desc')->get()->pluck('tahun')->toArray();
        $tahun = [];
        $tahun[0] = '  Pilih tahun Data  ';
        foreach($years as $year){
            $tahun[$year] = $year;
        }

        $sektor = 'statistik';
        return view('bidangnilais.show')->with('sektor', $sektor)->with('tahun', $tahun);
    }

    public function get_sektor_statistik(Request $request){
        $tahun = $request->tahun;
        $statistik = $this->bidangRepository->get_sektor_table(3);
        $tablename = 'nomenklatur_amount_'.@$tahun;
        $nilai = null;
        if(Schema::hasTable($tablename)){
            $nilai = DB::table($tablename)->distinct('id_bidang')->pluck('id_bidang')->toArray();
        }
        return response()->json(['sektor' => $statistik, 'nilai' => $nilai], 200);
    }
    
    public function delete_statistik(Request $request){
        $id = @$request->sektor;
        $tahun = @$request->tahun;

        $bidang = $this->bidangRepository->findWithoutFail($id);
        if(empty($bidang)){
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

        $tablename = 'nomenklatur_amount_'.@$tahun;
        if(!Schema::hasTable($tablename)){
            return response()->json(['valid' => true, 'message' => 'Data '.@$tahun.' already deleted'], 200);
        }

        DB::table($tablename)->where('id_bidang',$id)->delete();
        return response()->json(['valid' => true, 'message' => 'Data Statistik successfully deleted'], 200);
    }
    
    public function delete_upload_statistik(Request $request){
        // truncate Temp
        Bidangstatistik::truncate();
        return response()->json(['valid' => true, 'message' => 'Data Upload Statistik successfully deleted'], 200);
    }
}
