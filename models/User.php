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

   public function generateCustomOtpKey() {
    $letters = '';
    $numbers = '';

    
    for ($i = 0; $i < 16; $i++) {
        $letters .= chr(rand(65, 90)); 
    }

  
    for ($i = 0; $i < 8; $i++) {
        $numbers .= rand(0, 9);
    }

   
    $combined = str_shuffle($letters . $numbers);

    return $combined;
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
            $this->usertype=1;
            $otpkey=$this->generateCustomOtpKey();
           $this->otpkey=$otpkey;
            Yii::$app->helper->set('otpkey',Yii::$app->security->generatePasswordHash($otpkey));      

            if (!$this->password) {
                throw new \Exception('Password must be set before sending OTP.');
            }

            $this->password = Yii::$app->security->generatePasswordHash($this->password);

            
            if ($this->save(false)) {
                Helper::send($this->email, 'OTP for Signup', '<b style="font-size:18px;">' . $this->otp . '</b>');
              
                return true;
            }
            return false;

        }

        public function login()
        {
            if (!$this->validate()) {
                return false;
            }

            $user = self::findOne([
                'username' => $this->username,
                'is_verified' => 1,
                'status' => 1,
                'usertype' => 1,
            ]);

            if (!$user || !Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->addError('password', 'Invalid username or password.');
                return false;
            }

            Yii::$app->user->login($user);

            // Set cart count and session data using helper
            $cartCount = Cart::find()->where(['user_id' => $user->id, 'status' => 1])->count();
            Yii::$app->helper->set('cart_item_count', $cartCount);
            Yii::$app->helper->set('username', $user->username);

            return true;
        }

        public function AdminLogin()
        {
            $user = self::findOne(['username' => $this->username]);

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', 'Invalid username or password.');
                return false;
            }

            if ($user->usertype == 1) {
                $this->addError('username', 'Access denied for this user type.');
                return false;
            }

            if (Yii::$app->user->login($user)) {
                // Set RBAC if usertype is 2
                if ($user->usertype == 2) {
                    $rbac = json_decode($user->rbac, true);
                    Yii::$app->helper->set('rbac', is_array($rbac) ? $rbac : []);
                }

                return true;
            }

            $this->addError('username', 'Login failed: could not log in.');
            return false;
        }

        public function createAdmin()
            {
                $this->usertype = 2;              // Admin user
                $this->is_verified = 1;           // Already verified
                $this->status = 1;                // Optional: Mark as active
                $this->password = Yii::$app->security->generatePasswordHash($this->password);

                return $this->save(false);        // You’ve already validated before calling this
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
