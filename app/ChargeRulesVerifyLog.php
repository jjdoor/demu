<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ChargeRulesVerifyLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule query()
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRuleData onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRuleData withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRuleData withoutTrashed()
 */
class ChargeRulesVerifyLog extends Model
{
    use SoftDeletes;


}
