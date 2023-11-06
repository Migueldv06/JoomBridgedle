<?php

defined( '_JEXEC' ) or die;

class PlgContentAPImoodle extends JPlugin
{

    public function onContentPrepare($context, &$article, &$params, $page = 0) {

        //recebe o shortcode
        $short1 = ($this->params->get('shortcode1'));

        //verifica se o shortcode esta no texto do artigo , se sim inicia o codigo 1
        $regex = "{{$short1}}";
        if (preg_match($regex, $article->text)) {
            $article->text = preg_replace($regex, $this->processShortcode1(), $article->text);
        }
        
        //recebe o shortcode
        $short2= ($this->params->get('shortcode2'));

        //verifica se o shortcode esta no texto do artigo , se sim inicia o codigo 2
        $regex = "{{$short2}}";
        if (preg_match($regex, $article->text)) {
            $article->text = preg_replace($regex, $this->processShortcode2(), $article->text);
        }
    }
    
    private function processShortcode1() {
        // Código para o primeiro shortcode

        //recebe dados da url e coloca na variavel $resultado
        $URL1 = ($this->params->get('URL1'));
        $ch = curl_init($URL1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultado = json_decode(curl_exec($ch));

        //conta quantas vezes apareceu a variavel $alunos
        $numaluno = 0 ;
        foreach ($resultado as $alunos){
            $numaluno++;
        }
        $conteudo = $numaluno;

        //retorna a quantidade de alunos no curso
        return $conteudo;
    }
    
    private function processShortcode2() {
        // Código para o segundo shortcode

        //recebe dados da url e coloca na variavel $resultado
        $URL2 = ($this->params->get('URL2'));
        $ch = curl_init($URL2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultado = json_decode(curl_exec($ch));

        //conta quantas vezes apareceu a variavel $alunos
        $numaluno = 0 ;
        foreach ($resultado as $alunos){
            $numaluno++;
        }

        //retorna a quantidade de alunos no curso
        return $numaluno;
    }
    
}
