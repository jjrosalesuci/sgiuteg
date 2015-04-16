<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_evaluacion_pregunta".
 *
 * @property integer $id_evaluacion
 * @property integer $id_pregunta
 * @property integer $resaltar
 */
class datEvaluacionPregunta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_evaluacion_pregunta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_evaluacion', 'id_pregunta'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_evaluacion' => 'Id Evaluacion',
            'id_pregunta' => 'Id Pregunta',
        ];
    }
}
