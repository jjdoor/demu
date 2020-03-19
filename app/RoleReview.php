<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * App\RoleReview
 *
 * @property int $id
 * @property string $model 模块名称，一般和表名对应
 * @property string|null $name 审批名称
 * @property int $foreign_key 外键,model表对应的自增id
 * @property int|null $preview_role_id 提交审核者角色
 * @property int|null $preview_user_id 提交审核者
 * @property int|null $review_role_id 审核者角色
 * @property int|null $review_user_id 审核者
 * @property string|null $preview_suggestion 提交者建议
 * @property string|null $review_suggestion 审核者建议
 * @property int|null $status null:草稿，-1:退签，0:已提交，未审批，1:审批
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Role $roles
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereForeignKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview wherePreviewRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview wherePreviewSuggestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview wherePreviewUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereReviewRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereReviewSuggestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereReviewUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleReview whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RoleReview extends Model
{
    function users(){
        return $this->hasOne('\App\User','id','user_id');
    }

    function roles(){
        return $this->hasOne('\App\Role','id','role_id');
    }
}
