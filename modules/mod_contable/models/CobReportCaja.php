<?php

namespace app\modules\mod_contable\models;

use Yii;

/**
 * This is the model class for table "m_contable.cob_report_caja".
 *
 * @property integer $id
 * @property string $fecha_r_c
 * @property string $total_e_c
 * @property integer $id_reporte
 *
 * @property CobReporte $idReporte
 */
class CobReportCaja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.cob_report_caja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_r_c'], 'safe'],
            [['total_e_c'], 'number'],
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
            'fecha_r_c' => 'Fecha R C',
            'total_e_c' => 'Total E C',
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
