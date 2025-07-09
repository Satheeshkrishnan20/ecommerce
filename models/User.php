<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $fullname
 * @property string $email
 * @property int $phone
 * @property string $dob
 * @property int $gender
 * @property string $address
 * @property int $pincode
 * @property string $district
 * @property string $state
 * @property int $otp
 * @property int $otp_expiry
 * @property string $password
 * @property int $usertype
 * @property int $is_verified
 * @property string $rbac
 * @property int $status
 */
class User extends \yii\db\ActiveRecord
{
        public $confirm_password;
        public $otp1;
        public $otp2;
        public $otp3;
        public $otp4;
       

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'fullname', 'email', 'phone', 'dob', 'gender', 'address', 'pincode', 'district', 'state','password','confirm_password'], 'required','on'=>'signup'],
            // [['username', 'fullname', 'email', 'phone', 'dob', 'gender', 'address', 'pincode', 'district', 'state', 'otp', 'otp_expiry', 'password','confirm_password', 'usertype', 'is_verified', 'rbac', 'status'], 'required'],
            [['phone', 'pincode', 'otp','usertype', 'is_verified', 'status'], 'integer'],
            [['dob','otp_expiry'], 'safe'],
            [['username', 'fullname', 'email', 'address', 'district', 'state', 'rbac'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 300],
            
            [['confirm_password'],'compare','compareAttribute'=>'password','message' => "Passwords don't match"],
            [['otp1','otp2','otp3','otp4'],'required','on'=>'otp'],
            [['username','password'],'required','on'=>'login'],
            [['username'],'required','on'=>'usersearch'],
            [['username','password'],'required','on'=>'admin'],
            [['username','password'],'required','on'=>'createadmin']
        ];
    }

    public function scenarios(){
        $scenarios=parent::scenarios();
        $scenarios['signup']= ['username', 'fullname', 'email', 'phone', 'dob', 'gender', 'address', 'pincode', 'district', 'state','password','confirm_password'];
        $scenarios['otp']=['otp1','otp2','otp3','otp4'];
        $scenarios['login']=['username','password'];
        $scenarios['usersearch']=['username'];
        $scenarios['admin']=['username','password'];
        $scenarios['createadmin']=['username','password'];
        
        return $scenarios;
    }




    public function getotp(){
        return $this->otp1.$this->otp2.$this->otp3.$this->otp4;
    }




    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'fullname' => 'Fullname',
            'email' => 'Email',
            'phone' => 'Phone',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'address' => 'Address',
            'pincode' => 'Pincode',
            'district' => 'District',
            'state' => 'State',
            'otp' => 'Otp',
            'otp_expiry' => 'Otp Expiry',
            'password' => 'Password',
            'usertype' => 'Usertype',
            'is_verified' => 'Is Verified',
            'rbac' => 'Rbac',
            'status' => 'Status',
            'confirm_password'=>'Confirm Password'
        ];
    }

}
