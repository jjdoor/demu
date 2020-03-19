<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ContractCustomerSupplier
 *
 * @property int $id
 * @property int $contract_id
 * @property int $customer_supplier_id
 * @property int $is_invoice 是否结算单位
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier whereCustomerSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier whereIsInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractCustomerSupplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContractCustomerSupplier extends Model
{
    protected $table = 'contract_customer_supplier';
}
