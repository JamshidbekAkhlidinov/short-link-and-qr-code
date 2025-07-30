<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "data".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $url
 * @property string|null $code
 * @property string|null $qr_file
 * @property int|null $count
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property History[] $histories
 */
class Data extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => date('Y-m-d H:i:s'),
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'code', 'qr_file', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['count'], 'default', 'value' => 0],
            [['count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'url', 'qr_file'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'code' => 'Code',
            'qr_file' => 'Qr File',
            'count' => 'Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Histories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistories()
    {
        return $this->hasMany(History::class, ['data_id' => 'id']);
    }

}
