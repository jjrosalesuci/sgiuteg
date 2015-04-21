<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_grupo_parcial".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $anio
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $estatus
 * @property string $fecha_cierre 
 * @property string $id_matriz 
 * @property string $ambito 
 */
class datSemestres extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_periodo_lectivo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre','descripcion','anio','fecha_inicio','fecha_fin','estatus','fecha_cierre','id_matriz','ambito' ], 'string']
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
            'descripcion' => 'Descripcion',
            'anio' => 'Anio',
            'fecha_inicio' => 'Fecha inicio',
            'fecha_fin' => 'Fecha Fin',
            'estatus' => 'Estatus',
            'fecha_cierre' => 'Fecha Cierre',
            'id_matriz' => 'ID Matriz',
            'ambito' => 'Ambito',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}
