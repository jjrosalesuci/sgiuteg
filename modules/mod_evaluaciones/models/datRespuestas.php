<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_evaluacion_respuestas".
 *
 * @property integer $id
 * @property integer $id_pregunta
 * @property integer $id_usuario
 * @property string $respuesta
 * @property integer $id_evaluacion
 * @property integer $id_datos_evaluado
 */
class datRespuestas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_evaluacion_respuestas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pregunta', 'id_usuario', 'id_evaluacion'], 'integer'],
            [['respuesta'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pregunta' => 'Id Pregunta',
            'id_usuario' => 'Id Usuario',
            'respuesta' => 'Respuesta',
            'id_evaluacion' => 'Id Evaluacion',
        ];
    }
}
