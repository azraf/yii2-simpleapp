<?php

/*
 * This file is part of the Simple project.
 *
 * (c) Simple project <https://github.com/azraf>
 * Project Repository: https://github.com/azraf/yii2-simple
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace azraf\simple;

use yii\base\Module as BaseModule;

/**
 * This is the main module class for the Yii2-simple.
 *
 * @author Azraf <c.azraf@gmail.com>
 */
class Module extends BaseModule
{
    public function __construct($id, $parent = null, $config = [])
    {
        foreach ($this->getModuleComponents() as $name => $component) {
            if (!isset($config['components'][$name])) {
                $config['components'][$name] = $component;
            } elseif (is_array($config['components'][$name]) && !isset($config['components'][$name]['class'])) {
                $config['components'][$name]['class'] = $component['class'];
            }
        }
        parent::__construct($id, $parent, $config);
    }

    /**
     * Returns module components.
     * @return array
     */
    protected function getModuleComponents()
    {
        return [
        ];
    }
}
