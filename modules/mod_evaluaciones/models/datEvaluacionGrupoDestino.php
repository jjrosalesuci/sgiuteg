<?php

namespace app\modules\mod_evaluaciones\models;

use Yii;

/**
 * This is the model class for table "m_evaluaciones.dat_evaluacion_grupo_destino".
 *
 * @property integer $id_evaluacion
 * @property integer $id_grupo_destino
 */
class datEvaluacionGrupoDestino extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_evaluaciones.dat_evaluacion_grupo_destino';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_evaluacion', 'id_grupo_destino'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_evaluacion' => 'Id Evaluacion',
            'id_grupo_destino' => 'Id Grupo Destino',
        ];
    }
}
