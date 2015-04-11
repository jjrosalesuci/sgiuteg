<?php

namespace app\modules\mod_contable\models;

use Yii;

/**
 * This is the model class for table "m_contable.resumen_saldos".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $disponible
 * @property string $contable
 * @property string $girados
 * @property string $no_entregados
 * @property string $diferido
 * @property string $saldo
 * @property string $tc
 * @property string $sobre_giro_otorgado
 * @property string $fecha
 * @property string $hora
 */
class ResumenSaldos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_contable.resumen_saldos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'hora','disponible', 'contable', 'girados', 'no_entregados', 'diferido', 'saldo', 'tc', 'sobre_giro_otorgado'], 'safe'],
            [['nombre'], 'string', 'max' => 100]
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
            'disponible' => 'Disponible',
            'contable' => 'Contable',
            'girados' => 'Girados',
            'no_entregados' => 'No Entregados',
            'diferido' => 'Diferido',
            'saldo' => 'Saldo',
            'tc' => 'Tc',
            'sobre_giro_otorgado' => 'Sobre Giro Otorgado',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
        ];
    }
}
