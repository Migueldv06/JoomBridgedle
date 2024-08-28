<?php
class ModJoomBridgedleHelper
{
    public static function getData($params)
    {
        /*
        get cache_date from DB se menor que parans-> tempo de cache
        cache = false
        if ($cache = true){
            sql= select data from #__joombridget_cache
            return data from DB
        }

        ou db foi cacheado , data cacheado é hoje cache = true pega data da DB
        data cacheado não é hoje cache true manda valor pra DB 
        */
        if ($cache = false){
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

        return $result;
        }
    }
}
