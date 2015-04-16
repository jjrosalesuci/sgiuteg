<?php

namespace app\modules\mod_seguridad\models;

use Yii;

/**
 * This is the model class for table "m_arquitectura.dat_rol_acceso".
 *
 * @property integer $id_rol
 * @property integer $id_menu_item
 *
 * @property DatRol $idRol
 * @property DatMenu $idMenuItem
 */
class DatRolAcceso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_arquitectura.dat_rol_acceso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_rol', 'id_menu_item'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_rol' => 'Id Rol',
            'id_menu_item' => 'Id Menu Item',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRol()
    {
        return $this->hasOne(DatRol::className(), ['id_rol' => 'id_rol']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMenuItem()
    {
        return $this->hasOne(DatMenu::className(), ['id' => 'id_menu_item']);
    }
}
