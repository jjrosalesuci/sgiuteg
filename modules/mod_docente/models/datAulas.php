<?php

namespace app\modules\mod_docente\models;

use Yii;

/**
 * This is the model class for table "m_docente.dat_aulas".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $edificio
 */
class datAulas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_docente.dat_aulas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'edificio'], 'string']
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
            'edificio' => 'Edificio',
        ];
    }
}
