<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Product
 *
 * @property int $id
 * @property string $name 品名
 * @property int $user_id 操作人
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $master_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $segment_businesses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Business[] $slaver_businesses
 * @property-read \App\User $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SoftDeletes;

    protected $attributes = [
        'status' => 1,
    ];

    function users()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    function segment_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_product', 'product_id', 'segment_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function master_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_product', 'product_id', 'master_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

    function slaver_businesses()
    {
        return $this->belongsToMany(Business::class, 'business_product', 'product_id', 'slaver_business_id')->withPivot('user_id', 'created_at', 'updated_at');
    }

//    public function newPivot(Model $parent, array $attributes, $table, $exists)
//    {
//        if ($parent instanceof Business) {
//            return new BusinessProduct($parent, $attributes, $table, $exists);
//        }
//        return parent::newPivot($parent, $attributes, $table, $exists);
//    }
}
