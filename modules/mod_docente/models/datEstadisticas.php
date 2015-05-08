<?php

namespace app\modules\mod_docente\models;

use Yii;

/**
 * This is the model class for table "m_docente.dat_estadisticas".
 *
 * @property integer $id
 * @property integer $id_horario
 * @property string $minutos_atrasos
 * @property string $minutos_salidas_ah
 * @property string $horas_trabajadas
 * @property string $horas_faltas
 * @property string $horas_reemplazo
 * @property integer $id_docente
 * @property integer $id_tri
 */
class datEstadisticas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_docente.dat_estadisticas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_horario', 'id_docente', 'id_tri'], 'integer'],
            [['minutos_atrasos', 'minutos_salidas_ah', 'horas_trabajadas', 'horas_faltas', 'horas_reemplazo'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_horario' => 'Id Horario',
            'minutos_atrasos' => 'Minutos Atrasos',
            'minutos_salidas_ah' => 'Minutos Salidas Ah',
            'horas_trabajadas' => 'Horas Trabajadas',
            'horas_faltas' => 'Horas Faltas',
            'horas_reemplazo' => 'Horas Reemplazo',
            'id_docente' => 'Id Docente',
            'id_tri' => 'Id Tri',
        ];
    }
}
