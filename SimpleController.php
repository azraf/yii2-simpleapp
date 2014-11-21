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
        
        
        $this->wtControllerPath = WebThemesController::className();
        if (\Yii::$app->user->isGuest) {
            
            return $this->redirect(['/user/login']);
        } else {
            if(empty($this->wtParams) || empty($this->wtParams['user'])){
                $this->wtParams = ['user'=>[
                    'id'=> \Yii::$app->user->identity->id,
                    'username'=> \Yii::$app->user->identity->username,
                    'email'=> \Yii::$app->user->identity->email,
                    ]];
            }
            if(!isset($this->wtParams['user']['name']) || empty($this->wtParams['user']['name'])){
                $this->_setUserName();
            }
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
            $this->wtParams['user']['name'] = $profile->name;
        }
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
     * Use this for any testing purpose, by calling this->sExit() 
     * from any child class
     * 
     * @param type $exit
     */
    protected function sExit($exit='')
    {
        exit('--- exit from ' . strVals($exit). ' --- ' . $this->wtChildPath . ' <---> ' . $this->wtControllerPath);
    }
}