<?php

namespace App\Import;

use App\Exceptions\ListException;
use App\Models\Bidang;
use App\Models\Bidangekonomi;
use App\Models\Wilayah;
use App\Repositories\BidangRepository;
use App\Repositories\WilayahRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Flash;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BidangekonomiImport implements ToCollection, WithCalculatedFormulas
{
    protected $r = 2; // rows ke bawah di baris berapa
    protected $c = 3; // column ke samping di kolom berapa
    protected $sector;
    protected $region;

    protected $bidangRepo;
    protected $wilayahRepo;
    protected $uploadId;
    protected $uploaderId;
    protected $arrayExcel;

    public function __construct(BidangRepository $bidangRepository, WilayahRepository $wilayahRepository)
    {
        $this->bidangRepo = $bidangRepository;
        $this->wilayahRepo = $wilayahRepository;
        $this->sector = $this->bidangRepo->getChildSectorMap(2);
        $this->region = $wilayahRepository->getRegionMap();
    }

    public function setUploadId($id)
    {
        $this->uploadId = $id;
    }

    public function setUploaderId($id)
    {
        $this->uploaderId = $id;
    }

    public function setArrayExcel(array &$array)
    {
        $this->arrayExcel = &$array;
    }

    private function getColumn(int $col)
    {
        $col++;
        $result = '';
        while ($col > 0) {
            $col--; // Adjust to 0-index
            $result = chr($col % 26 + ord('A')) . $result;
            $col = intval($col / 26);
        }
        return $result;
    }

    private function getRow(int $row)
    {
        return $row + 1;
    }


    public function collection(Collection $rows)
    {
        $mapSector = [];
        $mapRegion = [];
        $checkingSector = true;
        $created_at = Carbon::now('Asia/Jakarta');
        $arrayValue = []; // Initialize arrayValue
        $containException = [];
        for ($i = $this->r; $i < count($rows); $i++) {
            if (!isset($rows[$i][1])) {
                continue;
            }

            $regionCode = number_format((float) $rows[$i][1], 2, '.', '');

            if (!isset($this->region[$regionCode])) {
                $containException[] = "Kode Region Tidak Valid Pada {$this->getColumn(1)}{$this->getRow($i)} : {$rows[$i][1]}";
            }

            if (isset($mapRegion[$regionCode])) {
                $containException[] = "Kode Region Terduplikat Pada {$this->getColumn(1)}{$this->getRow($i)} : {$regionCode}";
            }
            $regionId = $this->region[$regionCode] ?? null;

            if (is_null($regionId)) {
                continue;
            }



            for ($j = $this->c; $j < count($rows[$i]); $j++) {
                if (is_null($rows[0][$j])) {
                    continue;
                }

                if ($checkingSector) {
                    if (!isset($this->sector[$rows[0][$j]])) {
                        $containException[] = "Kode Sektor Tidak Valid Pada {$this->getColumn($j)}{$this->getRow(0)} : {$rows[0][$j]}";
                    }

                    if (isset($mapSector[$rows[0][$j]])) {
                        $containException[] = "Kode Sektor Terduplikat Pada {$this->getColumn($j)}{$this->getRow(0)} : {$rows[0][$j]}";
                    }

                    $mapSector[$rows[0][$j]] = 1;
                }

                // Check whether the value cell and sector code cell is empty or not
                if (is_null($rows[$i][$j])) {
                    continue;
                }

                $sectorId = $this->sector[$rows[0][$j]] ?? null;

                if (is_null($sectorId)) {
                    continue;
                }

                $value = strtolower(trim($rows[$i][$j]));

                if ((!is_numeric($rows[$i][$j]) && !is_null($rows[$i][$j])) && $value !== "na" && $value !== "nan" && $value !== "null" && $value !== "n/a" && $value !== "-" && $value !== "n/a") {

                    $containException[] = "Data bukan numerik Pada {$this->getColumn($j)}{$this->getRow($i)} : {$rows[$i][$j]}";
                    continue;
                }

                $this->arrayExcel[] = [
                    'id_bidang' => $sectorId,
                    'id_wilayah' => $regionId,
                    'nilai' => is_numeric($rows[$i][$j]) ? round($rows[$i][$j], 3) : null,
                ];

                $arrayValue[] = [
                    'nilai' => is_numeric($rows[$i][$j]) ? round($rows[$i][$j], 3) : null,
                    'kode' => $rows[0][$j],
                    'id_bidang' => $sectorId,
                    'id_wilayah' => $regionId,
                    'upload_id' => $this->uploadId,
                    'created_by' => $this->uploaderId,
                    'created_at' => $created_at
                ];
            }

            $checkingSector = false;
        }

        if (count($containException) > 0) {
            throw new ListException("Terdapat beberapa error pada excel.", $containException);
        }


        foreach (array_chunk($arrayValue, 500) as $chunk) {
            Bidangekonomi::insert($chunk);
        }
    }
}
