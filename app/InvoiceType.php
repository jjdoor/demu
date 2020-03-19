<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * This is the model class for table "invoice_types".
 *
 * @property string $id
 * @property string $direction 出和入
 * @property string $name 开票类型
 * @property int $tax_rate 税率
 * @property int $user_id 录入人
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\InvoiceType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceType whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\InvoiceType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\InvoiceType withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\User $users
 */
class InvoiceType extends Model
{
    use SoftDeletes;

    function users(){
        return $this->belongsTo(User::class,"user_id","id");
    }
}
