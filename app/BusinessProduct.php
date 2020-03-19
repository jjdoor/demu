<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\BusinessPort
 *
 * @property int $id
 * @property int $segment_business_id
 * @property int $master_business_id
 * @property int $slaver_business_id
 * @property int $port_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereMasterBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort wherePortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereSegmentBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereSlaverBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessPort whereUserId($value)
 * @property int $product_id
 * @property-read \App\Business $business
 * @property-read \App\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BusinessProduct whereProductId($value)
 */
class  BusinessProduct extends Pivot
{
    protected $table = 'business_product';

    function business()
    {
        return $this->belongsTo(Business::class, "slaver_business_id", "id");
    }

    function product()
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }

//    public function role()
//    {
//        return $this->belongsTo(Role::class);
//    }
//
//    public function department()
//    {
//        return $this->belongsTo(Department::class);
//    }
}
