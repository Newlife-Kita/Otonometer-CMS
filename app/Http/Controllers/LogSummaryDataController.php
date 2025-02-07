<?php

namespace App\Http\Controllers;

use App\Models\Bidangnilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogSummaryDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:logUpload-edit', ['only' => ['edit']]);
        $this->middleware('can:logUpload-store', ['only' => ['store']]);
        $this->middleware('can:logUpload-show', ['only' => ['show']]);
        $this->middleware('can:logUpload-update', ['only' => ['update']]);
        $this->middleware('can:logUpload-delete', ['only' => ['delete']]);
        $this->middleware('can:logUpload-create', ['only' => ['create']]);
    }

    public function index()
    {

        $databaseName = config('database.connections.mysql.database');
        $pattern = '%nomenklatur_amount_2%';

        // Step 1: Get the list of table names that match the pattern
        $tableNames = DB::table('information_schema.tables')
            ->select('table_name')
            ->where('table_schema', $databaseName)
            ->where('table_name', 'LIKE', $pattern)
            ->get();

        $totalRowCount = 0;
        $totalTables = $tableNames->count();

        $label = [];
        $data = [
            'keuangan' => [],
            'ekonomi' => [],
            'statistik' => [],
        ];

        $keuanganSector = $this->getSector('KEUANGAN');
        $ekonomiSector = $this->getSector('EKONOMI');
        $statistikSector = $this->getSector('STATISTIK');

        // Step 2: Loop through each table and count the rows
        foreach ($tableNames as $table) {

            $tableName = $table->TABLE_NAME;
            $pattern = '/^nomenklatur_amount_(\d{4})$/';

            if (preg_match($pattern, $tableName, $matches)) {
                $label[] = $matches[1];
            }

            // Step 3: Count the rows in the current table
            $rowCount = DB::table($tableName)->count();

            $data['keuangan'][] = DB::table($tableName)->whereIn('id_bidang', $keuanganSector)->count();
            $data['ekonomi'][] = DB::table($tableName)->whereIn('id_bidang', $ekonomiSector)->count();
            $data['statistik'][] = DB::table($tableName)->whereIn('id_bidang', $statistikSector)->count();


            // Add the row count to the total count
            $totalRowCount += $rowCount;
        }


        return view('logsummarydata.index')->with('countData', $totalRowCount)
            ->with('totalTables', $totalTables)
            ->with('label', $label)
            ->with('data', $data);
    }

    /**
     * 
     * @param string $tipe : "KEUANGAN", "EKONOMI", "STATISTIK"
     */
    private function getSector(string $tipe): array
    {
        $rootId = $tipe === 'KEUANGAN' ? 1 : ($tipe === 'EKONOMI' ? 2 : 3);


        $sql = "
            WITH RECURSIVE cte(id, id_parent) AS (
                SELECT id, id_parent
                FROM master_bidang
                WHERE id = ?
                UNION ALL
                SELECT n.id, n.id_parent
                FROM master_bidang n JOIN cte ON n.id_parent = cte.id
            )
            SELECT id FROM cte
        ";

        $sectorIds = DB::select($sql, [$rootId]);

        $sectorIds = collect($sectorIds)->pluck('id')->toArray();

        return $sectorIds;
    }
}
