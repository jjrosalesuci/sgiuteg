<?php

namespace app\modules\mod_biblioteca\models;

use Yii;

/**
 * This is the model class for table "m_biblioteca.dat_prestamos".
 *
 * @property integer $id
 * @property string $fecha_e
 * @property string $fecha_d
 * @property integer $id_alumno
 * @property integer $id_materia_alumno
 * @property string $titulo_libro
 * @property integer $id_docente
 * @property integer $id_carrera
 * @property string $nombre_alumno
 * @property string $nombre_docente
 * @property string $nombre_carrera
 * @property string $nombre_materia
 * @property string $apellido_alumno
 * @property string $estado
 * @property string $email
 * @property string $id_libro
 */
class datPrestamos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_biblioteca.dat_prestamos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_e', 'fecha_d'], 'safe'],
            [['id_libro','id_alumno', 'id_materia_alumno', 'id_docente', 'id_carrera'], 'integer'],
            [['email','titulo_libro', 'nombre_alumno', 'nombre_docente', 'nombre_carrera', 'nombre_materia', 'apellido_alumno','estado'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha_e' => 'Fecha E',
            'fecha_d' => 'Fecha D',
            'id_alumno' => 'Id Alumno',
            'id_materia_alumno' => 'Id Materia Alumno',
            'titulo_libro' => 'Titulo Libro',
            'id_docente' => 'Id Docente',
            'id_carrera' => 'Id Carrera',
            'nombre_alumno' => 'Nombre Alumno',
            'nombre_docente' => 'Nombre Docente',
            'nombre_carrera' => 'Nombre Carrera',
            'nombre_materia' => 'Nombre Materia',
            'apellido_alumno' => 'Apellido Alumno',
            'estado' => 'Estado',
            'email' => 'Email',
            'id_libro' => 'Id Libro',
        ];
    }
}
