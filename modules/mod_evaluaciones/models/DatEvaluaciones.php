<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_evaluaciones".
 *
 * @property integer $id
 * @property string $modalidad
 * @property integer $id_grupo_origen
 * @property integer $id_periodo
 * @property string $fecha
 * @property string $descripcion
 * @property string $estado
 * @property string $titulo
 * @property string $tipo
 * @property string $nombre_periodo
 *
 * @property DatEvaluacionGrupoDestino[] $datEvaluacionGrupoDestinos
 * @property DatEvaluacionPregunta[] $datEvaluacionPreguntas
 * @property DatDatosEvaluado[] $datDatosEvaluados
 */
class DatEvaluaciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_evaluaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_grupo_origen'], 'integer'],
            [['descripcion', 'estado', 'titulo', 'tipo', 'nombre_periodo'], 'string'],
            [['fecha'], 'safe'],
            [['modalidad'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modalidad' => 'Modalidad',
            'id_grupo_origen' => 'Id Grupo Origen',
            'id_periodo' => 'Id Periodo',
            'fecha' => 'Fecha',
            'descripcion' => 'Descripcion',
            'estado' => 'Estado',
            'titulo' => 'Titulo',
            'tipo' => 'Tipo',
            'nombre_periodo' => 'Nombre Periodo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatEvaluacionGrupoDestinos()
    {
        return $this->hasMany(DatEvaluacionGrupoDestino::className(), ['id_evaluacion' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatEvaluacionPreguntas()
    {
        return $this->hasMany(DatEvaluacionPregunta::className(), ['id_evaluacion' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatDatosEvaluados()
    {
        return $this->hasMany(DatDatosEvaluado::className(), ['id_evaluacion' => 'id']);
    }
}
