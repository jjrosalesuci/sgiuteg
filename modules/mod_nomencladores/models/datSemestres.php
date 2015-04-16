<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_grupo_parcial".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $orden
 * @property string $n_parciales
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $estatus
 * @property string $id_periodo_lectivo 
 */
class datSemestres extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_grupo_parcial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre','orden','n_parciales','fecha_inicio','fecha_fin','estatus','id_periodo_lectivo' ], 'string']
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
            'orden' => 'Orden',
            'n_parciales' => 'N_parciales',
            'fecha_inicio' => 'Fecha inicio',
            'fecha_fin' => 'Fecha Fin',
            'estatus' => 'Estatus',
            'id_periodo_lectivo ' => 'Id periodo lectivo',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}
