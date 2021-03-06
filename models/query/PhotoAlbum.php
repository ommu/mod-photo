<?php
/**
 * PhotoAlbum
 *
 * This is the ActiveQuery class for [[\ommu\album\models\PhotoAlbum]].
 * @see \ommu\album\models\PhotoAlbum
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 6 January 2020, 01:23 WIB
 * @link https://github.com/ommu/mod-photo
 *
 */

namespace ommu\album\models\query;

class PhotoAlbum extends \yii\db\ActiveQuery
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
	 * @return \ommu\album\models\PhotoAlbum[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\album\models\PhotoAlbum|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
