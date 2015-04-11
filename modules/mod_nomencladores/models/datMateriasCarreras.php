<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_materias_equivalencia".
 *
 * @property integer $id
 * @property string $id_carrera
 * @property string $id_materia
 */
class datMateriasCarreras extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_materias_equivalencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_carrera','id_materia'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_carrera' => 'Id docente',
            'id_materia' => 'Id materia',
        ];
    }

        
    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}