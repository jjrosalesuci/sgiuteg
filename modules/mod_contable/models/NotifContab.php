<?php

namespace app\modules\mod_contable\models;

use Yii;


/**
 * This is the model class for table "m_contable.dat_conf_notificaciones".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $correo
 * @property string $modulo
 */
class NotifContab extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.dat_conf_notificaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'correo' => 'Correo',
        ];
    }
}
