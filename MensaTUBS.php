<?php

/*
 * MensaTUBS.php
 * main class of MensaTUBS Plugin for Studip 2.4
 *
 * Edited by Biller on Dez 2019 Cronjob auf f1-studip!
 * @author Sebastian Biller <s.biller@tu-braunschweig.de>
 */

require_once getcwd() . '/../vendor/flexi/flexi.php';

class MensaTUBS extends StudipPlugin implements SystemPlugin {

    private $mensa_plan = array();
    private $z_arr = array(
        "1" => "Farbstoff",
        "2" => "Konservierungsstoff",
        "3" => "Antioxidationsmittel",
        "5" => "geschwefelt",
        "6" => "geschw&#228;rzt",
        "7" => "gewachst",
        "8" => "Phosphat",
        "9" => "S&#252;&#223;ungsmittel",
        "10" => "Phenylalaninquelle",
        "11" => "koffeinhaltig",
    );
    private $s_arr = array(
        "20" => "Milcheiwei&#223;",
        "21" => "Milchpulver",
        "22" => "Molkeneiwei&#223;",
        "23" => "Eiklar",
        "24" => "Milch",
        "25" => "Sahne",
        "53" => "Erzeugnisse tierischen Ursprungs",
        "60" => "Zucker und S&#252;&#223;ungsmittel",
        "62" => "konserviert mit Thiabendazol und Imazalil",
        "64" => "kakaohaltige Fettglasur",
    );
    private $a_arr = array(
        "GL" => "glutenhaltiges Getreide",
        "GL1" => "Weizen",
        "GL2" => "Roggen",
        "GL3" => "Gerste",
        "GL4" => "Hafer",
        "GL5" => "Dinkel",
        "GL6" => "Kamut",
        "KR" => "Krebstiere",
        "EI" => "Eier",
        "FI" => "Fisch",
        "EN" => "Erdn&#252;sse",
        "SO" => "Soja(bohnen)",
        "ML" => "Milch (Laktose)",
        "SE" => "Sesamsamen",
        "NU" => "Schalenfr&#252;chte",
        "NU1" => "Mandeln",
        "NU2" => "Haseln&#252;sse",
        "NU3" => "Waln&#252;sse",
        "NU4" => "Kaschun&#252;sse",
        "NU5" => "Pecan&#252;sse",
        "NU6" => "Paran&#252;sse",
        "NU7" => "Pistazien",
        "NU8" => "Macadamiansse",
        "SF" => "Senf",
        "SL" => "Sellerie",
        "SW" => "Schwefeldioxid/Sulfite",
        "LU" => "Lupine",
        "WT" => "Weichtiere",
    );
    private $allg_arr = array(
        "VEGT" => "Vegetarisch",
        "VEGA" => "Vegan",
        "SCHW" => "Schwein",
        "WILD" => "Wild",
        "RIND" => "Rind",
        "LAMM" => "Lamm",
        "GEFL" => "Gefluegel",
        "FISH" => "Fisch",
        "AT" => "Artgerechte Tierhaltung",
        "BIO" => "EU BIO Logo",
        "MV" => "mensaVital",
        "NEU" => "Neu!",
    );
    private $desc = array(
        "A" => "Allergene",
        "Z" => "Zusatzstoffe",
        "S" => "Sonstige",
        "VEGT" => "Vegetarisch",
        "VEGA" => "Vegan",
        "SCHW" => "Schwein",
        "WILD" => "Wild",
        "RIND" => "Rind",
        "LAMM" => "Lamm",
        "GEFL" => "Gefluegel",
        "FISH" => "Fisch",
        "AT" => "Artgerechte Tierhaltung",
        "BIO" => "EU BIO Logo",
        "MV" => "mensaVital",
        "NEU" => "Neu!",
        "1" => "Farbstoff",
        "2" => "Konservierungsstoff",
        "3" => "Antioxidationsmittel",
        "4" => "",
        "5" => "geschwefelt",
        "6" => "geschw&#228;rzt",
        "7" => "gewachst",
        "8" => "Phosphat",
        "9" => "S&#252;&#223; ungsmittel",
        "10" => "Phenylalaninquelle",
        "11" => "koffeinhaltig",
        "20" => "Milcheiwei&#223;",
        "21" => "Milchpulver",
        "22" => "Molkeneiwei&#223;",
        "23" => "Eiklar",
        "24" => "Milch",
        "25" => "Sahne",
        "53" => "Erzeugnisse tierischen Ursprungs",
        "60" => "Zucker und S&#252;&#223;ungsmittel",
        "62" => "konserviert mit Thiabendazol und Imazalil",
        "64" => "kakaohaltige Fettglasur",
        "GL" => "glutenhaltiges Getreide",
        "GL1" => "Weizen",
        "GL2" => "Roggen",
        "GL3" => "Gerste",
        "GL4" => "Hafer",
        "GL5" => "Dinkel",
        "GL6" => "Kamut",
        "KR" => "Krebstiere",
        "EI" => "Eier",
        "FI" => "Fisch",
        "EN" => "Erdn&#252;sse",
        "SO" => "Soja(bohnen)",
        "ML" => "Milch (Laktose)",
        "SE" => "Sesamsamen",
        "NU" => "Schalenfr&#252;chte",
        "NU1" => "Mandeln",
        "NU2" => "Haseln&#252;sse",
        "NU3" => "Waln&#252;sse",
        "NU4" => "Kaschun&#252;sse",
        "NU5" => "Pecan&#252;sse",
        "NU6" => "Paran&#252;sse",
        "NU7" => "Pistazien",
        "NU8" => "Macadamiansse",
        "SF" => "Senf",
        "SL" => "Sellerie",
        "SW" => "Schwefeldioxid/Sulfite",
        "LU" => "Lupine",
        "WT" => "Weichtiere"
    );
    private $mensa_ids = array(194, 101, 105, 120, 111, 109);

    public function __construct() {
        parent::__construct();
        setlocale(LC_TIME, "de_DE");
        $this->loadMensa();

        $mensa_ids = $this->mensa_ids;

        //PageLayout::setTitle("Mensaplan");
        //header Navigation
        $navigation = new Navigation('Mensa', PluginEngine::getURL("MensaTUBS"), array("id" => "194"));
        $navigation->setImage(Icon::create($this->getPluginURL() . '/images/MensaTUBS_logo.png', 'info'));
        Navigation::addItem("/mensa", $navigation);

        //sub navigation mensen
        $nav_foodtruck = new Navigation("Foodtruck BS/WF", PluginEngine::getURL("MensaTUBS"), array("id" => 194));
        $nav_kth = new Navigation("Mensa 1", PluginEngine::getURL("MensaTUBS"), array("id" => 101));
        $nav_bth = new Navigation("Mensa 2", PluginEngine::getURL("MensaTUBS"), array("id" => 105));
        $nav_hbk = new Navigation("HBK Mensa", PluginEngine::getURL("MensaTUBS"), array("id" => 120));
        $nav_360 = new navigation("360 Grad", PluginEngine::getURL("MensaTUBS"), array("id" => 111));
        $nav_b4u = new navigation("Bistro4u NFF", PluginEngine::getURL("MensaTUBS"), array("id" => 109));

        //initialize sub navs
        $nav_head = Navigation::getItem("/mensa");

	Navigation::addItem("/mensa/194", $nav_foodtruck);
        Navigation::addItem("/mensa/101", $nav_kth);
        Navigation::addItem("/mensa/111", $nav_360);
        Navigation::addItem("/mensa/105", $nav_bth);
        Navigation::addItem("/mensa/120", $nav_hbk);
        Navigation::addItem("/mensa/109", $nav_b4u);


	$nav_head->addSubNavigation("194", $nav_foodtruck);
        $nav_head->addSubNavigation("101", $nav_kth);
        $nav_head->addSubNavigation("111", $nav_360);
        $nav_head->addSubNavigation("105", $nav_bth);
        $nav_head->addSubNavigation("120", $nav_hbk);
        $nav_head->addSubNavigation("109", $nav_b4u);

        $tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
        $monate = array(1=>"Januar",2=>"Februar",3=>"März",4=>"April",5=>"Mai",6=>"Juni",7=>"Juli",8=>"August",9=>"September",10=>"Oktober",11=>"November",12=>"Dezember");
        //navs
        foreach ($mensa_ids as $key => $id) {
            $days = $this->getMensaAvailDates($id);
	    if($days && is_array($days)){
                foreach ($days as $day) {
                    ${ 'nav_' . $id . '_' . $day } = new Navigation($tage[date("w", strtotime($day))].', '.date("j.", strtotime($day)).' '.$monate[date("n", strtotime($day))], PluginEngine::getURL("MensaTUBS"), array("id" => $id, "date" => $day));
                    ${ 'nav_' . $id . '_item' } = Navigation::getItem("/mensa/" . $id);
                    ${ 'nav_' . $id . '_item' }->addSubNavigation($day, ${ 'nav_' . $id . '_' . $day });
                }
	    }
        }


        PageLayout::addHeadElement('link', array("rel" => "stylesheet", "href" => $this->getPluginURL() . "/css/main.css", "type" => "text/css"));
    }

    public function show_action() {
        $options_id = array('options' => array('default' => 194, 'min_range' => 100, 'max_range' => 200));
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, $options_id);
        if(!in_array($id, $this->mensa_ids)){
           $id = 194;
        }
        $options_date = array('options' => array('default' => $this->getMensaAvailDates($id)[0]));
        $date = filter_input(INPUT_GET, 'date', FILTER_DEFAULT, $options_date);
        if(!in_array($date, $this->getMensaAvailDates($id))){
           $date = $this->getMensaAvailDates($id)[0];
        }
        
        
        //initialize templates
        $template_path = $this->getPluginPath() . '/templates';
        $template_factory = new Flexi_TemplateFactory($template_path);

        Navigation::activateItem('/mensa/' . $id . '/' . $date);
        $template = $template_factory->open("generic");
        $template->set_attribute("html", $this->getHtmlMensa($id, $date));

        $template->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        echo $template->render();
    }

    private function loadMensa() {
        $xmlfile = file_get_contents('/var/www/html/studip/public/plugins_packages/tu-braunschweig/MensaTUBS/mensa.xml');
        $file = simplexml_load_string($xmlfile);
        foreach ($file->mensa as $mensa) {
            $this->mensa_plan[$mensa->attributes()->id->__toString()] = $mensa;
        }
        return $this->mensa_plan;
    }

    private function getMensaAvailDates($id) {
        $arr = $this->mensa_plan;
	$days_arr=array();
	if($arr && is_array($arr)){
        foreach ($arr[$id] as $day) {
            if (strtotime($day->attributes()->date->__toString()) >= strtotime(date('Y-m-d'))) {
                $days_arr[] = $day->attributes()->date->__toString();
            }
        }
	}
        return $days_arr;
    }

    private function getMensaMealsDate($id, $date) {
        $arr = $this->mensa_plan;
        foreach ($arr[$id] as $day) {
            if ($day->attributes()->date->__toString() == $date) {
                foreach ($day->meal as $meal) {
                    $attr[] = $meal->attributes();
                }
            }
        }
        return $attr;
    }

    private function getOeffnungen($id, $date) {
        $arr = $this->mensa_plan;
        $oeffnungen[1] = array('name' => $arr[$id]->attributes()->oeffnung1_name->__toString(), 'zeit' => $arr[$id]->attributes()->oeffnung1_zeit->__toString());
        $oeffnungen[2] = array('name' => $arr[$id]->attributes()->oeffnung2_name->__toString(), 'zeit' => $arr[$id]->attributes()->oeffnung2_zeit->__toString());
        $oeffnungen[3] = array('name' => $arr[$id]->attributes()->oeffnung3_name->__toString(), 'zeit' => $arr[$id]->attributes()->oeffnung3_zeit->__toString());
        return $oeffnungen;
    }

    private function getHtmlMensa($id, $date) {
        $arrX = $this->mensa_plan;
        $attr = $this->getMensaMealsDate($id, $date);
        $desc = $this->desc;
        $allg_arr = $this->allg_arr;
        $a_arr = $this->a_arr;
        $s_arr = $this->s_arr;
        $z_arr = $this->z_arr;
        $oeffnungen = $this->getOeffnungen($id, $date);
        $letzte_oeffnung = 0;
        $html = '<div id="container"><h1>' . $arrX[$id]->attributes()->showname->__toString() . ' - ' . $arrX[$id]->attributes()->address->__toString() . '</h1><table style="width:100%">';
        foreach ($attr as $meal_attr) {
            $oeffnung = $meal_attr->oeffnung->__toString();
            if ($oeffnung != $letzte_oeffnung) {
                $html.='<tr><td colspan="4" style="font-size: 1.17em;font-weight: bold;">' . $oeffnungen[$oeffnung]['name'] . ' - (' . $oeffnungen[$oeffnung]['zeit'] . ')</td>';
                $html.= '<th style="text-align: left;">Studenten</th>' .
                        '<th style="text-align: left;">Personal</th>' .
                        '<th style="text-align: left;">G&#228;ste</th>' .
                        '</tr>';
                $letzte_oeffnung = $oeffnung;
            }
            echo '<tr meal-id="' . $meal_attr->id->__toString() . '">';

            $kindname = $meal_attr->kindname->__toString();
            $meal = $meal_attr->meal->__toString();
            $kennzeichnung = $meal_attr->kennzeichnung->__toString();
            setlocale(LC_MONETARY, 'de_DE');
            $price_stud = money_format('%.2n', $meal_attr->price_stud->__toString());
            $price_stud = str_replace('EUR', chr(0xE2) . chr(0x82) . chr(0xAC), $price_stud);
            $price_empl = money_format('%.2n', $meal_attr->price_empl->__toString());
            $price_empl = str_replace('EUR', chr(0xE2) . chr(0x82) . chr(0xAC), $price_empl);
            $price_guest = money_format('%.2n', $meal_attr->price_guest->__toString());
            $price_guest = str_replace('EUR', chr(0xE2) . chr(0x82) . chr(0xAC), $price_guest);
            $azs_kennz = explode(',', $meal_attr->azs_kennz->__toString());
            $info = $azs_kennz;

            $bken = array();
            $ken = explode(',', $kennzeichnung);
            if ($meal_attr->hat_allergen->__toString() == 'A') {
                $bken[] = 'A';
            }
            if ($meal_attr->hat_zusatz->__toString() == 'Z') {
                $bken[] = 'Z';
            }
            if ($meal_attr->hat_sonder->__toString() == 'S') {
                $bken[] = 'S';
            }

            $html.= '<td>' . $kindname . '</td><td>' . $meal . '</td>';
            $html.= '<td title="Kennzeichnungen">';
            foreach ($bken as $v) {
                if (!empty($v)) {
                    $html.= '<span style="padding:2px" class="icon-' . $desc[$v] . '" title="' . $desc[$v] . '">';
                    for ($i = 1; $i <= 8; $i++) {
                        $html.= '<span class="path' . $i . '"></span>';
                    }
                    $html.= '</span>';
                }
            }
            foreach ($ken as $v) {
                if (!empty($v)) {
                    $html.= '<span style="padding:2px" class="icon-' . $desc[$v] . '-solo" title="' . $desc[$v] . '">';
                    for ($i = 1; $i <= 8; $i++) {
                        $html.= '<span class="path' . $i . '"></span>';
                    }
                    $html.= '</span>';
                }
            }
            $html.= '</td>';
            $html.= '<td title="Allergene, Zusatzstoffe und Sonstige Kennzeichnungen">';
            $i = 1;
            $c_info = count($info);
            foreach ($info as $v) {
                $html.= $v . ($i < $c_info ? ',' : '') . '</a>';
                $i++;
            }
            $html.= '</td>';
            $html.= '<td>' . $price_stud . '</td>' .
                    '<td>' . $price_empl . '</td>' .
                    '<td>' . $price_guest . '</td>';

            $html.= '</tr>';
        }
        $html.= '</table></div>';
        $html.= '<div id="container"><div id="container">';
        $html.= '<h1 style="margin-top:50px">Legende</h1>';
        $html.= '<table>';
        $html.= '<tr><td colspan="2" style="font-size: 1.17em;font-weight: bold;">Allgemeine Kennzeichnung</td></tr>';

        foreach ($allg_arr as $key => $item) {
            $html.= '<tr>';
            $html.= '<td><span style="padding:6px" class="icon-' . $desc[$key] . '-solo">';
            for ($i = 1; $i <= 8; $i++) {
                $html.= '<span class="path' . $i . '"></span>';
            }
            $html.= '</span></td>';
            $html.= '<td>' . $item . '</td>';
            $html.= '</tr>';
        }
        
        $html.= '</table></div>';
        $html.= '<div class="grid-stack">';
        $html.= '<div class="grid-stack-item" data-gs-x="0" data-gs-y="0" data-gs-width="2"><table style="width:100%">';

        $html.='<tr><td colspan="2" style="font-size: 1.17em;padding-top: 1.5em;font-weight: bold;">Allergenkennzeichnung</td></tr>';

        foreach ($a_arr as $key => $item) {
            $html.= '<tr><td>' . $key . '</td><td>' . $item . '</td></tr>';
        }
        $html.= '</table></div>';
        $html.= '<div class="grid-stack-item" data-gs-x="2" data-gs-y="0" data-gs-width="2"><table style="width:100%">';
        $html.='<tr><td colspan="2" style="font-size: 1.17em;padding-top: 1.5em;font-weight: bold;">Zusatzstoffe</td></tr>';

        foreach ($z_arr as $key => $item) {
            $html.= '<tr><td>' . $key . '</td><td>' . $item . '</td></tr>';
        }
        $html.= '</table></div>';
        $html.= '<div class="grid-stack-item" data-gs-x="4" data-gs-y="0" data-gs-width="2"><table style="width:100%">';
        $html.= '<tr><td colspan="2" style="font-size: 1.17em;padding-top: 1.5em;font-weight: bold;">Sonstige Kennzeichnung</td></tr>';

        foreach ($s_arr as $key => $item) {
            $html.='<tr><td>' . $key . '</td><td>' . $item . '</td></tr>';
        }

        $html.='</table></div></div></div>';

        return $html;
    }

}
