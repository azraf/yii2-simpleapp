<?php
/**
 * This class is for enhancing the core Yii2 Nav class, 
 * to get an extra parameter to show icon within the
 * menu
 * USE:
 * 'items' => [
 *       [
 *           'label' => 'Home',
 *           'url' => ['site/index'],
 *           'linkOptions' => [
 *                   'class'=>'active',
 *   ==>             'i_class'=>'fa fa-dashboard', // add <i class="fa fa-dashboard"></i> before the menu
 *   ==>             'i_class_right'=>'fa fa-angle-left pull-right' // <i class="fa fa-angle-left pull-right"></i>
 *                                                 // after the menu
 *               ],
 *       ]
 * ]
 * 
 * example can be found in
 * http://blog.makewebsmart.com/yii2-adding-icon-in-menu-with-yii2-simpleapp-class/292
 * 
 * @link https://github.com/azraf/yii2-simpleapp
 * 
 * @author C. Azraf <c.azraf@gmail.com>
 */

namespace azraf\simpleapp\classes;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;

class SimpleNav extends Nav
{

    public function init()
    {
        parent::init();
    }

    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if ($items !== null) {
            $linkOptions['data-toggle'] = 'dropdown';
            Html::addCssClass($options, 'dropdown');
            Html::addCssClass($linkOptions, 'dropdown-toggle');
            $label .= ' ' . Html::tag('b', '', ['class' => 'caret']);
            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $active);
                }
                $items = $this->renderDropdown($items, $item);
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
        }
        
        return $this->_navTag('li', $this->_navLink($label, $url, $linkOptions) . $items, $options);
    }

    private function _navLink($text, $url = null, $options = [])
    {
        if ($url !== null) {
            $options['href'] = Url::to($url);
        }
        return $this->_navTag('a', $text, $options);
    }

    private function _navTag($name, $content = '', $options = [])
    {
        $iClass = (!empty($options['i_class'])) ? '<i class="'.$options['i_class'].'"></i> ' : '';
        $iClassRight = (!empty($options['i_class_right'])) ? ' <i class="' . $options['i_class_right'] . '"></i>' : '';
        unset($options['i_class'],$options['i_class_right']);

        $html = "<$name" . HTML::renderTagAttributes($options) . '>' . $iClass;
        return isset(HTML::$voidElements[strtolower($name)]) ? $html : "$html$content$iClassRight</$name>";
    }
}
