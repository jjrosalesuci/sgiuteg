<?php

namespace app\modules\mod_biblioteca\models;

use Yii;

/**
 * This is the model class for table "m_biblioteca.sb_titulos".
 *
 * @property integer $ficha_no
 * @property string $titulo
 * @property string $autor
 * @property string $clasificacion
 * @property string $isbn
 * @property string $num_adqui
 * @property integer $biblioteca
 * @property integer $ejemplar
 */
class datTitulos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_biblioteca.sb_titulos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ficha_no', 'biblioteca', 'ejemplar'], 'integer'],
            [['titulo'], 'string', 'max' => 80],
            [['autor', 'clasificacion'], 'string', 'max' => 50],
            [['isbn'], 'string', 'max' => 20],
            [['num_adqui'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ficha_no' => 'Ficha No',
            'titulo' => 'Titulo',
            'autor' => 'Autor',
            'clasificacion' => 'Clasificacion',
            'isbn' => 'Isbn',
            'num_adqui' => 'Num Adqui',
            'biblioteca' => 'Biblioteca',
            'ejemplar' => 'Ejemplar',
        ];
    }
}
