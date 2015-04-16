<?php

namespace app\modules\mod_contable\models;

use Yii;

/**
 * This is the model class for table "m_contable.cob_report_tarjetas_credito".
 *
 * @property integer $id
 * @property integer $id_reporte
 * @property string $f_ap_dep1
 * @property string $v_ap_rec1
 * @property string $f_ap_dep2
 * @property string $f_ap_dep3
 * @property string $f_ap_dep4
 * @property string $f_ap_dep5
 * @property string $f_ap_dep6
 * @property string $f_ap_dep7
 * @property string $v_ap_rec2
 * @property string $v_ap_rec3
 * @property string $v_ap_rec4
 * @property string $v_ap_rec5
 * @property string $v_ap_rec6
 * @property string $v_ap_rec7
 * @property string $total
 *
 * @property CobReporte $idReporte
 */
class CobReportTarjetasCredito extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.cob_report_tarjetas_credito';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_reporte'], 'integer'],
            [['f_ap_dep1', 'f_ap_dep2', 'f_ap_dep3', 'f_ap_dep4', 'f_ap_dep5', 'f_ap_dep6', 'f_ap_dep7'], 'safe'],
            [['v_ap_rec1', 'v_ap_rec2', 'v_ap_rec3', 'v_ap_rec4', 'v_ap_rec5', 'v_ap_rec6', 'v_ap_rec7', 'total'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_reporte' => 'Id Reporte',
            'f_ap_dep1' => 'F Ap Dep1',
            'v_ap_rec1' => 'V Ap Rec1',
            'f_ap_dep2' => 'F Ap Dep2',
            'f_ap_dep3' => 'F Ap Dep3',
            'f_ap_dep4' => 'F Ap Dep4',
            'f_ap_dep5' => 'F Ap Dep5',
            'f_ap_dep6' => 'F Ap Dep6',
            'f_ap_dep7' => 'F Ap Dep7',
            'v_ap_rec2' => 'V Ap Rec2',
            'v_ap_rec3' => 'V Ap Rec3',
            'v_ap_rec4' => 'V Ap Rec4',
            'v_ap_rec5' => 'V Ap Rec5',
            'v_ap_rec6' => 'V Ap Rec6',
            'v_ap_rec7' => 'V Ap Rec7',
            'total' => 'Total',
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
