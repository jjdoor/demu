<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This is the model class for table "container_types".
 *
 * @property string $id
 * @property string $name
 * @property string $size 箱型,就是尺寸
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContainerType whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\ContainerType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ContainerType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ContainerType withoutTrashed()
 */
class ContainerType extends Model
{
	 use SoftDeletes;

}
