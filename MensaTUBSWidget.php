<?php
/**
 * Created by PhpStorm.
 * User: jayjay
 * Date: 17.09.15
 * Time: 12:38
 */

require_once("include/simple_html_dom.php");

class MensaTUBSWidget extends StudIPPlugin implements PortalPlugin {

    public function getPluignName(){
        return "Mensaplan";
    }

    public function getPortalTemplate(){
        $widget = $GLOBALS['template_factory']->open('shared/string');

        $widget->content = $this->getContent();
        $widget->icons = $this->getNavigation();
        $widget->title = $this->getPluignName();

        return $widget;
    }

    public function settings_action(){

    }

    protected function getNavigation(){
        $navigation = array();

        $nav = new Navigation('', PluginEngine::getLink($this, array(), 'settings'));
        $nav->setImage('icons/16/blue/admin.png', tooltip2(_('Einstellungen')) + array('data-dialog' => ''));

        return $navigation;
    }


    protected function getContent() {
        $template = $this->getTemplate('today');

        $template->set_attribute('html', $this->parseTodayAll());

        PageLayout::addHeadElement('link', array("rel" => "stylesheet", "href" => $this->getPluginURL()."/css/widget.css", "type" => "text/css"));

        return $template->render();
    }

    protected function getTemplate($template){

        $factory  = new Flexi_TemplateFactory(__DIR__ . '/templates');
        $template = $factory->open($template);
        $template->controller = PluginEngine::getPlugin('MensaTUBS');
        return $template;
    }

    protected function getConfig(){

    }

    protected function storeConfig(){

    }

    public function parseTodayAll() {

        $dayInWeek = date("N", time());
        switch($dayInWeek) {
            case "1":
                $dayInWeek = "mo";
                break;
            case "2":
                $dayInWeek = "di";
                break;
            case "3":
                $dayInWeek = "mi";
                break;
            case "4":
                $dayInWeek = "do";
                break;
            case "5":
                $dayInWeek = "fr";
                break;
            case "6":
                $dayInWeek = "sa";
                break;
            case "7":
                $dayInWeek = "so";
                break;
        }
        $tables = array();

        $html = file_get_html("http://www.stw-on.de/braunschweig/essen/menus/mensa-1");
        foreach($html->find("table#swbs_speiseplan_".$dayInWeek) as $table) {
            $tables["Mensa 1"] .= $table;
        }
        $html = file_get_html("http://www.stw-on.de/braunschweig/essen/menus/360-2");
        foreach($html->find("table#swbs_speiseplan_".$dayInWeek) as $table) {
            $tables["360 Grad"] .= $table;
        }
        $html = file_get_html("http://www.stw-on.de/braunschweig/essen/menus/mensa-2");
        foreach($html->find("table#swbs_speiseplan_".$dayInWeek) as $table) {
            $tables["Mensa 2"] .= $table;
        }
        $html = file_get_html("http://www.stw-on.de/braunschweig/essen/menus/mensa-hbk");
        foreach($html->find("table#swbs_speiseplan_".$dayInWeek) as $table) {
            $tables["Mensa HBK"] .= $table;
        }
        $html = file_get_html("http://www.stw-on.de/braunschweig/essen/menus/bistro4u-nff");
        foreach($html->find("table#swbs_speiseplan_".$dayInWeek) as $table) {
            $tables["Bistro4u NFF"] .= $table;
        }

        if(!isset($tables)){
            $tables["<b>Die Mensa hat heute geschlossen</b>"];
        }

        return $tables;
    }


}