<?php
require_once dirname(__FILE__) . '/cache.php';
class ModJoomBridgedleHelper
{
    public static function getData($params, $module)
    {
        $expiryTime = $params->get('cache');
        $key = 'joombridgedle_' . $params->get('function');
        $moduleId = $module->id;

        // Verifica se tem cache
        $cache = JoomBridgedleCacheHelper::getCache($moduleId, $key, $expiryTime);

        if ($cache !== false) {
            return $cache;
        } else {
            include_once dirname(__FILE__) . '/codes.php';
            $function = $params->get('function');
            $result = null;

            switch ($function) {
                case 'function1':
                    $result = ModJoomBridgedleCodes::getAlunos($params);
                    break;
                case 'function2':
                    $result = ModJoomBridgedleCodes::getVideos($params);
                    break;
                case 'function3':
                    $result = ModJoomBridgedleCodes::getSlides($params);
                    break;
                case 'function4':
                    $result = ModJoomBridgedleCodes::getHacks($params);
                    break;
            }
            JoomBridgedleCacheHelper::setCache($moduleId, $key, $result);
            return $result;
        }
    }
}
