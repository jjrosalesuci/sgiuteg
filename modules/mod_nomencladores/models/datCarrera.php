<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_carrera".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $cod_legal
 * @property string $nombre
 * @property string $descripcion
 * @property string $estatus
 * @property string $fecha_cierre
 * @property string $modalidad
 * @property string $tipo_modalidad
 * @property string $id_unidad 
 */
class datCarrera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_carrera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre','cod_legal','descripcion','estatus','fecha_cierre','modalidad','tipo_modalidad','id_unidad' ], 'string']
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
            'cod_legal' => 'Codigo legal',
            'descripcion' => 'Descripcion',
            'estatus' => 'Estatus',
            'fecha_cierre' => 'Fecha cierre',
            'modalidad' => 'Modalidad',
            'tipo_modalidad' => 'Tipo modalidad',
            'id_unidad ' => 'Id unidad',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}
