<?php

namespace app\modules\mod_docente\models;

use Yii;

/**
 * This is the model class for table "m_docente.dat_horario".
 *
 * @property integer $id
 * @property integer $id_materia
 * @property string $nombre_materia
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property integer $id_docente
 * @property string $nombre_docente
 * @property integer $id_aula
 * @property string $dia_semana
 * @property integer $id_trimestre
 * @property integer $id_acl_user
 * @property string $modalidad
 * @property string $tipo_docente
 */
class datHorario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_docente.dat_horario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_materia', 'id_docente', 'id_aula', 'id_trimestre', 'id_acl_user'], 'integer'],
            [['nombre_materia', 'nombre_docente', 'dia_semana', 'modalidad', 'tipo_docente'], 'string'],
            [['hora_inicio', 'hora_fin'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_materia' => 'Id Materia',
            'nombre_materia' => 'Nombre Materia',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
            'id_docente' => 'Id Docente',
            'nombre_docente' => 'Nombre Docente',
            'id_aula' => 'Id Aula',
            'dia_semana' => 'Dia Semana',
            'id_trimestre' => 'Id Trimestre',
            'id_acl_user' => 'Id Acl User',
            'modalidad' => 'Modalidad',
            'tipo_docente' => 'Tipo Docente',
        ];
    }
}
