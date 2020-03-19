<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PriceMaintain
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PriceMaintainData[] $price_maintain_data
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceMaintain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceMaintain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceMaintain query()
 * @mixin \Eloquent
 */
class PriceMaintain extends Model
{
	protected $table="price_maintain";

   function price_maintain_data()
    {
        return $this->hasMany(PriceMaintainData::class, 'price_maintain_id', 'id');
    }

    function users()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }



}
