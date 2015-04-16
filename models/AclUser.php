<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table.
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $md5_password
 * @property string $estatus
 */

class AclUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->db_siga; 
    }

}
