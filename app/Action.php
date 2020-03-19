<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Action
 *
 * @property int $id
 * @property int $parent_id
 * @property string $menu_name 显示的菜单名称
 * @property string $name 动作
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereMenuName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Action extends Model
{
    //
}
