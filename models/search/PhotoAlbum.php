<?php
/**
 * PhotoAlbum
 *
 * PhotoAlbum represents the model behind the search form about `app\modules\album\models\PhotoAlbum`.
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 Ommu Platform (www.ommu.co)
 * @created date 6 January 2020, 02:24 WIB
 * @link https://www.ommu.co
 *
 */

namespace app\modules\album\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\album\models\PhotoAlbum as PhotoAlbumModel;

class PhotoAlbum extends PhotoAlbumModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'member_id', 'user_id', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'caption', 'creation_date', 'modified_date', 'updated_date', 'memberDisplayname', 'userDisplayname', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
		if(!($column && is_array($column)))
			$query = PhotoAlbumModel::find()->alias('t');
		else
			$query = PhotoAlbumModel::find()->alias('t')->select($column);
		$query->joinWith([
			// 'member member', 
			// 'user user', 
			// 'creation creation', 
			// 'modified modified'
		]);
		if((isset($params['sort']) && in_array($params['sort'], ['memberDisplayname', '-memberDisplayname'])) || (isset($params['memberDisplayname']) && $params['memberDisplayname'] != ''))
			$query = $query->joinWith(['member member']);
		if((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || (isset($params['userDisplayname']) && $params['userDisplayname'] != ''))
			$query = $query->joinWith(['user user']);
		if((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != ''))
			$query = $query->joinWith(['creation creation']);
		if((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != ''))
			$query = $query->joinWith(['modified modified']);

		$query = $query->groupBy(['id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['memberDisplayname'] = [
			'asc' => ['member.displayname' => SORT_ASC],
			'desc' => ['member.displayname' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		if(Yii::$app->request->get('id'))
			unset($params['id']);
		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.member_id' => isset($params['member']) ? $params['member'] : $this->member_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.caption', $this->caption])
			->andFilterWhere(['like', 'member.displayname', $this->memberDisplayname])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
