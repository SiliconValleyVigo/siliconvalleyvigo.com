<?php

class SelectLang {
    static public function InitSelectLang($requests){
        $prefix = substr($requests['userPhone'], 2);
        $langCode = "";
        foreach(ARRAY_LANGS as $lang){
            if($lang['prefix'] === $prefix){
                $langCode .= $lang['lang']; 
            }
        }

        if($langCode === ""){
            foreach(ARRAY_LANGS as $lang){
                if($lang['prefix'] === 'default'){
                    $langCode .= $lang['lang']; 
                }
            }
        }
    
        return $langCode;
    }
}