<?php

namespace App\Repositories;

use App\Models\Member;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class MemberRepository
 * @package App\Repositories
 * @version October 25, 2023, 8:37 am UTC
 *
 * @method Member findWithoutFail($id, $columns = ['*'])
 * @method Member find($id, $columns = ['*'])
 * @method Member first($columns = ['*'])
*/
class MemberRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_sso',
        'name',
        'email',
        'email_verified_at',
        'mobile',
        'mobile_verified_at',
        'image',
        'id_wilayah',
        'password',
        'remember_token'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Member::class;
    }
}
