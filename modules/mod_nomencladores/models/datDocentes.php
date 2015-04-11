<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_docente".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $cedula
 * @property string $ruc
 * @property string $segundo_nombre
 * @property string $apellido
 * @property string $apellido_materno
 * @property string $direccion_domicilio
 * @property string $telefono_domicilio
 * @property string $direccion_trabajo
 * @property string $telefono_trabajo
 * @property string $telefono_celular
 * @property string $email
 * @property string $titulo_tn
 * @property string $titulo_cn
 * @property string $universidad_titulo_cn
 * @property string $nivel_titulo_cn
 * @property string $pais_titulo_cn  
 */
class datDocentes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_docente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre','cedula','ruc','segundo_nombre','apellido','apellido_materno','direccion_domicilio','telefono_domicilio','direccion_trabajo','telefono_trabajo','telefono_celular','email','titulo_tn','titulo_cn','universidad_titulo_cn','nivel_titulo_cn','pais_titulo_cn'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'cedula' => 'Cedula',
            'ruc' => 'Ruc',
            'segundo_nombre' => 'Segundo Nombre',
            'apellido' => 'Apellido',
            'apellido_materno' => 'Apellido materno',
            'direccion_domicilio' => 'Direccion domicilio',
            'telefono_domicilio' => 'Telefono domicilio',
            'direccion_trabajo' => 'Direccion trabajo',
            'telefono_trabajo' => 'Telefono trabajo',
            'telefono_celular' => 'Telefono celular',
            'email' => 'Email',
            'titulo_tn' => 'Titulo tn',
            'titulo_cn' => 'Titulo cn',
            'universidad_titulo_cn' => 'Universidad titulo cn',
            'nivel_titulo_cn' => 'Nivel titulo cn',
            'pais_titulo_cn' => 'Pais titulo cn',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}
