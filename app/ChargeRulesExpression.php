<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ChargeRulesExpression
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChargeRule query()
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRulesExpression onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRulesExpression withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ChargeRulesExpression withoutTrashed()
 */
class ChargeRulesExpression extends Model
{
    use SoftDeletes;
    protected $table = 'charge_rules_expression';

}
