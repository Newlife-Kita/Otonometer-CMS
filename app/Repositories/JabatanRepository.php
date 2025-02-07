<?php

namespace App\Repositories;

use App\Models\Jabatan;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class JabatanRepository
 * @package App\Repositories
 * @version October 18, 2023, 9:29 am UTC
 *
 * @method Jabatan findWithoutFail($id, $columns = ['*'])
 * @method Jabatan find($id, $columns = ['*'])
 * @method Jabatan first($columns = ['*'])
 */
class JabatanRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'kode',
        'nama',
        'tipe',
        'tipe_wilayah',
        'urutan'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Jabatan::class;
    }

    public function getArrayJabatan(string $tipe, string $tipe2, bool $komisi = false)
    {

        switch ($tipe2) {
            case 'propinsi':
                $wilayah = 'propinsi';
                break;
            case 'kabupaten':
                $wilayah = 'kabupaten';
                break;
            case 'kota':
                $wilayah = $tipe == 'pemda' ? 'kota' : 'kabupaten';
                break;
            default:
                $wilayah = '';
        }


        $collection = Jabatan::select('id', 'nama')
            ->where('tipe', $tipe)
            ->where('tipe_wilayah', $wilayah)
            ->when($komisi, function ($query) {
                $query->where(function ($query) {
                    $query->where('nama', 'like', '%Komisi%')
                        ->orWhere('nama', 'like', '%Anggota%')
                        ->orWhere('nama', 'like', '%Dinas%');
                });
            })
            ->when(!$komisi, function ($query) {
                $query->where(function ($query) {
                    $query->where('nama', 'not like', '%Komisi%')
                        ->Where('nama', 'not like', '%Anggota%');
                });
            })
            ->get();

        $array = [];

        foreach ($collection as $item) {
            $array[$item['id']] = $item['nama'];
        }

        return $array;
    }
}
