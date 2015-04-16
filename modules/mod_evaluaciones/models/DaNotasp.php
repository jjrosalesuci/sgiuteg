<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_grupo_preguntas".
 *
 * @property integer $id
 * @property string $nombre
 */
class DaNotasp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_trabajador_dat_evaluacion_dat_pregunta_nota';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {      
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
