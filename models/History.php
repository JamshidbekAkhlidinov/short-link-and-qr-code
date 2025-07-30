<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property int $id
 * @property int|null $data_id
 * @property string|null $ip_address
 * @property string|null $created_at
 *
 * @property Data $data
 */
class History extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_id', 'ip_address', 'created_at'], 'default', 'value' => null],
            [['data_id'], 'integer'],
            [['created_at'], 'safe'],
            [['ip_address'], 'string', 'max' => 255],
            [['data_id'], 'exist', 'skipOnError' => true, 'targetClass' => Data::class, 'targetAttribute' => ['data_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_id' => 'Data ID',
            'ip_address' => 'Ip Address',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Data]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasOne(Data::class, ['id' => 'data_id']);
    }

}
