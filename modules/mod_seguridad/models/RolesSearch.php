<?php

namespace app\modules\mod_seguridad\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\mod_seguridad\models\Roles;

/**
 * RolesSearch represents the model behind the search form about `app\modules\mod_seguridad\models\Roles`.
 */
class RolesSearch extends Roles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_rol'], 'integer'],
            [['nombre'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Roles::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_rol' => $this->id_rol,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre]);

        return $dataProvider;
    }
}
