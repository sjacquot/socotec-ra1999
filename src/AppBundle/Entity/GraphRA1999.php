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

/**
 * \class GraphRA1999
 * Create Graph Images from A(#), F(#), C(#) data \n
 * Curve SRC Is Acoustique Measure values \n
 * Curve Template Is calculated from dynamique Template Curve according to Curve SRC \n
 * @ingroup Acoustique
 *
 */
class GraphRA1999
{
    /**
     * @var array
     */
    private $Settings =
        ["A" =>
            ["Width"=>464, "Height"=>841,
            "title" => "Isolement acoustique standardise DnT",
            "Gabarit"=>['title'=>'Gabarit ISO 717-1']],
        "F" =>
            ["Width"=>464, "Height"=>841,
                "title" => "Isolement acoustique standardise DnT",
                "Gabarit"=>['title'=>'Gabarit ISO 717-1']],
        "C" =>
            ["Width"=>464, "Height"=>841,
                "title" => "Niveau du bruit de choc standardise L'nT",
                "Gabarit"=>['title'=>'Gabarit ISO 717-2']]];
    /**
     * @var string A|F|C
     */
    private $typeGraph;
    private $Graph;
    /**
     * @var JpGraph
     */
    private $PathCharts;

    /**
     * GraphRA1999 constructor.
     * @param $pathCharts string Path to store generated images
     */
    public function __construct($pathCharts)
    {
        $this->PathCharts = $pathCharts;

    }

    /**
     * Create Graph Image file BAI A(#)
     * @param null $data
     * @return bool|null
     */
    public function createA($data = null)
    {
        if (!is_null($data)){
            $this->typeGraph = "A";
            $data['TEMPLATE'] = $this->CalcTemplateCurveA($data["TEST"]);
            $data['src'] = $this->create($this->Settings[$this->typeGraph],$data);
            return $data;
        } else return false;
    }
    /**
     * Create Graph Image file BAE F(#)
     * @param null $data
     * @return bool|null
     */
    public function createF($data = null)
    {
        if (!is_null($data)){
            $this->typeGraph = "F";
            $data['TEMPLATE'] = $this->CalcTemplateCurveF($data["TEST"]);
            $data['src'] = $this->create($this->Settings[$this->typeGraph],$data);
            return $data;
        } else return false;

    }
    /**
     * Create Graph Image file BC C(#)
     * @param null $data
     * @return bool|null
     */
    public function createC($data = null)
    {
        if (!is_null($data)){
            $this->typeGraph = "C";
            $data['TEMPLATE'] = $this->CalcTemplateCurveC($data["TEST"]);
            $data['src'] = $this->create($this->Settings[$this->typeGraph],$data);
            return $data;
        } else return false;
    }

    /**
     * generate Graph image from data and settings
     * @param $SettingsGraph
     * @param $data
     * @return string file path
     */
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

        $this->Graph->ygrid->SetFill(true,'#bae3e9@0.6','#bae3e9@0.6');
        $this->Graph->ygrid->SetLineStyle("solid");
        $this->Graph->ygrid->Show();
        $this->Graph->ygrid->SetColor('black');

        $this->Graph->xgrid->SetLineStyle("solid");
        $this->Graph->xgrid->Show();
        $this->Graph->xgrid->SetColor('black');


        // Create the Template line
        $p1 = new \LinePlot($data["TEMPLATE"]);
        $this->Graph->Add($p1);
        $p1->SetColor('#fa7272');
        $p1->SetStyle('solid');
        $p1->SetLineWeight(1);

        $p2 = new \LinePlot($data["TEST"]);
        $this->Graph->Add($p2);
        $p2->SetColor('#1206b9');
        $p2->SetStyle("solid");
        $p2->SetLineWeight(1);

        $this->Graph->legend->Hide();
        $time = date("Ymd-His");

        //$filepath = "/Users/pullmedia/Sites/socotec-ra1999/web/uploads/";
        $seed = uniqid("chart-".$this->typeGraph."-".$time."-");
        $filename = $seed.'.jpg';
        $this->Graph->Stroke($this->PathCharts.$filename);
        return $filename;

    }

    /**
     * Calculate Template Curve for BAI $data
     * @param $data array of SRC data
     * @return array Template data
     */
    private function CalcTemplateCurveA($data){
        $maxi = 0;
        for($index = 0;$index<=112;$index++){
            $TplCurve = $this->getTemplateACurve($index);
            $Sum = 0.0;
            foreach ($TplCurve as $freq => $value){
                $Sum += (float) ((-$value+$data[$freq])<0)?(-$value+$data[$freq]):0.0;
            }
            if($Sum>=-10.01&&$Sum<-5){
                $maxi = $index;
            }
        }
        return $this->getTemplateACurve($maxi);
    }

    /**
     * Returns dynamique BAI template data at $start (in Decibel value for 500Hz)
     * @param $start
     * @return array
     */
    private function getTemplateACurve($start){
        return [$start-16,$start-7,$start,$start+3,$start+4];
    }


    /**
     * Calculate Template Curve for BAE $data
     * @param $data array of SRC data
     * @return array Template data
     */
    private function CalcTemplateCurveF($data){
        return $this->CalcTemplateCurveA($data);
    }


    /**
     * Calculate Template Curve for BC $data
     * @param $data array of SRC data
     * @return array Template data
     */
    private function CalcTemplateCurveC($data){
        $min = null;
        for($index = 0;$index<=105&&is_null($min);$index++) {
            $TplCurve = $this->getTemplateCurveC($index);
            $Sum = 0.0;
            foreach ($TplCurve as $freq => $value){
                $Sum += (float) ((+$value-$data[$freq])<0)?((float)+$value-(float)$data[$freq]):0.0;
            }
            if($Sum>=-10.001&&$Sum<-5){
                $min = $index;
            }
        }
        return $this->getTemplateCurveC($min);
    }

    /**
     * Returns dynamique BC template data from $start (in Decibel value for 500Hz)
     * @param $start
     * @return array
     */
    private function getTemplateCurveC($start){
        return [$start+2,$start+2,$start,$start-3,$start-16];
    }
}