<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_alumno_materia".
 *
 * @property integer $id
 * @property string $id_alumno
 * @property string $id_materia
 */
class datAlumnosMaterias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_alumno_materia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_alumno','id_materia'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_alumno' => 'Id alumno',
            'id_materia' => 'Id materia',
        ];
    }

        
    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}