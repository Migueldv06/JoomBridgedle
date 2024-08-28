<?php

require_once __DIR__ . '/cache.php';

class ModJoomBridgedleHelper
{
    public static function getData($params, $module)
    {
        $cacheKey = 'joombridgedle_' . $params->get('function');
        $moduleId = $module->id;

        // Verifica se existe cache
        $cache = JoomBridgedleCacheHelper::getCache($moduleId, $cacheKey);

        if ($cache !== false) {
            // Retorna o cache se disponível
            return $cache;
        } else {
            // Gera o resultado se o cache não estiver disponível
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

            // Define o tempo de expiração do cache em minutos (por exemplo, 60 minutos)
            $expiryTime = 60;
            JoomBridgedleCacheHelper::setCache($moduleId, $cacheKey, $result, $expiryTime);

            return $result;
        }
    }
}
