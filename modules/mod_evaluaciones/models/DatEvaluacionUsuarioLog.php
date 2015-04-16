<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_evaluacion_usuario_log".
 *
 * @property integer $id
 * @property integer $id_evaluacion
 * @property integer $id_usuario
 */
class DatEvaluacionUsuarioLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_evaluacion_usuario_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_evaluacion', 'id_usuario'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_evaluacion' => 'Id Evaluacion',
            'id_usuario' => 'Id Usuario',
        ];
    }
}
