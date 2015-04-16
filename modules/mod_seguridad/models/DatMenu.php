<?php

namespace app\modules\mod_seguridad\models;

use Yii;

/**
 * This is the model class for table "m_arquitectura.dat_menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $link
 * @property integer $id_padre
 * @property integer $ancho
 * @property integer $alto
 *
 * @property DatMenu $id0
 * @property DatMenu $datMenu
 * @property DatRolAcceso[] $datRolAccesos
 */
class DatMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_arquitectura.dat_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_padre','ancho','alto'], 'integer'],
            [['name', 'link'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'link' => 'Link',
            'id_padre' => 'Id Padre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(DatMenu::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatMenu()
    {
        return $this->hasOne(DatMenu::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatRolAccesos()
    {
        return $this->hasMany(DatRolAcceso::className(), ['id_menu_item' => 'id']);
    }
}
