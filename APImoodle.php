<?php

defined( '_JEXEC' ) or die;

class PlgContentAPImoodle extends JPlugin
{

    public function onContentPrepare($context, &$article, &$params, $page = 0) {
        $regex = '/\[meushortcode1\]/i';
        if (preg_match($regex, $article->text)) {
            $article->text = preg_replace($regex, $this->processShortcode1(), $article->text);
        }
    
        $regex = '/\[meushortcode2\]/i';
        if (preg_match($regex, $article->text)) {
            $article->text = preg_replace($regex, $this->processShortcode2(), $article->text);
        }
    }
    
    private function processShortcode1() {
        // Código para o primeiro shortcode
        $URL1 = ($this->params->get('URL1'));
        $ch = curl_init($URL1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultado = json_decode(curl_exec($ch));

        $numaluno = 0 ;
        foreach ($resultado as $alunos){
            $numaluno++;
        }
        $conteudo = $numaluno;
        return $conteudo;
    }
    
    private function processShortcode2() {
        // Código para o segundo shortcode
        $URL2 = ($this->params->get('URL2'));
        $ch = curl_init($URL2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultado = json_decode(curl_exec($ch));

        $numaluno = 0 ;
        foreach ($resultado as $alunos){
            $numaluno++;
        }

        return $numaluno;
    }
    
}
