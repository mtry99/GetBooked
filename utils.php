<script src="utils.js"></script>

<?php

function ColorHSLToRGB($h, $s, $l){
    $r = $l;
    $g = $l;
    $b = $l;
    $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
    if ($v > 0){
        $m;
        $sv;
        $sextant;
        $fract;
        $vsf;
        $mid1;
        $mid2;

        $m = $l + $l - $v;
        $sv = ($v - $m ) / $v;
        $h *= 6.0;
        $sextant = floor($h);
        $fract = $h - $sextant;
        $vsf = $v * $sv * $fract;
        $mid1 = $m + $vsf;
        $mid2 = $v - $vsf;

        switch ($sextant)
        {
                case 0:
                    $r = $v;
                    $g = $mid1;
                    $b = $m;
                    break;
                case 1:
                    $r = $mid2;
                    $g = $v;
                    $b = $m;
                    break;
                case 2:
                    $r = $m;
                    $g = $v;
                    $b = $mid1;
                    break;
                case 3:
                    $r = $m;
                    $g = $mid2;
                    $b = $v;
                    break;
                case 4:
                    $r = $mid1;
                    $g = $m;
                    $b = $v;
                    break;
                case 5:
                    $r = $v;
                    $g = $m;
                    $b = $mid2;
                    break;
        }
    }
    return "rgb(".($r * 255.0).",".($g * 255.0).",".($b * 255.0).")";
}

$language_map = array("afr"=>"Afrikaans","alb"=>"Albanian","amh"=>"Amharic","ang"=>"English, Old","ara"=>"Arabic","arm"=>"Armenian","asm"=>"Assamese","ava"=>"Avaric","aze"=>"Azerbaijani","baq"=>"Basque","bel"=>"Belarusian","ben"=>"Bengali","bnt"=>"Bantu","bos"=>"Bosnian","bre"=>"Breton","bul"=>"Bulgarian","cat"=>"Catalan","cau"=>"Caucasian","chi"=>"Chinese","chv"=>"Chuvash","cmn"=>"Mandarin","cze"=>"Czech","dan"=>"Danish","dut"=>"Dutch","dzo"=>"Dzongkha","egy"=>"Egyptian","eng"=>"English","enm"=>"English, Middle","esk"=>"Eskimo languages","esp"=>"Esperanto","est"=>"Estonian","fao"=>"Faroese","fin"=>"Finnish","fiu"=>"Finno-Ugrian","fre"=>"French","fri"=>"Frisian","frm"=>"French, Middle","fro"=>"French, Old","gae"=>"Scottish Gaelix","gag"=>"Galician","geo"=>"Georgian","ger"=>"German","gle"=>"Irish","glg"=>"Galician","gmh"=>"German, Middle High","grc"=>"Ancient Greek","gre"=>"Greek","gsw"=>"gsw","guj"=>"Gujarati","hat"=>"Haitian French Creole","hau"=>"Hausa","heb"=>"Hebrew","hin"=>"Hindi","hrv"=>"Croatian","hun"=>"Hungarian","ibo"=>"Igbo","ice"=>"Icelandic","ind"=>"Indonesian","iri"=>"Irish","ita"=>"Italian","jpn"=>"Japanese","kal"=>"Kalâtdlisut","kan"=>"Kannada","kaz"=>"Kazakh","khi"=>"Khoisan","kir"=>"Kyrgyz","kok"=>"Konkani","kor"=>"Korean","kur"=>"Kurdish","lad"=>"Ladino","lao"=>"Lao","lat"=>"Latin","lav"=>"Latvian","lit"=>"Lithuanian","mac"=>"Macedonian","mai"=>"Maithili","mal"=>"Malayalam","mao"=>"Maori","mar"=>"Marathi","may"=>"Malay","mni"=>"Manipuri","mol"=>"Moldavian","mon"=>"Mongolian","mul"=>"Multiple languages","nai"=>"North American Indian","nep"=>"Nepali","new"=>"Newari","nor"=>"Norwegian","oci"=>"Occitan","oji"=>"Ojibwa","ori"=>"Oriya","oss"=>"Ossetic","ota"=>"Turkish, Ottoman","paa"=>"Papuan","pan"=>"Panjabi","pap"=>"Papiamento","per"=>"Persian","pol"=>"Polish","por"=>"Portuguese","roa"=>"Romance","rom"=>"Romani","rum"=>"Romanian","run"=>"Rundi","rus"=>"Russian","sah"=>"Yakut","san"=>"Sanskrit","scc"=>"Serbian","scr"=>"Croatian","sin"=>"Sinhalese","slo"=>"Slovak","slv"=>"Slovenian","smo"=>"Samoan","snh"=>"Sinhalese","som"=>"Somali","spa"=>"Spanish","srp"=>"Serbian","swa"=>"Swahili","swe"=>"Swedish","tag"=>"Tagalog","tam"=>"Tamil","tat"=>"Tatar","tel"=>"Telugu","tgk"=>"Tajik","tgl"=>"Tagalog","tha"=>"Thai","tib"=>"Tibetan","tuk"=>"Turkmen","tur"=>"Turkish","tut"=>"Altaic","twi"=>"Twi","ukr"=>"Ukrainian","und"=>"Undetermined","urd"=>"Urdu","uzb"=>"Uzbek","vie"=>"Vietnamese","wel"=>"Welsh","wen"=>"Sorbian","xho"=>"Xhosa","yid"=>"Yiddish","yor"=>"Yoruba","zap"=>"Zapotec");
