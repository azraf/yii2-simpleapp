<?php

/*
 * This file is part of the Simple project.
 *
 * (c) Simple project <https://github.com/azraf>
 * Project Repository: https://github.com/azraf/yii2-extend
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace azraf\extend;

class SimpleSecurity
{
    /**
     *this values can be accessible from
     * anywhere of the framework, e.g.
     *   $sData = [];
     *   $sData['RoleTag'] = Yii::$app->wtsecure->sessionRoleTag;
     *   $sData['UserInfoTag'] = Yii::$app->wtsecure->sessionUserInfoTag;
     *   $sData['UserNameTag'] = Yii::$app->wtsecure->sessionUserNameTag;
     *   $sData['UserIdTag'] = Yii::$app->wtsecure->sessionUserIdTag;
     *   $sData['LoginTimeTag'] = Yii::$app->wtsecure->sessionLoginTimeTag;
     *   $sData['LoginExprTimeTag'] = Yii::$app->wtsecure->sessionLoginExprTimeTag;
     *   $sData['LoginIpTag'] = Yii::$app->wtsecure->sessionLoginIpTag;
     *   $this->d($sData,'$sData');
     * 
     * 
     * @var type 
     */
    const DATA_POST_HASH = 'cE4H6dEi'; // Change it for security
    
    public $sessionUserNameTag      = 'x1ilJJKy8dgd54s2ds780';
    public $sessionUserIdTag        = 'x1ilJJKy8jh987y768780';
    public $sessionUserInfoTag      = 'x1ilJJKy8ykq0481gr780';
    public $sessionLoginStatusTag   = 'x1ilJJKy8ykh6042gr780';
    public $sessionRoleTag          = 'x1ilJJKy8ykh98khsr780';
    public $sessionLoginTimeTag     = 'x1ilJJKy8h0yuuuf3r780';
    public $sessionLoginExprTimeTag = 'x1ilJJKy8hsysfwerr780';
    public $sessionLoginIpTag       = 'x1ilJJKy8hyfdqaggr780';
    
    /**
     * 
     * @param type $data
     * @param type $userInfo
     * @return boolean
     */
    static function encryptUserData($data, $userInfo)
    {
        if(is_array($userInfo)){
            $userId = (!empty($userInfo['id'])) ? $userInfo['id'] : false;
            $userName = (!empty($userInfo['username'])) ? strtolower($userInfo['username']) : false;
            if($userId || $userName){
                $hash = $userName . $userId . SELF::DATA_POST_HASH;
                return SELF::encrypt($data,$hash);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @param type $data
     * @param type $userInfo
     * @return boolean
     */
    static function decryptUserData($data, $userInfo)
    {
        if(is_array($userInfo)){
            $userId = (!empty($userInfo['id'])) ? $userInfo['id'] : false;
            $userName = (!empty($userInfo['username'])) ? strtolower($userInfo['username']) : false;
            if($userId || $userName){
                $hash = $userName . $userId . SELF::DATA_POST_HASH;
                return SELF::decrypt($data,$hash);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * 
     * 
     * @param type $var
     * @param type $userInfo
     * @return string|boolean
     */
    static function encrypt($var,$hash='')
    {
        if(empty($hash)){
            $hash = SELF::DATA_POST_HASH;
        }
        $hash = strval($hash);
        
        if(is_array($var)){ /// If the input is a Array
            $ret = [];
            foreach($var as $key => $val){
//                $varKey = SELF::encryptStr($key,$hash);
                $varKey = \Yii::$app->getSecurity()->encryptByPassword($key, $hash);
                if(is_array($val)){
                    $varValue = [];
                    foreach($val as $key => $node){
                        if(is_array($node)){
                            $c = [];
                            foreach($node as $k => $v){

                                $c[ \Yii::$app->getSecurity()->encryptByPassword($k, $hash) ] = \Yii::$app->getSecurity()->encryptByPassword($v, $hash);
                            }
                            $varValue[ \Yii::$app->getSecurity()->encryptByPassword($key, $hash)] = $c;
                        } else {
                            $varValue[\Yii::$app->getSecurity()->encryptByPassword($key, $hash)] = \Yii::$app->getSecurity()->encryptByPassword($node, $hash);
                        }
                    }
                } else {
                    $varValue = \Yii::$app->getSecurity()->encryptByPassword($val, $hash);
                }
                $ret[$varKey] = $varValue; 
            }
        } // End of if input is an Array 
        else {
            $ret = \Yii::$app->getSecurity()->encryptByPassword($var, $hash);
        }

        return $ret;
    }
    
    /**
     * 
     * 
     * @param type $var
     * @param type $userInfo
     * @return string|boolean
     */
    static function decrypt($var,$userInfo='')
    {
        if(is_array($userInfo)){
            $userId = (!empty($userInfo['id'])) ? $userInfo['id'] : '';
            $userName = (!empty($userInfo['username'])) ? strtolower($userInfo['username']) : '';
            $hash = $userName . $userId . SELF::DATA_POST_HASH;
        } else {
            $hash = SELF::DATA_POST_HASH;
        }
        if(is_array($var)){ /// If the input is a Array
            $ret = [];
            foreach($var as $key => $val){
                $varKey = \Yii::$app->getSecurity()->decryptByPassword($key, $hash);
                if(is_array($val)){
                    $varValue = [];
                    foreach($val as $key => $node){
                        if(is_array($node)){
                            $c = [];
                            foreach($node as $k => $v){
                                $c[ \Yii::$app->getSecurity()->decryptByPassword($k, $hash) ] = \Yii::$app->getSecurity()->decryptByPassword($v, $hash);
                            }
                            $varValue[ \Yii::$app->getSecurity()->decryptByPassword($key, $hash)] = $c;
                        } else {
                            $varValue[\Yii::$app->getSecurity()->decryptByPassword($key, $hash)] = \Yii::$app->getSecurity()->decryptByPassword($node, $hash);
                        }
                    }
                } else {
                    $varValue = \Yii::$app->getSecurity()->decryptByPassword($val, $hash);
                }
                $ret[$varKey] = $varValue; 
            }
        } // End of if input is an Array 
        else {
            $ret = \Yii::$app->getSecurity()->decryptByPassword($var, $hash);
        }

        return $ret;
    }
    
    /**
     * 
     * @param type $str
     * @param type $hash
     * @return type
     */
    static function encryptStr($str, $hash=false)
    {
        if(empty($hash)){
            $hash = SELF::DATA_POST_HASH;
        }
        return \Yii::$app->getSecurity()->encryptByPassword($str, $hash);
    }
    
    /**
     * 
     * @param type $str
     * @param type $hash
     * @return type
     */
    static function decryptStr($str, $hash=false)
    {
        if(empty($hash)){
            $hash = SELF::DATA_POST_HASH;
        }
        return \Yii::$app->getSecurity()->decryptByPassword($str, $hash);
    }
    
    /**
     * 
     * @param array $roles
     * @return encrypted string
     */
    static function encryptRoles($roles=[],$userInfo=[])
    {
        return SELF::encrypt(implode(',', $roles),$userInfo);
    }
    
    /**
     * 
     * @param type $encryStr
     * @return type
     */
    static function decryptRoles($encryStr,$userInfo=[])
    {
        return explode(',',  SELF::decrypt($encryStr,$userInfo));
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @param boolean $encryption
     */
    static function setSession($name,$value,$encryption=1)
    {
        $session = \Yii::$app->session;
        if($encryption){
            $session->set($name, SELF::encryptStr($value));
        } else {
            $session->set($name, $value);
        }
    }
    
    /**
     * 
     * @param type $name
     * @param type $encryption
     * @return type
     */
    static function getSession($name,$encryption=1)
    {
        $session = \Yii::$app->session;
        if($encryption){
            $value = $session->get($name);
            return SELF::decryptStr($value);
        } else {
            return $session->get($name);
        }
    }
    
    /**
     * 
     * @return boolean
     */
    static function getUserRoles()
    {
        $auth = \Yii::$app->authManager;
        $roles = $auth->getRolesByUser(\Yii::$app->user->identity->id);
        if(is_object($roles) || is_array($roles)){
            $rolesArr = [];
            foreach($roles as $role)
            {
                if(!empty($role->name)){
                    $rolesArr[] = $role->name;
                }
            }
            if(!empty($rolesArr)){
                return $rolesArr;
            }
        }
        return false;
    }
}
