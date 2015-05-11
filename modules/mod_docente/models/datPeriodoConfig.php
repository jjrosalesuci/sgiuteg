<?php

namespace app\modules\mod_docente\models;

use Yii;

/**
 * This is the model class for table "m_docente.dat_periodo_config".
 *
 * @property integer $id_periodo
 * @property string $tipo
 * @property integer $id
 */
class datPeriodoConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_docente.dat_periodo_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_periodo'], 'required'],
            [['id_periodo'], 'integer'],
            [['tipo'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_periodo' => 'Id Periodo',
            'tipo' => 'Tipo',
            'id' => 'ID',
        ];
    }
}
