<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * 账号模型定义关联
 * Date: 2016/10/20
 * Time: 9:51
 */
namespace Admin\Model;
use Think\Model\RelationModel;
class AccountModel extends RelationModel{
    protected  $_link = array(
        'Seller'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'Seller',
            'foreign_key'   => 'aid',
            'as_fields' => 'name,id:ownid',
        ),
        'Buyer'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'Buyer',
            'foreign_key'   => 'aid',
            'as_fields' => 'name,id:ownid',
        ),
        'Trucker'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'Trucker',
            'foreign_key'   => 'aid',
            'as_fields' => 'name,id:ownid',
        ),
        'Driver'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'Driver',
            'foreign_key'   => 'aid',
            'as_fields' => 'name,id:ownid',
        ),
        'Company'=>array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'Company',
            'foreign_key'   => 'aid',
            'as_fields' => 'name,id:ownid',
        )
    );
}