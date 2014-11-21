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

use yii\base\View;

class SimpleViewComponent extends View
{
    public $roles;
//    public $userRoles;
    
    public function init()
    {
        parent::init();
//        $this->roles = 'webadmin';
    }
}
