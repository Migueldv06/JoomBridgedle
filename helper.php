<?php
class ModJoomBridgedleHelper
{
    public static function getData($params, $module)
    {
        $cookie_id = $module->id;
        if (!isset($_COOKIE[$cookie_id])) {
            include_once dirname(__FILE__) . '/codes.php';
            $function = $params->get('function');
            switch ($function) {
                case 'function1':
                    $cookie_value = ModJoomBridgedleCodes::getAlunos($params);
                    break;
                case 'function2':
                    $cookie_value = ModJoomBridgedleCodes::getVideos($params);
                    break;
                case 'function3':
                    $cookie_value = ModJoomBridgedleCodes::getSlides($params);
                    break;
                case 'function4':
                    $cookie_value = ModJoomBridgedleCodes::getHacks($params);
                    break;
                case 'function5':
                    $cookie_value = ModJoomBridgedleCodes::getGeral($params);
                    break;
            }
            setcookie($cookie_id, $cookie_value, time() + $params->get("cache"));
            return $cookie_value;
        } else {
            return $_COOKIE[$cookie_id];
        }
    }
}
