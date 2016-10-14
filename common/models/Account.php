<?php

namespace common\models;

use common\components\arBehaviors\LinkBehavior;
use common\models\gii\BaseAccount;
use common\models\queries\AccountQuery;
use Exception;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * @author Albert Garipov <bert320@gmail.com>
 * 
 * @property string $password
 */
class Account extends BaseAccount implements IdentityInterface
{

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_BLOCKED = 'BLOCKED';
    const ROLE_ADMIN = 'admin';
    const ROLE_GUEST = 'guest';

    private $_password;

    /**
     * @var boolean
     */
    public $sendPassword;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
                [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'createdBy',
                'updatedByAttribute' => 'updatedBy',
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }

    /**
     * @param array $roles
     * @return array
     */
    public static function roleList($roles = false)
    {
        $list = [
            self::ROLE_ADMIN => Yii::t('app', 'Administrator'),
        ];
        return $roles ? array_intersect_key($list, array_flip($roles)) : $list;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['username', 'email', 'role'], 'required'],
                [['username', 'email'], 'unique'],
                [['email', 'username', 'position'], 'string', 'max' => 255],
                ['email', 'email'],
                ['role', 'in', 'range' => array_keys(static::roleList())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_replace(parent::attributeLabels(), [
        ]);
    }

    /**
     * Returns an account of current authorized user.
     * @param string $role Use account's ROLE_* constants.
     * @return Account
     * @throws Exception if the model's role doesn't match to the specified role.
     */
    public static function current($role = null)
    {
        $account = Yii::$app->getUser()->getIdentity();
        if ($account === null) {
            throw new Exception('Guest user!');
        }
        if ($role !== null && $role !== $account->role) {
            throw new Exception('Role doesn\'t match.');
        }
        return $account;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds account by email
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($username)
    {
        return static::findOne(['email' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if ($this->passwordHash) {
            return Yii::$app->security->validatePassword($password, $this->passwordHash);
        } else {
            return $password === $this->publicPassword;
        }
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;

        // change hash if the password is specified
        if (strlen($password)) {
            $this->passwordHash = Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * Returns password if it was previously set.
     * @param string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

}