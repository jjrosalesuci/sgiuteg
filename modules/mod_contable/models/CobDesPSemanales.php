<?php

namespace app\modules\mod_contable\models;

use Yii;

/**
 * This is the model class for table "m_contable.cob_des_p_semanales".
 *
 * @property integer $id
 * @property string $t_p_p
 * @property string $t_p_sp
 * @property string $t_postgrado
 * @property string $t_o_ingres
 * @property string $total_general
 * @property integer $id_reporte
 * @property string $fecha_descripcion
 *
 * @property CobReporte $idReporte
 */
class CobDesPSemanales extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.cob_des_p_semanales';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['t_p_p', 't_p_sp', 't_postgrado', 't_o_ingres', 'total_general'], 'number'],
            [['id_reporte'], 'integer'],
            [['fecha_descripcion'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            't_p_p' => 'T P P',
            't_p_sp' => 'T P Sp',
            't_postgrado' => 'T Postgrado',
            't_o_ingres' => 'T O Ingres',
            'total_general' => 'Total General',
            'id_reporte' => 'Id Reporte',
            'fecha_descripcion' => 'Fecha Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReporte()
    {
        return $this->hasOne(CobReporte::className(), ['id' => 'id_reporte']);
    }
}
