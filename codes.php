<?php

class ModJoomBridgedleCodes
{

    public static function getAlunos($params)
    {
        $URL = $params->get('URL');
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultado_json = json_decode(curl_exec($ch));
        $result = count($resultado_json);
        return $result;
    }
    public static function getVideos($params)
    {
        $arraysegundos = array();
        $segundosTotal = 0;
        $nomearquivos_mp4 = array();
        $URL = $params->get('URL');
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultado_json = json_decode(curl_exec($ch));
        curl_close($ch);
        foreach ($resultado_json as $resultadoArray) {
            foreach ($resultadoArray->modules as $module) {
                $moduloname = $module->name;
                if (isset($module->contents)) {
                    foreach ($module->contents as $content) {
                        if (isset($content->mimetype)) {
                            $filename = $content->filename;
                            $mimetype = $content->mimetype;
                            if ($mimetype === "video/mp4") {
                                array_push($nomearquivos_mp4, "$moduloname\n");
                            }
                        }
                    }
                }
            }
        }
        foreach ($nomearquivos_mp4 as $linha) {
            // Expressão regular para encontrar o último tempo entre parênteses
            preg_match_all('/\(\d{1,2}:\d{2}\)/', $linha, $matches);
            // Obtém o último match (tempo de vídeo)
            $tempo = end($matches[0]);
            // Remove os parênteses do tempo
            $tempo = trim($tempo, "()");
            list($minuto, $segundo) = explode(":", $tempo);
            $segundos = ($minuto * 60) + $segundo;
            array_push($arraysegundos, $segundos);
        }
        foreach ($arraysegundos as $somasegundos) {
            $segundosTotal = $somasegundos + $segundosTotal;
        }
        $horasTotal = floor($segundosTotal / 3600);
        $segundosRestantes = $segundosTotal % 3600;
        $minutosTotal = floor($segundosRestantes / 60);
        $segundosRestantes = $segundosRestantes % 60;
        $result = "$horasTotal:$minutosTotal";
        return $result;
    }
    public static function getSlides($params)
    {
        $totalSlides = 0;
        $nomearquivos_pdf = array();
        $URL = $params->get('URL');
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultadoURL = json_decode(curl_exec($ch));
        curl_close($ch);
        foreach ($resultadoURL as $resultadoArray) {
            foreach ($resultadoArray->modules as $module) {
                $moduloname = $module->name;
                if (isset($module->contents)) {
                    foreach ($module->contents as $content) {
                        if (isset($content->mimetype)) {
                            $filename = $content->filename;
                            $mimetype = $content->mimetype;
                            if ($mimetype === "application/pdf") {
                                array_push($nomearquivos_pdf, "$moduloname\n");
                            }
                        }
                    }
                }
            }
        }
        foreach ($nomearquivos_pdf as $linha) {
            preg_match_all('/\((\d+) slides\)/', $linha, $matchesSlides);
            foreach ($matchesSlides[1] as $slides) {
                $totalSlides += (int)$slides;
            }
        }
        $result = "$totalSlides";
        return $result;
    }
    public static function getHacks($params)
    {
        $totalHacks = 0;
        $nomearquivos_pdf = array();
        $URL = $params->get('URL');
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultadoURL = json_decode(curl_exec($ch));
        curl_close($ch);
        foreach ($resultadoURL as $resultadoArray) {
            foreach ($resultadoArray->modules as $module) {
                $moduloname = $module->name;
                if (isset($module->contents)) {
                    foreach ($module->contents as $content) {
                        if (isset($content->mimetype)) {
                            $filename = $content->filename;
                            $mimetype = $content->mimetype;
                            if ($mimetype === "application/pdf") {
                                array_push($nomearquivos_pdf, "$moduloname\n");
                            }
                        }
                    }
                }
            }
        }
        foreach ($nomearquivos_pdf as $linha) {
            preg_match_all('/\((\d+) Hacks\)/', $linha, $matchesHacks);
            foreach ($matchesHacks[1] as $hacks) {
                $totalHacks += (int)$hacks;
            }
        }
        $result = "$totalHacks";
        return $result;
    }
}
