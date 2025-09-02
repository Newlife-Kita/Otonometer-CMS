<?php

namespace App;

use App\Models\Role;
use App\Models\Wilayah;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasRoles;
    use Notifiable;

    protected $guard_name = 'web';
    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'email',
        'password',
        'id_wilayah',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'image' => 'string',
        'remember_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        "email" => "unique:users,email"
    ];

    // public function roles(): BelongsToMany
    // {
    //     return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    // }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function userWilayah()
    {
        return $this->hasOne(Wilayah::class, 'id', 'id_wilayah')->select('nama');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function userWilayahchild()
    {
        return $this->hasOne(Wilayah::class, 'id_parent', 'id_wilayah')->select('id','nama');
    }
}
