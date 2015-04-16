<?php

namespace app\modules\mod_contable\models;

use Yii;

/**
 * This is the model class for table "m_contable.cob_cart_grado".
 *
 * @property integer $id
 * @property string $fecha
 * @property string $total_p
 * @property string $total_sp
 * @property string $total_cartera
 * @property integer $id_reporte
 *
 * @property CobReporte $idReporte
 */
class CobCartGrado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.cob_cart_grado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha'], 'safe'],
            [['total_p', 'total_sp', 'total_cartera'], 'number'],
            [['id_reporte'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'total_p' => 'Total P',
            'total_sp' => 'Total Sp',
            'total_cartera' => 'Total Cartera',
            'id_reporte' => 'Id Reporte',
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
