<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ChargeRule
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule query()
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRulesData onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRulesData withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRulesData withoutTrashed()
 */
class ChargeRulesData extends Model
{
    use SoftDeletes;


}
