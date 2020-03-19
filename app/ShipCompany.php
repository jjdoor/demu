<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * This is the model class for table "ship_companies".
 *
 * @property string $id
 * @property string $name
 * @property int $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ShipCompany onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ShipCompany withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ShipCompany withoutTrashed()
 * @mixin \Eloquent
 * @property int $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ShipCompany[] $ship_companies
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ShipCompany whereParentId($value)
 */
class ShipCompany extends Model
{
    use SoftDeletes;
    protected $attributes = [
        'status'=>1,
    ];

    function ship_companies(){
        return $this->hasMany(ShipCompany::class,'parent_id','id');
    }
}
