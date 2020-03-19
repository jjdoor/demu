<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Attachment
 *
 * @property int $id
 * @property string $name
 * @property string $ext
 * @property int $size
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $model 模块名，一般对应表名
 * @property int $foreign_key 模块名外键
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereForeignKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereModel($value)
 * @property string $original_name 原文件名字
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Attachment whereOriginalName($value)
 */
class Attachment extends Model
{
    //
}
