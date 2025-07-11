<?php

namespace app\models;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use app\components\Helper;

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
class User extends \yii\db\ActiveRecord implements IdentityInterface
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
            ['phone', 'match', 'pattern' => '/^\d{10}$/', 'message' => 'Phone number must be exactly 10 digits.'],
             ['pincode', 'match', 'pattern' => '/^\d{6}$/', 'message' => 'Pincode must be exactly 6 digits.'],
            [['confirm_password'],'compare','compareAttribute'=>'password','message' => "Passwords don't match"],
            [['otp1','otp2','otp3','otp4'],'required','on'=>'otp'],
            [['username','password'],'required','on'=>'login'],
            [['username'],'required','on'=>'usersearch'],
            [['username','password'],'required','on'=>'admin'],
            [['username','password'],'required','on'=>'createadmin'],
            [['username'], 'unique','on'=>'signup']
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

    public static function findIdentity($id){
            return self::findOne($id);
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Optional if you're not using token-based login (like in APIs)
        return true;
    }

    public function validatePassword($password)
{
    return $this->password === $password;
}

public function hasPermission(string $permission): bool
{
   
    $rbac = is_array($this->rbac) ? $this->rbac : json_decode($this->rbac, true);
    return is_array($rbac) && in_array($permission, $rbac);
}





    public static function getAllUser(){
                return new ActiveDataProvider([
                'query' => self::find()->where([
                    'usertype' => 1,
                    'status' => 1,
                    'is_verified' => 1,
                ]),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
    }
   
    public function getOtp(){
        return $this->otp1.$this->otp2.$this->otp3.$this->otp4;
    }

    public function generateOtp(){
        return rand(1000, 9999);
    }

    public function getRbac()
        {
            return json_decode($this->rbac, true);
        }

    public function sendOtp()
        {
            $this->otp = $this->generateOtp();
            $this->otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));
            $this->is_verified = 0;
            $this->status = 1;

            if (!$this->password) {
                throw new \Exception('Password must be set before sending OTP.');
            }

            $this->password = Yii::$app->security->generatePasswordHash($this->password);

            // Save user without validation (you already validated before calling this)
            if ($this->save(false)) {
                Helper::send($this->email, 'OTP for Signup', '<b style="font-size:18px;">' . $this->otp . '</b>');
                return true;
            }

            return false;
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
