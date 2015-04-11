<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_alumno".
 *
 * @property integer $id
 * @property string $cedula
 * @property string $matricula
 * @property string $nombre
 * @property string $apellido 
 * @property string $nacionalidad
 * @property string $id_provincia
 * @property string $id_canton
 * @property string $id_carrera
 * @property string $genero
 * @property string $id_modalidad
 * @property string $organiz_cp
 * @property string $beneficio_beca_c
 * @property string $beneficio_beca_p
 * @property string $beneficio_ay_finan
 * @property string $beneficio_credito_ies
 * @property string $beneficio_credito_iece
 * @property string $fecha_inicio_estudios
 * @property string $apellido_materno
 * @property string $fecha_nacimiento
 * @property string $categoria_definicion
 * @property string $direccion_trabajo
 * @property string $email
 * @property string $email_uteg
 * @property string $direccion
 * @property string $telefono
 * @property string $civil
 * @property string $estatus
 * @property string $fecha_ingreso
 * @property string $id_representante
 * @property string $id_acl_user
 * @property string $user
 * @property string $anio_gradua
 * @property string $colegio
 * @property string $id_provincia_cole
 * @property string $llave
 * @property string $validado 
 */
class datAlumnos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_alumno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre','cedula','matricula','apellido','nacionalidad','id_provincia','id_canton','id_carrera','genero','id_modalidad','organiz_cp','beneficio_beca_c','beneficio_beca_p','beneficio_ay_finan','beneficio_credito_ies','beneficio_credito_iece','fecha_inicio_estudios','apellido_materno','fecha_nacimiento','categoria_definicion','direccion_trabajo','email','email_uteg','direccion','telefono','civil','estatus','fecha_ingreso','id_representante','id_acl_user','user','anio_gradua','colegio','id_provincia_cole','llave','validado'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cedula' => 'Cedula',
            'matricula' => 'Matricula',
            'nombre' => 'Nombre',
            'apellido ' => 'Apellido',
            'nacionalidad' => 'Nacionalidad',
            'id_provincia' => 'Id_provincia',
            'id_canton' => 'Id_canton',
            'id_carrera' => 'Id_carrera',
            'genero' => 'Genero',
            'id_modalidad' => 'Id_modalidad',
            'organiz_cp' => 'Organiz_cp',
            'beneficio_beca_c' => 'Beneficio_beca_c',
            'beneficio_beca_p' => 'Beneficio_beca_p',
            'beneficio_ay_finan' => 'Beneficio_ay_finan',
            'beneficio_credito_ies' => 'Beneficio_credito_ies',
            'beneficio_credito_iece' => 'Beneficio_credito_iece',
            'fecha_inicio_estudios' => 'Fecha_inicio_estudios',
            'apellido_materno' => 'Apellido_materno',
            'fecha_nacimiento' => 'Fecha_nacimiento',
            'direccion_trabajo' => 'Direccion_trabajo',
            'email' => 'Email',
            'email_uteg' => 'Email_uteg',
            'direccion' => 'Direccion',
            'telefono' => 'Telefono',
            'civil' => 'Civil',
            'estatus' => 'Estatus',
            'fecha_ingreso' => 'Fecha_ingreso',
            'id_representante' => 'Id_representante',
            'id_acl_user' => 'Id_acl_user',
            'user' => 'User',
            'anio_gradua' => 'Año graduacion',
            'colegio' => 'Colegio',
            'id_provincia_cole' => 'Id_provincia_cole',
            'llave' => 'Llave',
            'validado ' => 'Validado',
        ];
    }

        
    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}
