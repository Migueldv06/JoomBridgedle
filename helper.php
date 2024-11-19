<?php
class ModJoomBridgedleHelper
{
    public static function getData($params, $module)
    {
        // Inclui o arquivo de funções
        include_once dirname(__FILE__) . '/codes.php';

        // Obtém os parâmetros
        $function = $params->get('function');
        $module_id = $module->id;
        $cache_time = $params->get("cache") * 60;

        // Verifica se há cache válido
        $cacheFile = dirname(__FILE__) . "/cache/jom_bridgedle{$module_id}.html";
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cache_time) {
            return file_get_contents($cacheFile);
        }

        $data = ModJoomBridgedleCodes::$function($params);

        // Salva no cache
        if (!is_dir(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0755, true);
        }
        file_put_contents($cacheFile, $data);

        return $data;
    }
}
