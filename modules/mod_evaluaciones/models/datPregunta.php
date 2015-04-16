<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_pregunta".
 *
 * @property integer $id_pregunta
 * @property string $texto
 * @property integer $tipo
 * @property integer $id_g_pregunta
 * @property string $opciones
 *
 * @property DatEvaluacionPregunta[] $datEvaluacionPreguntas
 * @property DatEvaluacionRespuestas[] $datEvaluacionRespuestas
 */
class datPregunta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_pregunta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['texto', 'opciones'], 'string'],
            [['tipo', 'id_g_pregunta'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_pregunta' => 'Id Pregunta',
            'texto' => 'Texto',
            'tipo' => 'Tipo',
            'id_g_pregunta' => 'Id G Pregunta',
            'opciones' => 'Opciones',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatEvaluacionPreguntas()
    {
        return $this->hasMany(DatEvaluacionPregunta::className(), ['id_pregunta' => 'id_pregunta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatEvaluacionRespuestas()
    {
        return $this->hasMany(DatEvaluacionRespuestas::className(), ['id_pregunta' => 'id_pregunta']);
    }
}
