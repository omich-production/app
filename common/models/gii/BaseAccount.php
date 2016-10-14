<?php

namespace common\models\gii;

use Yii;

/**
 * This is the model class for table "{{%account}}".
 *
 * @property integer $id
 * @property string $role
 * @property string $status
 * @property string $authKey
 * @property string $email
 * @property string $passwordHash
 * @property string $username
 * @property integer $createdBy
 * @property integer $updatedBy
 * @property integer $createdAt
 * @property integer $updatedAt
 */
class BaseAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'authKey', 'passwordHash', 'username', 'createdAt', 'updatedAt'], 'required'],
            [['status'], 'string'],
            [['createdBy', 'updatedBy', 'createdAt', 'updatedAt'], 'integer'],
            [['role'], 'string', 'max' => 31],
            [['authKey', 'username'], 'string', 'max' => 32],
            [['email', 'passwordHash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'email' => Yii::t('app', 'Email'),
            'passwordHash' => Yii::t('app', 'Password Hash'),
            'username' => Yii::t('app', 'Username'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }
}
