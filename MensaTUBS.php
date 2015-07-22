<?php
/*
 * MensaTUBS.php
 * main class of MensaTUBS Plugin for Studip 2.4
 *
 * @author Julian Jacobi <julian.jacobi@tu-braunschweig.de>
 */

require_once getcwd().'/../vendor/flexi/flexi.php';

class MensaTUBS extends StudipPlugin implements SystemPlugin {
	
	public function __construct() {
		parent::__construct();

		//PageLayout::setTitle("Mensaplan");

		//header Navigation
		$navigation = new Navigation('Mensa', PluginEngine::getURL("MensaTUBS"), array("mensa" => "all"));
		$navigation->setImage($this->getPluginURL()."/images/MensaTUBS_logo.png");
		Navigation::addItem("/mensa", $navigation);

		//sub navigation mensen
		$nav_kth = new Navigation("Mensa 1", PluginEngine::getURL("MensaTUBS"), array("mensa" => "kth"));
		$nav_bth = new Navigation("Mensa 2", PluginEngine::getURL("MensaTUBS"), array("mensa" => "bth"));
		$nav_hbk = new Navigation("HBK Mensa", PluginEngine::getURL("MensaTUBS"), array("mensa" => "hbk"));
		$nav_360 = new navigation("360 Grad", PluginEngine::getURL("MensaTUBS"), array("mensa" => "360"));
		$nav_all = new Navigation("Heute", PluginEngine::getURL("MensaTUBS"), array("mensa" => "all"));

		//initialize sub navs
		$nav_head = Navigation::getItem("/mensa");

		Navigation::addItem("/mensa/all", $nav_all);
		Navigation::addItem("/mensa/kth", $nav_kth);
		Navigation::addItem("/mensa/360", $nav_360);
		Navigation::addItem("/mensa/bth", $nav_bth);
		Navigation::addItem("/mensa/hbk", $nav_hbk);


		$nav_head->addSubNavigation("all", $nav_all);
		$nav_head->addSubNavigation("kth", $nav_kth);
		$nav_head->addSubNavigation("360", $nav_360);
		$nav_head->addSubNavigation("bth", $nav_bth);
		$nav_head->addSubNavigation("hbk", $nav_hbk);

		//sub sub navigations
		$nav_kth_this = new Navigation("Diese Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "kth"));
		$nav_kth_next = new Navigation("N&#228;chste Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "kth_next"));
		$nav_bth_this = new Navigation("Diese Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "bth"));
		$nav_bth_next = new Navigation("N&#228;chste Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "bth_next"));
		$nav_hbk_this = new Navigation("Diese Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "hbk"));
		$nav_hbk_next = new Navigation("N&#228;chste Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "hbk_next"));
		$nav_360_this = new Navigation("Diese Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "360"));
		$nav_360_next = new Navigation("N&#228;chste Woche", PluginEngine::getURL("MensaTUBS"), array("mensa" => "360_next"));

		//init sub sub navs
		$nav_kth_item = Navigation::getItem("/mensa/kth");
		$nav_360_item = Navigation::getItem("/mensa/360");
		$nav_bth_item = Navigation::getItem("/mensa/bth");
		$nav_hbk_item = Navigation::getItem("/mensa/hbk");
		$nav_kth_item->addSubNavigation("this", $nav_kth_this);
		$nav_kth_item->addSubNavigation("next", $nav_kth_next);
		$nav_360_item->addSubNavigation("this", $nav_360_this);
		$nav_360_item->addSubNavigation("next", $nav_360_next);
		$nav_bth_item->addSubNavigation("this", $nav_bth_this);
		$nav_bth_item->addSubNavigation("next", $nav_bth_next);
		$nav_hbk_item->addSubNavigation("this", $nav_hbk_this);
		$nav_hbk_item->addSubNavigation("next", $nav_hbk_next);


		PageLayout::addHeadElement('link', array("rel" => "stylesheet", "href" => $this->getPluginURL()."/css/main.css", "type" => "text/css"));
		
	}

	public function show_action() {
		$mensa = "kth";
		if(isset($_GET['mensa'])){
			$mensa = $_GET['mensa'];
		}

		//initialize templates
		$template_path = $this->getPluginPath().'/templates';
		$template_factory = new Flexi_TemplateFactory($template_path);

		switch ($mensa) {
			case "kth": //mensa Katharinenstraße
				
				Navigation::activateItem("/mensa/kth/this");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/mensa-1"));
				echo $template->render();

				break;

			case "360": //mensa 360°
				
				Navigation::activateItem("/mensa/360/this");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/360-2"));
				echo $template->render();

				break;

			case "bth": //mensa Bethovenstraße
				
				Navigation::activateItem("/mensa/bth/this");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/mensa-2"));
				echo $template->render();

				break;

			case "hbk": //mensa HBK
				
				Navigation::activateItem("/mensa/hbk/this");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/mensa-hbk"));
				echo $template->render();

				break;

			case "kth_next": //mensa Katharinenstraße
				
				Navigation::activateItem("/mensa/kth/next");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/mensa-1-kommende-woche"));
				echo $template->render();

				break;

			case "360_next": //mensa 360°
				
				Navigation::activateItem("/mensa/360/next");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/360-nachste-woche"));
				echo $template->render();

				break;

			case "bth_next": //mensa Bethovenstraße
				
				Navigation::activateItem("/mensa/bth/next");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/mensa-2-kommende-woche"));
				echo $template->render();

				break;

			case "hbk_next": //mensa HBK
				
				Navigation::activateItem("/mensa/hbk/next");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/mensa-hbk-kommende-woche"));
				echo $template->render();

				break;
				
			case "all": //today food
				Navigation::activateItem("/mensa/all");
                var_dump(PageLayout::getTabNavigation()->activeSubnavigation()->getTitle());

				$template = $template_factory->open("today");
				$template->set_attribute("html", $this->parseTodayAll());
				echo $template->render();

				break;

			default: //default is kth
				
				Navigation::activateItem("/mensa/kth");

				$template = $template_factory->open("generic");
				$template->set_attribute("html", $this->parseMensa("http://www.stw-on.de/braunschweig/essen/menus/mensa-1"));
				echo $template->render();

				break;
		}
	}

	private function parseMensa($url) {
		require_once($this->getPluginPath()."/include/simple_html_dom.php");

		$html = file_get_html($url);

		$day = "";
		$day_html = "";

		foreach($html->find("table.swbs_speiseplan") as $table) {

			//evaluate current day
			$tbHead = $table->find("th.swbs_speiseplan_head");
			$headline = $tbHead[0]->innertext;
			$pos = strpos($headline, "&")-1;
			$currentDay = substr($headline, 0, $pos);

			if($day != $currentDay) {
				if($day_html != "") {
					$tables[$day] = $day_html;
				}
				$day = $currentDay;
				$day_html = $table->outertext;
			}else if($day == $currentDay) {
				$day_html .= $table->outertext;
			}

		}
		if(isset($tables)){
			$tables[$day] = $day_html;
		} else {
			$tables[0] = "<b>Die Mensa hat leider geschlossen</b>";
		}

		return $tables;
	}

	private function parseTodayAll() {
		require_once($this->getPluginPath()."/include/simple_html_dom.php");

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

		if(!isset($tables)){
			$tables["<b>Die Mensa hat heute geschlossen</b>"];
		}

		return $tables;
	}
} 
