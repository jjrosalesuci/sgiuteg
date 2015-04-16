<?php

namespace app\modules\mod_contable\models;

use Yii;

/**
 * This is the model class for table "m_contable.cob_reporte".
 *
 * @property integer $id
 * @property string $fecha
 *
 * @property CobDesPSemanales[] $cobDesPSemanales
 * @property CobReportTarjetasCredito[] $cobReportTarjetasCreditos
 * @property CobCartGrado[] $cobCartGrados
 * @property CobReportCaja[] $cobReportCajas
 * @property CobReportRecupPostgrado[] $cobReportRecupPostgrados
 */
class CobReporte extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.cob_reporte';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha'], 'safe']
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCobDesPSemanales()
    {
        return $this->hasMany(CobDesPSemanales::className(), ['id_reporte' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCobReportTarjetasCreditos()
    {
        return $this->hasMany(CobReportTarjetasCredito::className(), ['id_reporte' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCobCartGrados()
    {
        return $this->hasMany(CobCartGrado::className(), ['id_reporte' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCobReportCajas()
    {
        return $this->hasMany(CobReportCaja::className(), ['id_reporte' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCobReportRecupPostgrados()
    {
        return $this->hasMany(CobReportRecupPostgrado::className(), ['id_reporte' => 'id']);
    }
}
