<?php

/*
 * This file is part of the Simple project.
 *
 * (c) Simple project <https://github.com/azraf>
 * Project Repository: https://github.com/azraf/yii2-simple
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace azraf\simpleapp\classes;

use yii\web\Controller;
use dektrium\user\ModelManager as UserModel;


class SimpleController extends Controller
{
    protected $wtChildPath = false;
    protected $wtControllerPath = false;

    public $wtParams = [];
    public $wtlayoutrole;
    
    /**
     * 
     * @return type
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        //'roles' => ['admin']
                    ],
                ],
            ],
        ];
    }
    
    /**
     * 
     */
    public function setNull()
    {
        $this->wtParams = [];
        $this->wtlayoutrole = false;
    }
    
    /**
     * 
     * @return type
     */
    public function init()
    {
        parent::init();
        
        
        $this->wtControllerPath = SimpleController::className();
        if (\Yii::$app->user->isGuest) {
            
            return $this->redirect(['/user/login']);
        } else {
            SELF::_hooks();
        }
    }
    private function _hooks()
    {
        if(empty( \Yii::$app->session->get('user.name'))){
            $this->_setUserName();
        }

        if(empty( \Yii::$app->session->get('user.regtime'))){
            $this->_setUserRegTime();
        }
    }
    
    /**
     * 
     * @param type $style
     */
    protected function setLayout($style=false)
    {
        switch ($style) {
            case 'admin':
                \Yii::$app->view->params['layoutLeft'] = 'left-menu-admin';
                break;

            case 'editor':
                \Yii::$app->view->params['layoutLeft'] = 'left-menu-editor';
                break;

            default:
                \Yii::$app->view->params['layoutLeft'] = false;
                break;
        }
    }
    
    /**
     * 
     * @return type
     */
    private function _setUserName()
    {
        $model = new UserModel;
        $profile = $model->findProfileById(\Yii::$app->user->identity->id);
        
        if(empty($profile->name)){
            \Yii::$app->session->setFlash('warning', 'Please complete your profile first.');
            return $this->redirect(['/user/settings/profile']);
        } else {
            \Yii::$app->session->set('user.name',$profile->name);
        }
    }
    
    private function _setUserRegTime()
    {
        $model = new UserModel;
        $user = $model->findUserById(\Yii::$app->user->identity->id);

        \Yii::$app->session->set('user.regtime',$user->created_at);
    }
    
    /**
     * 
     * @return type
     */
    public function profile()
    {
        $model = new UserModel;
        return $model->findProfileById(\Yii::$app->user->identity->id);
    }
    
    /**
     * 
     * @param type $view
     * @return type
     */
    public function wtRender($view)
    {
        return $this->render($view, $this->wtParams);
    }
    
    /**
     * 
     * Use this for any testing purpose, to print out any var, by calling this->d() 
     * from any child class
     * 
     * @param type $var
     * @param type $item
     * @param type $exit
     * 
     */
    public function d($var,$item=false,$exit='')
    {
        if($item){
            echo '<br />'.$item.'<br />';
        }
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        if(!empty($exit)){
            if(($exit=='path') || ($exit=='p')){
                exit('--- exit from code ' . strVal($this->wtChildPath). ' --- ' . $this->wtControllerPath);
            }
            exit('--- exit from ' . strVal($exit). ' --- ' . $this->wtControllerPath);
        }
    }
    
    /**
     * 
     * Use this for any testing purpose, by calling this->q() 
     * from any child class
     * 
     * @param type $exit
     */
    protected function q($exit='')
    {
        exit('--- exit from ' . strVals($exit). ' --- ' . $this->wtChildPath . ' <---> ' . $this->wtControllerPath);
    }
}
