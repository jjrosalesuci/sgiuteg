<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_datos_evaluado".
 *
 * @property integer $id
 * @property integer $id_evaluacion
 * @property integer $id_usuario
 * @property integer $id_carrera
 * @property string $nombre_carrera
 * @property integer $id_asignatura
 * @property string $nombre_asignatura
 * @property integer $id_trabajador
 * @property string $nombre_trabajador
 *
 * @property DatEvaluacionRespuestas[] $datEvaluacionRespuestas
 */
class datDatosEvaluado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_datos_evaluado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_evaluacion', 'id_usuario', 'id_carrera', 'id_asignatura', 'id_trabajador'], 'integer'],
            [['nombre_carrera', 'nombre_asignatura', 'nombre_trabajador'], 'string']
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
            'id_carrera' => 'Id Carrera',
            'nombre_carrera' => 'Nombre Carrera',
            'id_asignatura' => 'Id Asignatura',
            'nombre_asignatura' => 'Nombre Asignatura',
            'id_trabajador' => 'Id Trabajador',
            'nombre_trabajador' => 'Nombre Trabajador',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatEvaluacionRespuestas()
    {
        return $this->hasMany(DatEvaluacionRespuestas::className(), ['id_datos_evaluado' => 'id']);
    }
}
