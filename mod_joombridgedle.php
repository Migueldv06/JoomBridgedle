<?php
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';

$layout = $params->get('layout', 'default');
require JModuleHelper::getLayoutPath('mod_joombridgedle', $layout);