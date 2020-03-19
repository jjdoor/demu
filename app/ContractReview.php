<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ContractReview
 *
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractReview query()
 * @mixin \Eloquent
 */
class ContractReview extends Model
{
//    use SoftDeletes;
    protected $attributes = [
        'result'=>0,

    ];

    function users(){
        return $this->hasOne(User::class,'id','users_id');
    }
}
