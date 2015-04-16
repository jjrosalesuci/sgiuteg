<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_materia".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $cod_legal
 * @property string $creditos
 * @property string $horasXsemana
 * @property string $estatus
 * @property string $fecha_cierre
 * @property string $id_categoria 
 */
class datAsignatura extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_materia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre','cod_legal','creditos','horasXsemana','estatus','fecha_cierre','id_categoria'], 'string']
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
            'creditos' => 'Creditos',
            'horasXsemana' => 'Horas por semana',
            'estatus' => 'Estatus',
            'fecha_cierre' => 'Fecha cierre',
            'id_categoria ' => 'Id categoria',
        ];
    }

        
    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}
