<?php

namespace app\modules\mod_contable\models;

use Yii;

/**
 * This is the model class for table "m_contable.cob_report_recup_postgrado".
 *
 * @property integer $id
 * @property integer $id_reporte
 * @property string $g_fds1
 * @property string $g_fds2
 * @property string $g_fds3
 * @property string $g_fds4
 * @property string $g_fds5
 * @property string $g_fds6
 * @property string $g_fds7
 * @property string $c_fds1
 * @property string $c_fds2
 * @property string $c_fds3
 * @property string $c_fds4
 * @property string $c_fds5
 * @property string $c_fds6
 * @property string $c_fds7
 * @property string $c_e1
 * @property string $c_e2
 * @property string $c_e3
 * @property string $c_e4
 * @property string $c_e5
 * @property string $c_e6
 * @property string $c_e7
 * @property string $c_r1
 * @property string $c_r2
 * @property string $c_r3
 * @property string $c_r4
 * @property string $c_r5
 * @property string $c_r6
 * @property string $c_r7
 * @property string $total_c_fds
 * @property string $total_c_e
 * @property string $total_c_r
 * @property string $recaudacion
 * @property string $fecha_c_rp
 *
 * @property CobReporte $idReporte
 */
class CobReportRecupPostgrado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.cob_report_recup_postgrado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_reporte'], 'integer'],
            [['g_fds1', 'g_fds2', 'g_fds3', 'g_fds4', 'g_fds5', 'g_fds6', 'g_fds7', 'fecha_c_rp'], 'string'],
            [['c_fds1', 'c_fds2', 'c_fds3', 'c_fds4', 'c_fds5', 'c_fds6', 'c_fds7', 'c_e1', 'c_e2', 'c_e3', 'c_e4', 'c_e5', 'c_e6', 'c_e7', 'c_r1', 'c_r2', 'c_r3', 'c_r4', 'c_r5', 'c_r6', 'c_r7', 'total_c_fds', 'total_c_e', 'total_c_r', 'recaudacion'], 'number']
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
            'g_fds1' => 'G Fds1',
            'g_fds2' => 'G Fds2',
            'g_fds3' => 'G Fds3',
            'g_fds4' => 'G Fds4',
            'g_fds5' => 'G Fds5',
            'g_fds6' => 'G Fds6',
            'g_fds7' => 'G Fds7',
            'c_fds1' => 'C Fds1',
            'c_fds2' => 'C Fds2',
            'c_fds3' => 'C Fds3',
            'c_fds4' => 'C Fds4',
            'c_fds5' => 'C Fds5',
            'c_fds6' => 'C Fds6',
            'c_fds7' => 'C Fds7',
            'c_e1' => 'C E1',
            'c_e2' => 'C E2',
            'c_e3' => 'C E3',
            'c_e4' => 'C E4',
            'c_e5' => 'C E5',
            'c_e6' => 'C E6',
            'c_e7' => 'C E7',
            'c_r1' => 'C R1',
            'c_r2' => 'C R2',
            'c_r3' => 'C R3',
            'c_r4' => 'C R4',
            'c_r5' => 'C R5',
            'c_r6' => 'C R6',
            'c_r7' => 'C R7',
            'total_c_fds' => 'Total C Fds',
            'total_c_e' => 'Total C E',
            'total_c_r' => 'Total C R',
            'recaudacion' => 'Recaudacion',
            'fecha_c_rp' => 'Fecha C Rp',
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
