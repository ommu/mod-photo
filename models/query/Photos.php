<?php
/**
 * Photos
 *
 * This is the ActiveQuery class for [[\app\modules\album\models\Photos]].
 * @see \app\modules\album\models\Photos
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 6 January 2020, 01:24 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

namespace app\modules\album\models\query;

class Photos extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published() 
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted() 
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \app\modules\album\models\Photos[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \app\modules\album\models\Photos|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
