<?php

namespace app\modules\mod_docente\models;

use Yii;

/**
 * This is the model class for table "m_docente.dat_asistencia".
 *
 * @property integer $id
 * @property integer $id_turno
 * @property string $fecha
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property integer $id_docent_sup
 * @property string $ip_inicio
 * @property string $ip_fin
 */
class datAsistencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_docente.dat_asistencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_turno', 'id_docent_sup'], 'integer'],
            [['fecha', 'hora_inicio', 'hora_fin'], 'safe'],
            [['ip_inicio', 'ip_fin'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_turno' => 'Id Turno',
            'fecha' => 'Fecha',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
            'id_docent_sup' => 'Id Docent Sup',
            'ip_inicio' => 'Ip Inicio',
            'ip_fin' => 'Ip Fin',
        ];
    }
}
