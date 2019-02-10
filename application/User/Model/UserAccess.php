<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-02-10
 * Time: 15:09
 */

namespace app\User\Model;


use think\Model;

class UserAccess extends Model
{
    protected $table = "auth_group_access";
}