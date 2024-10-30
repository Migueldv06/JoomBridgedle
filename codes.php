<?php

class ModJoomBridgedleCodes
{

    public static function getAlunos($params)
    {
        $URL = $params->get('URL');
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultadoURL = json_decode(curl_exec($ch));
        $result = count($resultadoURL);
        return $result;
    }
    public static function getVideos($params)
    {
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
                if (isset($module->contents) && $module->visible != 0) {
                    foreach ($module->contents as $content) {
                        if (isset($content->mimetype)) {
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
            preg_match_all('/\(\d{1,2}:\d{2}\)/', $linha, $matches);
            if (!empty($matches[0])) {
                $tempo = trim(end($matches[0]), "()");
                list($minuto, $segundo) = explode(":", $tempo);
                $segundosTotal += ($minuto * 60) + $segundo;
            }
        }
        $horasTotal = floor($segundosTotal / 3600);
        $minutosTotal = floor(($segundosTotal % 3600) / 60);
        $result = "$horasTotal:$minutosTotal";
        return $result;
    }
    public static function getSlides($params)
    {
        $contaArquivos = 0;
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
                if (isset($module->contents) && $module->visible != 0) {
                    foreach ($module->contents as $content) {
                        if (isset($content->mimetype)) {
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
                $contaArquivos++;
                $totalSlides += (int)$slides;
            }
        }
        $result = "$contaArquivos Arquivos de apresentação com $totalSlides slides.";
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
                if (isset($module->contents) && $module->visible != 0) {
                    foreach ($module->contents as $content) {
                        if (isset($content->mimetype)) {
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
    public static function getGeral($params)
    {
        $nomearquivos_pdf = array();
        $nomearquivos_mp4 = array();
        $totalVideosHacks = $totalVideos = $totalDownloads = $totalHacks = $totalSlides = $totalArquivoSlides = $segundosTotal = 0;

        $URL = $params->get('URL');
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultadoURL = json_decode(curl_exec($ch));
        curl_close($ch);

        foreach ($resultadoURL as $resultadoArray) {
            foreach ($resultadoArray->modules as $module) {
                $moduloname = $module->name;
                if (isset($module->contents) && $module->visible != 0) {
                    foreach ($module->contents as $content) {
                        if (isset($content->mimetype)) {
                            $mimetype = $content->mimetype;
                            if ($mimetype === "video/mp4") {
                                array_push($nomearquivos_mp4, "$moduloname\n");
                            }
                            if ($mimetype === "application/pdf") {
                                array_push($nomearquivos_pdf, "$moduloname\n");
                            }
                            if ($mimetype !== "video/mp4" && $mimetype !== "application/pdf") {
                                $totalDownloads++;
                            }
                        }
                    }
                }
            }
        }
        foreach ($nomearquivos_mp4 as $linha) {
            if (preg_match_all("/Hack/i", $linha, $videoHacks)) {
                $totalVideosHacks++;
            } else {
                $totalVideos++;
            }
            preg_match_all('/\(\d{1,2}:\d{2}\)/', $linha, $matchesVideos);
            if (!empty($matchesVideos[0])) {
                $tempo = trim(end($matchesVideos[0]), "()");
                list($minuto, $segundo) = explode(":", $tempo);
                $segundosTotal += ($minuto * 60) + $segundo;
            }
        }
        $horasTotal = floor($segundosTotal / 3600);
        $minutosTotal = floor(($segundosTotal % 3600) / 60);
        $HorasVideos = "$horasTotal:$minutosTotal";


        foreach ($nomearquivos_pdf as $linha) {
            preg_match_all('/\((\d+) slides\)/', $linha, $matchesSlides);
            preg_match_all('/\((\d+) Hacks\)/', $linha, $matchesHacks);
            foreach ($matchesSlides[1] as $slides) {
                $totalArquivoSlides++;
                $totalSlides += (int)$slides;
            }
            foreach ($matchesHacks[1] as $hacks) {
                $totalHacks += (int)$hacks;
            }
        }

        $result = "
        <ul style=\"list-style-type: none; padding-left: 40px;\">
            <li><svg width=\"24\" height=\"24\" style=\"vertical-align: top; margin-right: 8px;\"><path d=\"M21 3H3c-1.11 0-2 .89-2 2v12a2 2 0 002 2h5v2h8v-2h5c1.1 0 1.99-.9 1.99-2L23 5a2 2 0 00-2-2zm0 14H3V5h18v12zm-5-6l-7 4V7l7 4z\"/></svg>$HorasVideos horas de vídeo aulas ($totalVideos aulas conceituais + $totalVideosHacks aulas de hacks práticos).</li>
            <li><svg width=\"24\" height=\"24\" style=\"vertical-align: top; margin-right: 8px;\"><path d=\"M11.17 8l-2-2H4v12h16V8h-8.83zM4 4h6l2 2h8c1.1 0 2 .9 2 2v10c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2l.01-12c0-1.1.89-2 1.99-2zm6.55 9v-3h2.9v3H16l-4 4-4-4h2.55z\"/></svg>Virtual machine (Configurada para exercícios).</li>
            <li><svg width=\"24\" height=\"24\" style=\"vertical-align: top; margin-right: 8px;\"><path d=\"M11.17 8l-2-2H4v12h16V8h-8.83zM4 4h6l2 2h8c1.1 0 2 .9 2 2v10c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2l.01-12c0-1.1.89-2 1.99-2zm6.55 9v-3h2.9v3H16l-4 4-4-4h2.55z\"/></svg>$totalDownloads Artefatos de código aberto.</li>
            <li><svg width=\"24\" height=\"24\" style=\"vertical-align: top; margin-right: 8px;\"><path d=\"M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z\"/></svg>$totalHacks Hacks documentados e em vídeo.</li>
            <li><svg width=\"24\" height=\"24\" style=\"vertical-align: top; margin-right: 8px;\"><path d=\"M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z\"/></svg>$totalArquivoSlides Arquivos de apresentação com $totalSlides slides.</li>
            <li><svg width=\"24\" height=\"24\" style=\"vertical-align: top; margin-right: 8px;\"><path d=\"M18.6 6.62c-1.44 0-2.8.56-3.77 1.53L12 10.66 10.48 12h.01L7.8 14.39c-.64.64-1.49.99-2.4.99-1.87 0-3.39-1.51-3.39-3.38S3.53 8.62 5.4 8.62c.91 0 1.76.35 2.44 1.03l1.13 1 1.51-1.34L9.22 8.2A5.37 5.37 0 005.4 6.62C2.42 6.62 0 9.04 0 12s2.42 5.38 5.4 5.38c1.44 0 2.8-.56 3.77-1.53l2.83-2.5.01.01L13.52 12h-.01l2.69-2.39c.64-.64 1.49-.99 2.4-.99 1.87 0 3.39 1.51 3.39 3.38s-1.52 3.38-3.39 3.38c-.9 0-1.76-.35-2.44-1.03l-1.14-1.01-1.51 1.34 1.27 1.12a5.386 5.386 0 003.82 1.57c2.98 0 5.4-2.41 5.4-5.38s-2.42-5.37-5.4-5.37z\"/></svg>Acesso vitalício.</li>
            <li><svg width=\"24\" height=\"24\" style=\"vertical-align: top; margin-right: 8px;\"><path d=\"M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94A5.01 5.01 0 0011 15.9V19H7v2h10v-2h-4v-3.1a5.01 5.01 0 003.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm7 6c-1.65 0-3-1.35-3-3V5h6v6c0 1.65-1.35 3-3 3zm7-6c0 1.3-.84 2.4-2 2.82V7h2v1z\"/></svg>Certificado de conclusão.</li>
        </ul>";

        return $result;
    }
}
