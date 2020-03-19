<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReviewLog
 *
 * @property int $id
 * @property string $model 审批模块名称
 * @property string $name 审批名称
 * @property int $foreign_key 外键，model表对应的自增id
 * @property int $role_id 审核者角色
 * @property int|null $user_id 审核者
 * @property string $suggestion 意见建议，status=1表示对下一步的建议,status=-1表示对上一步的建议
 * @property int $status 审核状态 -1:退签，0:未审核，草稿状态，1:同意
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereForeignKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereSuggestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReviewLog whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Role $roles
 * @property-read \App\User $users
 */
class ReviewLog extends Model
{
    function users(){
        return $this->hasOne(User::class,'id','user_id');
    }
    function roles(){
        return $this->hasOne('\App\Role','id','role_id');
    }
}
