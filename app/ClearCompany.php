<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ClearCompany
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $user_id 操作员
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ClearCompany onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ClearCompany withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ClearCompany withoutTrashed()
 * @mixin \Eloquent
 * @property string $name_code 助记码
 * @property string $name_abbreviation 简称
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereNameAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClearCompany whereNameCode($value)
 */
class ClearCompany extends Model
{
    protected $attributes = [
        'status'=>1,
    ];
    use SoftDeletes;
//    function CompanyOrganizeHasOne(){
//        return $this->hasOne('\App\ClearCompany','id','id');
//    }

    function users(){
//        return $this->hasOne(User::class,'id','user_id');
        return $this->belongsTo(User::class,'user_id');
    }
}
