<?php

namespace app\modules\mod_nomencladores\models;

use Yii;

/**
 * This is the model class for table "sa_materia_periodo_lectivo".
 *
 * @property integer $id
 * @property string $id_docente
 * @property string $id_materia
 */
class datDocentesMaterias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sa_materia_periodo_lectivo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_docente','id_materia'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_docente' => 'Id docente',
            'id_materia' => 'Id materia',
        ];
    }

        
    public static function getDb()
    {
        return \Yii::$app->db_siga;
    }
}