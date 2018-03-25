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
            "Gabarit"=>['serie'=>[41,50,57,60, 61],'title'=>'Gabarit ISO 717-1']],
        "F" =>
            ["Width"=>464,"Height"=>841,
                "title" => "Isolement acoustique standardise DnT",
                "Gabarit"=>['serie'=>[25,34,41,44, 45],'title'=>'Gabarit ISO 717-1']],
        "C" =>
            ["Width"=>464,"Height"=>841,
                "title" => "Niveau du bruit de choc standardise L'nT",
                "Gabarit"=>['serie'=>[48,48,46,43, 30],'title'=>'Gabarit ISO 717-2']]];
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
        $this->Graph->SetMargin(40,25,25,40);


        $this->Graph->title->Set($SettingsGraph["title"]);
        $this->Graph->SetBox(false);

        $this->Graph->img->SetAntiAliasing();

        $this->Graph->yaxis->HideZeroLabel();
        $this->Graph->yaxis->SetTitle("dB");

        $this->Graph->xaxis->SetTickLabels(array('125','250','500','1000','2000','4000'));
        $this->Graph->xaxis->SetTitle("Hz");

        $this->Graph->ygrid->SetFill(true,'#c7dbf9@0.5','#c7dbf9@0.5');
        $this->Graph->ygrid->SetLineStyle("solid");
        $this->Graph->ygrid->Show();
        $this->Graph->ygrid->SetColor('black');

        $this->Graph->xgrid->SetLineStyle("solid");
        $this->Graph->xgrid->Show();
        $this->Graph->xgrid->SetColor('black');


        // Create the Template line
        $p1 = new \LinePlot($data["TEMPLATE"]);
        $this->Graph->Add($p1);
        $p1->SetColor('red');
        $p1->SetStyle('solid');
        $p1->SetLineWeight(1);

        $p2 = new \LinePlot($data["TEST"]);
        $this->Graph->Add($p2);
        $p2->SetColor('blue');
        $p2->SetStyle("solid");
        $p2->SetLineWeight(3);

        $this->Graph->legend->Hide();
        $time = date("Ymd-His");

        //$filepath = "/Users/pullmedia/Sites/socotec-ra1999/web/uploads/";
        $seed = uniqid("chart-".$this->typeGraph."-".$time."-");
        $filename = $seed.'.jpg';
        $this->Graph->Stroke($this->PathCharts.$filename);
        return $filename;

    }
}