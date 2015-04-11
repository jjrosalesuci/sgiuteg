<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_grupo_preguntas".
 *
 * @property integer $id
 * @property string $nombre
 */
class DatGrupo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_grupo_preguntas';
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
        ];
    }
}
