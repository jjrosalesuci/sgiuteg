<?php

namespace app\modules\mod_seguridad\models;

use Yii;

/**
 * This is the model class for table "m_arquitectura.dat_rol".
 *
 * @property integer $id_rol
 * @property string $nombre
 *
 * @property DatUsuarioRol[] $datUsuarioRols
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_arquitectura.dat_rol';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_rol' => 'Id Rol',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatUsuarioRols()
    {
        return $this->hasMany(DatUsuarioRol::className(), ['id_rol' => 'id_rol']);
    }
}
