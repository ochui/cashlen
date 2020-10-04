<?php

namespace app\models\activerecord;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[BackgroundTasks]].
 *
 * @see BackgroundTasks
 */
class BackgroundTasksQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return BackgroundTasks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return BackgroundTasks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
