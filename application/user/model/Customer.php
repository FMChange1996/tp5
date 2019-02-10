<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-01-30
 * Time: 16:14
 */

namespace app\User\model;


use think\Model;
use think\model\concern\SoftDelete;

class Customer extends Model
{
    use SoftDelete;

    protected $table = "customer";

}