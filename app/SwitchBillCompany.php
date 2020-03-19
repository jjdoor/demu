<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SwitchBillCompany
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SwitchBillCompany whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SwitchBillCompany extends Model
{
    protected $attributes = [
        'status'=>1,
    ];
}
