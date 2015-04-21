<?php

namespace app\modules\mod_docente\models;

use Yii;

/**
 * This is the model class for table "m_docente.dat_aulas".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $edificio
 * @property string $parlantes
 * @property string $infocus
 * @property string $pc
 * @property string $monitor
 * @property string $teclado
 * @property string $mouse
 *
 * @property DatHorario[] $datHorarios
 */
class datAulas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_docente.dat_aulas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'edificio', 'parlantes', 'infocus', 'pc', 'monitor', 'teclado', 'mouse'], 'string']
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
            'edificio' => 'Edificio',
            'parlantes' => 'Parlantes',
            'infocus' => 'Infocus',
            'pc' => 'Pc',
            'monitor' => 'Monitor',
            'teclado' => 'Teclado',
            'mouse' => 'Mouse',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatHorarios()
    {
        return $this->hasMany(DatHorario::className(), ['id_aula' => 'id']);
    }
}
