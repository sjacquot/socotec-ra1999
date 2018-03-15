<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 15/03/2018
 * Time: 09:07
 */

namespace AppBundle\Entity;

/*require_once ('../../../vendor/jpgraph/jpgraph/lib/jpgraph/src/jpgraph.php');
require_once ('../../../vendor/jpgraph/jpgraph/lib/jpgraph/src/jpgraph_line.php');
require_once ('../../../vendor/jpgraph/jpgraph/lib/jpgraph/src/jpgraph_theme.inc.php');
require_once ('../../../vendor/jpgraph/jpgraph/lib/jpgraph/src/themes/UniversalTheme.class.php');*/

use JpGraph\JpGraph;

class GraphRA1999
{

    private $Settings =
        ["A" =>
            ["Width"=>464,"Height"=>841,
            "title" => "Isolement acoustique standardise DnT",
            "Gabarit"=>[41,50,57,60, 61]],
        "F" =>
            ["Width"=>464,"Height"=>841,
                "title" => "Isolement acoustique standardisé DnT",
                "Gabarit"=>[25,34,41,44, 45]],
        "C" =>
            ["Width"=>464,"Height"=>841,
                "title" => "Niveau du bruit de choc standardise L'nT",
                "Gabarit"=>[48,48,46,43, 30]]];
    private $typeGraph;
    private $Graph;
    private $PathCharts;

    /**
     * GraphRA1999 constructor.
     */
    public function __construct($pathCharts)
    {
        $this->PathCharts = $pathCharts;

    }


    public function createA($data = null)
    {
        $this->typeGraph = "A";
        return $this->create($this->Settings[$this->typeGraph],$data);
    }
    public function createF($data = null)
    {
        $this->typeGraph = "F";
        return $this->create($this->Settings[$this->typeGraph],$data);

    }
    public function createC($data = null)
    {
        $this->typeGraph = "C";
        return $this->create($this->Settings[$this->typeGraph],$data);

    }
    private function create($SettingsGraph, $data){

        JpGraph::load();
        JpGraph::module("line");
        JpGraph::module("theme.inc");
        $this->Graph = new \Graph($SettingsGraph["Width"],$SettingsGraph["Height"]);
        $this->Graph->SetScale('textlin',10,90);

        $theme_class = new \UniversalTheme;
        $this->Graph->SetTheme($theme_class);

        $this->Graph->img->SetAntiAliasing(false);

        $this->Graph->title->Set($SettingsGraph["title"]);
        $this->Graph->SetBox(false);

        $this->Graph->img->SetAntiAliasing();

        $this->Graph->ygrid->Show();
        $this->Graph->ygrid->SetColor('black');

        $this->Graph->yaxis->HideZeroLabel();
        $this->Graph->yaxis->HideLine(false);
        $this->Graph->yaxis->HideTicks(false,true);
        $this->Graph->yaxis->SetTickLabels(array('10','20','30','40','50','60','70','80','90'));
        $this->Graph->yaxis->SetTitle("dB");

        $this->Graph->xgrid->Show();
        $this->Graph->xgrid->SetColor('black');

        $this->Graph->xgrid->SetLineStyle("solid");
        $this->Graph->xaxis->SetTickLabels(array('125','250','500','1000','2000','4000'));
        $this->Graph->xaxis->SetTitle("Hz");



        // Create the Template line
        $p1 = new \LinePlot($SettingsGraph["Gabarit"]);
        $this->Graph->Add($p1);
        $p1->SetColor('red');
        $p1->SetStyle("dashed");
        $p1->SetLegend('Gabarit ISO 717-2');

        $p2 = new \LinePlot($data);
        $this->Graph->Add($p2);
        $p2->SetColor('blue');
        $p2->SetStyle("solid");
        $p2->SetLegend("Resultats de l'essai");

        $this->Graph->legend->SetFrameWeight(1);

        $time = date("Ymd-His");

        //$filepath = "/Users/pullmedia/Sites/socotec-ra1999/web/uploads/";
        $seed = uniqid("chart-".$this->typeGraph."-".$time."-");
        $filename = $seed.'.jpg';
        $this->Graph->Stroke($this->PathCharts.$filename);
        return $filename;

    }
}