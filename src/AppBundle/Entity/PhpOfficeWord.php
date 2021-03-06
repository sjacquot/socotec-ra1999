<?php
/**
 * Fork of TemplateProcessor from PhpOffice\PhpWord with cumulated enhancement from various contributors \n
 * to manage Block Clones with variables and Images integration and bestfit images intégration.
 * User: Stéphane Jacquot
 * Date: 15/06/2018
 * Time: 11:23
 */

namespace AppBundle\Entity;


use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Escaper\RegExp;
use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Zend\Stdlib\StringUtils;
use PhpOffice\PhpWord\Settings;

class PhpOfficeWord extends TemplateProcessor
{
    const SEARCH_LEFT = -1;
    const SEARCH_RIGHT = 1;
    const SEARCH_AROUND = 0;
    /**
     * Enable/disable setValue('key') becoming setValue('${key}') automatically.
     * Call it like: TemplateProcessor::$ensureMacroCompletion = false;
     *
     * @var bool
     */
    public static $ensureMacroCompletion = true;
    /**
     * ZipArchive object.
     *
     * @var mixed
     */
    protected $zipClass;
    /**
     * @var string Temporary document filename (with path)
     */
    protected $tempDocumentFilename;
    /**
     * Content of main document part (in XML format) of the temporary document
     *
     * @var string
     */
    protected $tempDocumentMainPart;
    /**
     * Content of headers (in XML format) of the temporary document
     *
     * @var string[]
     */
    protected $tempDocumentHeaders = array();
    /**
     * Content of footers (in XML format) of the temporary document
     *
     * @var string[]
     */
    protected $tempDocumentFooters = array();
    /**
     *
     * Rels and Types - used in inserting images
     *
     */
    protected $_main_rels;
    protected $_header_rels = [];
    protected $_footer_rels = [];
    protected $_types;
    protected $blank_rels_page = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"></Relationships>';
    /**
     * @since 0.12.0 Throws CreateTemporaryFileException and CopyFileException instead of Exception.
     *
     * @param string $documentTemplate The fully qualified template filename
     *
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     */
    public function __construct($documentTemplate)
    {
        // Temporary document filename initialization
        $this->tempDocumentFilename = tempnam(Settings::getTempDir(), 'PhpWord');
        if (false === $this->tempDocumentFilename) {
            throw new CreateTemporaryFileException();
        }
        // Template file cloning
        if (false === copy($documentTemplate, $this->tempDocumentFilename)) {
            throw new CopyFileException($documentTemplate, $this->tempDocumentFilename);
        }
        // Temporary document content extraction
        $this->zipClass = new ZipArchive();
        $this->zipClass->open($this->tempDocumentFilename);
        $index = 1;
        while (false !== $this->zipClass->locateName($this->getHeaderName($index))) {
            $this->tempDocumentHeaders[$index] = $this->fixBrokenMacros(
                $this->zipClass->getFromName($this->getHeaderName($index))
            );
            $index++;
        }
        $index = 1;
        while (false !== $this->zipClass->locateName($this->getFooterName($index))) {
            $this->tempDocumentFooters[$index] = $this->fixBrokenMacros(
                $this->zipClass->getFromName($this->getFooterName($index))
            );
            $index++;
        }
        $this->tempDocumentMainPart = $this->fixBrokenMacros($this->zipClass->getFromName($this->getMainPartName()));
        $this->_countRels=100;
    }
    /**
     * @param string $macro If written as VALUE it will return ${VALUE} if static::$ensureMacroCompletion
     * @param bool $closing False by default, if set to true, will add  ${/  }  around the macro
     *
     * @return string
     */
    protected static function ensureMacroCompleted($macro, $closing = false)
    {
        if (static::$ensureMacroCompletion && substr($macro, 0, 2) !== '${' && substr($macro, -1) !== '}') {
            $macro = '${' . ($closing ? '/' : '') . $macro . '}';
        }
        return $macro;
    }
    /**
     * @param mixed $search macro name you want to replace (or an array of these)
     * @param mixed $replace replace string (or an array of these)
     * @param int $limit How many times it will have to replace the same variable all over the document
     */
    public function setValue($search, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        if (is_array($search)) {
            foreach ($search as &$item) {
                $item = static::ensureMacroCompleted($item);
            }
            unset($item);
        } else {
            $search = static::ensureMacroCompleted($search);
        }
        if (is_array($replace)) {
            foreach ($replace as &$item) {
                $item = static::ensureUtf8Encoded($item);
            }
            unset($item);
        } else {
            $replace = static::ensureUtf8Encoded($replace);
        }
        if (Settings::isOutputEscapingEnabled()) {
            $xmlEscaper = new Xml();
            $replace = $xmlEscaper->escape($replace);
        }
        $this->tempDocumentHeaders = (array) $this->setValueForPart(
            $search,
            $replace,
            (array) $this->tempDocumentHeaders,
            $limit
        );
        $this->tempDocumentMainPart = (string) $this->setValueForPart(
            $search,
            $replace,
            (string) $this->tempDocumentMainPart,
            $limit
        );
        $this->tempDocumentFooters = (array) $this->setValueForPart(
            $search,
            $replace,
            (array) $this->tempDocumentFooters,
            $limit
        );
    }
    /**
     * Set a new image
     *
     * @param string $search
     * @param string $replace
     */
    public function setImageValue($search, $replace)
    {
        // Sanity check
        if (!file_exists($replace)) {
            return;
        }
        // Delete current image
        $this->zipClass->deleteName('word/media/' . $search);
        // Add a new one
        $this->zipClass->addFile($replace, 'word/media/' . $search);
    }

    public function setImages($search, $images){
        $this->setImagesInner($search, $images);
    }

    public function setImg($search, $img){
        $this->setImagesInner($search, array($img));
    }

    public function setFixedSizedImages($search, $images){
        $images_to_add = "";
        $types_to_add = "";
        $rels_to_add = "";
        foreach ($images as $img) {
            $desired_height=(int)$img['h'];
            $desired_width=(int)$img['w'];
            if(isset($img['units'])){
                $desired_units= $img['units'];
            } else {
                $desired_units= 'cm';
            }
            //get current picture counter
            $countrels=$this->_countRels++;
            //create a relationship id
            $rel_id = 'rId' . $countrels;

            //get the image extension from file source
            $exploded = explode(".", $img['src']);
            $img_extension = end($exploded);
            //eg img100.jpg
            $img_name = 'img' . $countrels . '.' . $img_extension;
            //remove file if exists and add new one
            $this->zipClass->deleteName('word/media/' . $img_name);
            $this->zipClass->addFile($img['src'], 'word/media/' . $img_name);

            //create img content
            $img_template = '
				<w:pict>
					<v:shape type="#_x0000_t75" style="width:WIDUNIT;height:HEIUNIT">
						<v:imagedata r:id="RID" o:title=""/>
					</v:shape>
				</w:pict>
			';
            $img_search = array('RID', 'WID', 'HEI','UNIT');
            $img_replace = array($rel_id, $desired_width, $desired_height,$desired_units);
            $img_to_add = str_replace($img_search, $img_replace, $img_template);
            $images_to_add .= $img_to_add;
            //create type content
            $type_template = '<Override PartName="/word/media/IMG" ContentType="image/EXT"/>';

            $type_search = array('IMG', 'EXT');
            $type_replace = array($img_name, $img_extension);
            $type_to_add = str_replace($type_search, $type_replace, $type_template) ;
            $types_to_add .= $type_to_add;

            //create relationship content
            $rel_template = '<Relationship Id="RID" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/IMG"/>';

            $rel_search = array('RID', 'IMG');
            $rel_replace = array($rel_id, $img_name);
            $rel_to_add = str_replace($rel_search, $rel_replace, $rel_template);
            $rels_to_add .= $rel_to_add;
        }
        // Fin des ajouts en attachments
        //add stuff to search string
        $str_key = '${'.$search.'}';


        //try to insert the part to replace, if count comes back positive, add to rels

        //start with main part
        $count = 0;
        $this->tempDocumentMainPart =  str_replace($str_key, $images_to_add, $this->tempDocumentMainPart, $count);

        if ($count > 0){
            if ($this->_main_rels==""){
                $this->_main_rels=$this->zipClass->getFromName('word/_rels/document.xml.rels');
            }
            $this->_main_rels = str_replace('</Relationships>', $rels_to_add, $this->_main_rels) . '</Relationships>';
        }

        //headers

        foreach($this->tempDocumentHeaders as $index=>$header){
            $count = 0;
            $this->tempDocumentHeaders[$index] = str_replace($str_key, $images_to_add, $this->tempDocumentHeaders[$index], $count);
            if ($count > 0){
                //check if not set
                if (!isset($this->_header_rels[$index])){
                    $this->_header_rels = $this->zipClass->getFromName('word/_rels/header'.$index.'.xml.rels');
                }
                //check if empty
                if ($this->_header_rels[$index] == ""){
                    $this->_header_rels[$index]=$this->blank_rels_page;
                }
                //add
                $this->_header_rels[$index] = str_replace('</Relationships>', $rels_to_add, $this->_header_rels[$index]) . '</Relationships>';
            }
        }

        //footers

        foreach($this->tempDocumentFooters as $index=>$footer){
            $count = 0;
            $this->tempDocumentFooters[$index] = str_replace($str_key, $images_to_add, $this->tempDocumentFooters[$index], $count);
            if ($count > 0){
                //check if not set
                if (!isset($this->_footer_rels[$index])){
                    $this->_footer_rels = $this->zipClass->getFromName('word/_rels/footer'.$index.'.xml.rels');
                }
                //check if empty
                if ($this->_footer_rels[$index] == ""){
                    $this->_footer_rels[$index]=$this->blank_rels_page;
                }
                //add
                $this->_footer_rels[$index] = str_replace('</Relationships>', $rels_to_add, $this->_footer_rels[$index]) . '</Relationships>';
            }
        }


        //check if types are set
        if($this->_types==""){
            $this->_types=$this->zipClass->getFromName('[Content_Types].xml');
        }

        //add types onto the the end of types
        $this->_types = str_replace('</Types>', $types_to_add, $this->_types) . '</Types>';

        // -> Remplacement dans le template
    }

    private function setImagesInner($search, $images){

        $images_to_add = "";
        $types_to_add = "";
        $rels_to_add = "";

        foreach($images as $img){

            list($width, $height) = getimagesize($img['src']);
            if(isset($img['swh'])){
                //if the image is more vertical
                if($width<=$height){
                    //set the vertical size to the one specified
                    $desired_height=(int)$img['swh'];
                    //figure out the ratio between the height and width
                    $ratio=$width/$height;
                    //increase the other by the ratio
                    $desired_width=(int)$img['swh']*$ratio;
                    //make a whole number
                    $desired_width=round($desired_width);
                }

                //if the image is more horizontal
                if($width>=$height){
                    $desired_width=(int)$img['swh'];
                    $ratio=$height/$width;
                    $desired_height=(int)$img['swh']*$ratio;
                    $desired_height=round($desired_height);
                }

                //set the new width and height
                $width=$desired_width;
                $height=$desired_height;
            }

            //get current picture counter
            $countrels=$this->_countRels++;
            //create a relationship id
            $rel_id = 'rId' . $countrels;

            //get the image extension from file source
            $exploded = explode(".", $img['src']);
            $img_extension = end($exploded);
            //eg img100.jpg
            $img_name = 'img' . $countrels . '.' . $img_extension;
            //remove file if exists and add new one
            $this->zipClass->deleteName('word/media/' . $img_name);
            $this->zipClass->addFile($img['src'], 'word/media/' . $img_name);

            //create img content
            $img_template = '
				<w:pict>
					<v:shape type="#_x0000_t75" style="width:WIDpx;height:HEIpx">
						<v:imagedata r:id="RID" o:title=""/>
					</v:shape>
				</w:pict>
			';

            $img_search = array('RID', 'WID', 'HEI');
            $img_replace = array($rel_id, $width, $height);
            $img_to_add = str_replace($img_search, $img_replace, $img_template);
            $images_to_add .= $img_to_add;
            //create type content
            $type_template = '<Override PartName="/word/media/IMG" ContentType="image/EXT"/>';

            $type_search = array('IMG', 'EXT');
            $type_replace = array($img_name, $img_extension);
            $type_to_add = str_replace($type_search, $type_replace, $type_template) ;
            $types_to_add .= $type_to_add;

            //create relationship content
            $rel_template = '<Relationship Id="RID" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/IMG"/>';

            $rel_search = array('RID', 'IMG');
            $rel_replace = array($rel_id, $img_name);
            $rel_to_add = str_replace($rel_search, $rel_replace, $rel_template);
            $rels_to_add .= $rel_to_add;

        }

        //add stuff to search string
        $str_key = '${'.$search.'}';


        //try to insert the part to replace, if count comes back positive, add to rels

        //start with main part
        $count = 0;
        $this->tempDocumentMainPart =  str_replace($str_key, $images_to_add, $this->tempDocumentMainPart, $count);

        if ($count > 0){
            if ($this->_main_rels==""){
                $this->_main_rels=$this->zipClass->getFromName('word/_rels/document.xml.rels');
            }
            $this->_main_rels = str_replace('</Relationships>', $rels_to_add, $this->_main_rels) . '</Relationships>';
        }

        //headers

        foreach($this->tempDocumentHeaders as $index=>$header){
            $count = 0;
            $this->tempDocumentHeaders[$index] = str_replace($str_key, $images_to_add, $this->tempDocumentHeaders[$index], $count);
            if ($count > 0){
                //check if not set
                if (!isset($this->_header_rels[$index])){
                    $this->_header_rels = $this->zipClass->getFromName('word/_rels/header'.$index.'.xml.rels');
                }
                //check if empty
                if ($this->_header_rels[$index] == ""){
                    $this->_header_rels[$index]=$this->blank_rels_page;
                }
                //add
                $this->_header_rels[$index] = str_replace('</Relationships>', $rels_to_add, $this->_header_rels[$index]) . '</Relationships>';
            }
        }

        //footers

        foreach($this->tempDocumentFooters as $index=>$footer){
            $count = 0;
            $this->tempDocumentFooters[$index] = str_replace($str_key, $images_to_add, $this->tempDocumentFooters[$index], $count);
            if ($count > 0){
                //check if not set
                if (!isset($this->_footer_rels[$index])){
                    $this->_footer_rels = $this->zipClass->getFromName('word/_rels/footer'.$index.'.xml.rels');
                }
                //check if empty
                if ($this->_footer_rels[$index] == ""){
                    $this->_footer_rels[$index]=$this->blank_rels_page;
                }
                //add
                $this->_footer_rels[$index] = str_replace('</Relationships>', $rels_to_add, $this->_footer_rels[$index]) . '</Relationships>';
            }
        }


        //check if types are set
        if($this->_types==""){
            $this->_types=$this->zipClass->getFromName('[Content_Types].xml');
        }

        //add types onto the the end of types
        $this->_types = str_replace('</Types>', $types_to_add, $this->_types) . '</Types>';


    }

    /**
     * Replaces a closed block with text
     *
     * @param string $blockname The blockname without '${}'. Your macro must end with slash, i.e.: ${value/}
     * @param mixed $replace Array or the text can be multiline (contain \n); It will then cloneBlock()
     * @param int $limit
     */
    public function setBlock($blockname, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        if (is_string($replace) && preg_match('~\R~u', $replace)) {
            $replace = preg_split('~\R~u', $replace);
        }
        if (is_array($replace)) {
            $this->processBlock($blockname, count($replace), true, false);
            foreach ($replace as $oneVal) {
                $this->setValue($blockname, $oneVal, 1);
            }
        } else {
            $this->setValue($blockname, $replace, $limit);
        }
    }
    /**
     * Expose zip class
     *
     * To replace an image: $templateProcessor->zip()->AddFromString("word/media/image1.jpg", file_get_contents($file));
     * (note that to add an image you also need to add some xml in the document, and a relation from Id to zip-filename)
     * To read a file: $templateProcessor->zip()->getFromName("word/media/image1.jpg");
     *
     * @return object
     */
    public function zip()
    {
        return $this->zipClass;
    }
    /**
     * If $throwException is true, it throws an exception, else it returns $elseReturn
     *
     * @param string $exceptionText
     * @param bool $throwException
     * @param mixed $elseReturn
     *
     * @return mixed
     */
    private function failGraciously($exceptionText, $throwException, $elseReturn)
    {
        if ($throwException) {
            throw new Exception($exceptionText);
        }
        return $elseReturn;
    }
    /**
     * Returns array of all variables in template.
     *
     * @return string[]
     */
    public function getVariables()
    {
        $variables = $this->getVariablesForPart($this->tempDocumentMainPart);
        foreach ($this->tempDocumentHeaders as $headerXML) {
            $variables = array_merge($variables, $this->getVariablesForPart($headerXML));
        }
        foreach ($this->tempDocumentFooters as $footerXML) {
            $variables = array_merge($variables, $this->getVariablesForPart($footerXML));
        }
        return array_unique($variables);
    }
    /**
     * Clone a string and enumerate ( i.e. ${macro#1} )
     *
     * @param string $text Must be a variable as we use references for speed
     * @param int $numberOfClones How many times $text needs to be duplicated
     * @param bool $incrementVariables If true, the macro's inside the string get numerated
     *
     * @return string
     */
    protected static function cloneSlice(&$text, $numberOfClones = 1, $incrementVariables = true)
    {
        $result = '';
        for ($i = 1; $i <= $numberOfClones; $i++) {
            if ($incrementVariables) {
                $result .= preg_replace('/\$\{(.*?)(\/?)\}/', '\${\\1#' . $i . '\\2}', $text);
            } else {
                $result .= $text;
            }
        }
        return $result;
    }
    /**
     * Process a table row in a template document.
     *
     * @param string $search
     * @param int $numberOfClones
     * @param mixed $replace (true to clone, or a string to replace)
     * @param bool $incrementVariables
     * @param bool $throwException
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @return string|false Returns the row cloned or false if the $search macro is not found
     */
    private function processRow(
        $search,
        $numberOfClones = 1,
        $replace = true,
        $incrementVariables = true,
        $throwException = false
    ) {
        return $this->processSegment(
            static::ensureMacroCompleted($search),
            'w:tr',
            0,
            $numberOfClones,
            'MainPart',
            function (&$xmlSegment, &$segmentStart, &$segmentEnd, &$part) use (&$replace) {
                if (strpos($xmlSegment, '<w:vMerge w:val="restart"')) {
                    $extraRowEnd = $segmentEnd;
                    while (true) {
                        $extraRowStart = $extraRowEnd + 1;
                        $extraRowEnd = strpos($part, '</w:tr>', $extraRowStart);
                        if (!$extraRowEnd) {
                            break;
                        }
                        $extraRowEnd += strlen('</w:tr>');
                        // If tmpXmlRow doesn't contain continue, this row is no longer part of the spanned row.
                        $tmpXmlRow = substr($part, $extraRowStart, ($extraRowEnd - $extraRowStart));
                        if (!preg_match('#<w:vMerge ?/>#', $tmpXmlRow)
                            && !preg_match('#<w:vMerge w:val="continue" ?/>#', $tmpXmlRow)
                        ) {
                            break;
                        }
                        // This row was a spanned row, update $segmentEnd and search for the next row.
                        $segmentEnd = $extraRowEnd;
                    }
                    $xmlSegment = substr($part, $segmentStart, ($segmentEnd - $segmentStart));
                }
                return $replace;
            },
            $incrementVariables,
            $throwException
        );
    }
    /**
     * Clone a table row in a template document.
     *
     * @param string $search
     * @param int $numberOfClones
     * @param bool $incrementVariables
     * @param bool $throwException
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @return mixed Returns true if row cloned succesfully or or false if the $search macro is not found
     */
    public function cloneRow(
        $search,
        $numberOfClones = 1,
        $incrementVariables = true,
        $throwException = false
    ) {
        return $this->processRow($search, $numberOfClones, true, $incrementVariables, $throwException);
    }
    /**
     * Get a row. (first block found)
     *
     * @param string $search
     * @param bool $throwException
     *
     * @return string|null
     */
    public function getRow($search, $throwException = false)
    {
        return $this->processRow($search, 0, false, false, $throwException);
    }
    /**
     * Replace a row.
     *
     * @param string  $search a macro name in a table row
     * @param string  $replacement The replacement <w:tr> xml string. Be careful and keep the xml uncorrupted.
     * @param bool $throwException false by default (it then returns false or null on errors)
     *
     * @return mixed true (replaced), false ($search not found) or null (no tags found around $search)
     */
    public function replaceRow($search, $replacement = '', $throwException = false)
    {
        return $this->processRow($search, 1, (string) $replacement, false, $throwException);
    }
    /**
     * Delete a row containing the given variable
     *
     * @param string $search
     *
     * @return bool
     */
    public function deleteRow($search)
    {
        return $this->processRow($search, 0, '', false, false);
    }
    /**
     * process a block.
     *
     * @param string  $blockname The blockname without '${}'
     * @param int $clones
     * @param mixed $replace
     * @param bool $incrementVariables
     * @param bool $throwException
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @return mixed The cloned string if successful, false ($blockname not found) or Null (no paragraph found)
     */
    private function processBlock(
        $blockname,
        $clones = 1,
        $replace = true,
        $incrementVariables = true,
        $throwException = false
    ) {
        $startSearch = static::ensureMacroCompleted($blockname);
        $endSearch = static::ensureMacroCompleted($blockname, true);
        if (substr($blockname, -1) == '/') { // singleton/closed block
            return $this->processSegment(
                $startSearch,
                'w:p',
                0,
                $clones,
                'MainPart',
                $replace,
                $incrementVariables,
                $throwException
            );
        }
        $startTagPos = strpos($this->tempDocumentMainPart, $startSearch);
        $endTagPos = strpos($this->tempDocumentMainPart, $endSearch, $startTagPos);
        if (!$startTagPos || !$endTagPos) {
            return $this->failGraciously(
                "Can not find block '$blockname', template variable not found or variable contains markup.",
                $throwException,
                false
            );
        }
        $startBlockStart = $this->findOpenTagLeft($this->tempDocumentMainPart, '<w:p>', $startTagPos, $throwException);
        $startBlockEnd = $this->findCloseTagRight($this->tempDocumentMainPart, '</w:p>', $startTagPos);
        if (!$startBlockStart || !$startBlockEnd) {
            return $this->failGraciously(
                "Can not find start paragraph around block '$blockname'",
                $throwException,
                null
            );
        }
        $endBlockStart = $this->findOpenTagLeft($this->tempDocumentMainPart, '<w:p>', $endTagPos, $throwException);
        $endBlockEnd = $this->findCloseTagRight($this->tempDocumentMainPart, '</w:p>', $endTagPos);
        if (!$endBlockStart || !$endBlockEnd) {
            return $this->failGraciously(
                "Can not find end paragraph around block '$blockname'",
                $throwException,
                null
            );
        }
        if ($startBlockEnd == $endBlockEnd) { // inline block
            $startBlockStart = $startTagPos;
            $startBlockEnd = $startTagPos + strlen($startSearch);
            $endBlockStart = $endTagPos;
            $endBlockEnd = $endTagPos + strlen($endSearch);
        }
        $xmlBlock = $this->getSliceSearch($this->tempDocumentMainPart, $startBlockEnd, $endBlockStart);
        if ($replace !== false) {
            if ($replace === true) {
                $replace = static::cloneSlice($xmlBlock, $clones, $incrementVariables);
            }
            $this->tempDocumentMainPart =
                $this->getSliceSearch($this->tempDocumentMainPart, 0, $startBlockStart)
                . $replace
                . $this->getSliceSearch($this->tempDocumentMainPart, $endBlockEnd);
            return true;
        }
        return $xmlBlock;
    }
    /**
     * Clone a block.
     *
     * @param string  $blockname The blockname without '${}', it will search for '${BLOCKNAME}' and '${/BLOCKNAME}
     * @param int $clones How many times the block needs to be cloned
     * @param bool $incrementVariables true by default (variables get appended #1, #2 inside the cloned blocks)
     * @param bool $throwException false by default (it then returns false or null on errors)
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @return mixed True if successful, false ($blockname not found) or null (no paragraph found)
     */
    public function cloneBlock(
        $blockname,
        $clones = 1,
        $incrementVariables = true,
        $throwException = false
    ) {
        return $this->processBlock($blockname, $clones, true, $incrementVariables, $throwException);
    }
    /**
     * Get a block. (first block found)
     *
     * @param string $blockname The blockname without '${}'
     * @param bool $throwException false by default
     *
     * @return mixed a string when $blockname is found, false ($blockname not found) or null (no paragraph found)
     */
    public function getBlock($blockname, $throwException = false)
    {
        return $this->processBlock($blockname, 0, false, false, $throwException);
    }
    /**
     * Replace a block.
     *
     * @param string $blockname The name of the macro start and end (without the macro marker ${})
     * @param string $replacement The replacement xml
     * @param bool $throwException false by default
     *
     * @return mixed false-ish on no replacement, true-ish on replacement
     */
    public function replaceBlock($blockname, $replacement = '', $throwException = false)
    {
        return $this->processBlock($blockname, 0, (string) $replacement, false, $throwException);
    }
    /**
     * Delete a block of text.
     *
     * @param string $blockname
     *
     * @return mixed true-ish on block found and deleted, falseish on block not found
     */
    public function deleteBlock($blockname)
    {
        return $this->replaceBlock($blockname, '', false);
    }
    /**
     * process a segment.
     *
     * @param string $needle  If this is a macro, you need to add the ${} around it yourself
     * @param string $xmltag  an xml tag without brackets, for example:  w:p
     * @param int $direction  in which direction should be searched. -1 left, 1 right. Default 0: around
     * @param int $clones  How many times the segment needs to be cloned
     * @param string $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:1' (first header)
     * @param mixed $replace true (default/cloneSegment) false(getSegment) string(replaceSegment) function(callback)
     * @param bool $incrementVariables true by default (variables get appended #1, #2 inside the cloned blocks)
     * @param bool $throwException false by default (it then returns false or null on errors)
     *
     * @return mixed The segment(getSegment), false (no $needle), null (no tags), true (clone/replace)
     */
    public function processSegment(
        $needle,
        $xmltag,
        $direction = self::SEARCH_AROUND,
        $clones = 1,
        $docPart = 'MainPart',
        $replace = true,
        $incrementVariables = true,
        $throwException = false
    ) {
        $docPart = preg_split('/:/', $docPart);
        if (count($docPart) > 1) {
            $part = &$this->{'tempDocument' . $docPart[0]}[$docPart[1]];
        } else {
            $part = &$this->{'tempDocument' . $docPart[0]};
        }
        $needlePos = strpos($part, $needle);
        if ($needlePos === false) {
            return $this->failGraciously(
                "Can not find macro '$needle', text not found or text contains markup.",
                $throwException,
                false
            );
        }
        $directionStart = $direction == self::SEARCH_RIGHT ? 'findOpenTagRight' : 'findOpenTagLeft';
        $directionEnd = $direction == self::SEARCH_LEFT ? 'findCloseTagLeft' : 'findCloseTagRight';
        $segmentStart = $this->{$directionStart}($part, "<$xmltag>", $needlePos, $throwException);
        $segmentEnd = $this->{$directionEnd}($part, "</$xmltag>", $needlePos, $throwException);
        if ($segmentStart >= $segmentEnd && $segmentEnd) {
            if ($direction == self::SEARCH_RIGHT) {
                $segmentEnd = $this->findCloseTagRight($part, "</$xmltag>", $segmentStart);
            } else {
                $segmentStart = $this->findOpenTagLeft($part, "<$xmltag>", $segmentEnd - 1, $throwException);
            }
        }
        if (!$segmentStart || !$segmentEnd) {
            return $this->failGraciously(
                "Can not find <$xmltag> ($segmentStart,$segmentEnd) around segment '$needle'",
                $throwException,
                null
            );
        }
        $xmlSegment = $this->getSliceSearch($part, $segmentStart, $segmentEnd);
        while (is_callable($replace)) {
            $replace = $replace($xmlSegment, $segmentStart, $segmentEnd, $part);
        }
        if ($replace !== false) {
            if ($replace === true) {
                $replace = static::cloneSlice($xmlSegment, $clones, $incrementVariables);
            }
            $part =
                $this->getSliceSearch($part, 0, $segmentStart)
                . $replace
                . $this->getSliceSearch($part, $segmentEnd);
            return true;
        }
        return $xmlSegment;
    }
    /**
     * Clone a segment.
     *
     * @param string  $needle  If this is a macro, you need to add the ${} around it yourself
     * @param string  $xmltag  an xml tag without brackets, for example:  w:p
     * @param int $direction in which direction should be searched. -1 left, 1 right. Default 0: around
     * @param int $clones  How many times the segment needs to be cloned
     * @param string $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:1' (first header)
     * @param bool $incrementVariables true by default (variables get appended #1, #2 inside the cloned blocks)
     * @param bool $throwException false by default (it then returns false or null on errors)
     *
     * @return mixed Returns true when succesfully cloned, false (no $needle found), null (no tags found)
     */
    public function cloneSegment(
        $needle,
        $xmltag,
        $direction = self::SEARCH_AROUND,
        $clones = 1,
        $docPart = 'MainPart',
        $incrementVariables = true,
        $throwException = false
    ) {
        return $this->processSegment(
            $needle,
            $xmltag,
            $direction,
            $clones,
            $docPart,
            true,
            $incrementVariables,
            $throwException
        );
    }
    /**
     * Get a segment. (first segment found)
     *
     * @param string $needle If this is a macro, you need to add the ${} around it yourself
     * @param string $xmltag an xml tag without brackets, for example:  w:p
     * @param int $direction in which direction should be searched. -1 left, 1 right. Default 0: around
     * @param string $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:1' (first header)
     * @param bool $throwException false by default (it then returns false or null on errors)
     *
     * @return mixed Segment String, false ($needle not found) or null (no tags found around $needle)
     */
    public function getSegment($needle, $xmltag, $direction = 0, $docPart = 'MainPart', $throwException = false)
    {
        return $this->processSegment($needle, $xmltag, $direction, 0, $docPart, false, false, $throwException);
    }
    /**
     * Replace a segment.
     *
     * @param string $needle If this is a macro, you need to add the ${} around it yourself
     * @param string $xmltag an xml tag without brackets, for example:  w:p
     * @param int $direction in which direction should be searched. -1 left, 1 right. Default 0: around
     * @param string $replacement The replacement xml string. Be careful and keep the xml uncorrupted.
     * @param string $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:2' (second header)
     * @param bool $throwException false by default (it then returns false or null on errors)
     *
     * @return mixed true (replaced), false ($needle not found) or null (no tags found around $needle)
     */
    public function replaceSegment(
        $needle,
        $xmltag,
        $direction = self::SEARCH_AROUND,
        $replacement = '',
        $docPart = 'MainPart',
        $throwException = false
    ) {
        return $this->processSegment(
            $needle,
            $xmltag,
            $direction,
            0,
            $docPart,
            (string) $replacement,
            false,
            $throwException
        );
    }
    /**
     * Delete a segment.
     *
     * @param string $needle If this is a macro, you need to add the ${} yourself
     * @param string $xmltag an xml tag without brackets, for example:  w:p
     * @param int $direction in which direction should be searched. -1 left, 1 right. Default 0: around
     * @param string $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:1' (second header)
     *
     * @return mixed true (segment deleted), false ($needle not found) or null (no tags found around $needle)
     */
    public function deleteSegment($needle, $xmltag, $direction = self::SEARCH_AROUND, $docPart = 'MainPart')
    {
        return $this->replaceSegment($needle, $xmltag, $direction, '', $docPart, false);
    }
    /**
     * Saves the result document.
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return string The filename of the document
     */
    public function save()
    {
        foreach ($this->tempDocumentHeaders as $index => $xml) {
            $this->zipClass->addFromString($this->getHeaderName($index), $xml);
        }
        $this->zipClass->addFromString($this->getMainPartName(), $this->tempDocumentMainPart);

        foreach ($this->tempDocumentFooters as $index => $xml) {
            $this->zipClass->addFromString($this->getFooterName($index), $xml);
        }

        //main rels
        if($this->_main_rels!="")
        {
            $this->zipClass->addFromString('word/_rels/document.xml.rels', $this->_main_rels);
        }

        //header rels
        foreach($this->_header_rels as $index=>$rel){
            $this->zipClass->addFromString('word/_rels/header'.$index.'.xml.rels', $rel);
        }

        //footer rels
        foreach($this->_footer_rels as $index=>$rel){
            $this->zipClass->addFromString('word/_rels/footer'.$index.'.xml.rels', $rel);
        }

        //types
        if($this->_types!="")
        {
            $this->zipClass->addFromString('[Content_Types].xml', $this->_types);
        }

        // Close zip file
        if (false === $this->zipClass->close()) {
            throw new Exception('Could not close zip file.');
        }
        return $this->tempDocumentFilename;
    }
    /**
     * Saves the result document to the user defined file.
     *
     * @since 0.8.0
     *
     * @param string $fileName
     *
     * @return void
     */
    public function saveAs($fileName)
    {
        $tempFileName = $this->save();
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        /*
         * Note: we do not use `rename` function here, because it looses file ownership data on Windows platform.
         * As a result, user cannot open the file directly getting "Access denied" message.
         *
         * @see https://github.com/PHPOffice/PHPWord/issues/532
         */
        copy($tempFileName, $fileName);
        unlink($tempFileName);
    }
    /**
     * Finds parts of broken macros and sticks them together.
     * Macros, while being edited, could be implicitly broken by some of the word processors.
     * In order to limit side-effects, we limit matches to only inside (inner) paragraphs
     *
     * @param string $documentPart The document part in XML representation
     *
     * @return string
     */
    protected function fixBrokenMacros($documentPart)
    {
        $paragraphs = preg_split(
            '@(</?w:p\b[^>]*>)@',
            $documentPart,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );
        foreach ($paragraphs as &$paragraph) {
            $paragraph = preg_replace_callback(
                '|\$(?:<[^\${}]+>)?\{[^{}]*\}|U',
                function ($match) {
                    return strip_tags($match[0]);
                },
                $paragraph
            );
        }
        return implode('', $paragraphs);
    }
    /**
     * Find and replace macros in the given XML section.
     *
     * @param mixed $search
     * @param mixed $replace
     * @param mixed $documentPartXML Array or string (Header/Footer)
     * @param int $limit
     *
     * @return mixed
     */
    protected function setValueForPart($search, $replace, $documentPartXML, $limit)
    {
        // Shift-Enter
        if (is_array($replace)) {
            foreach ($replace as &$item) {
                $item = preg_replace('~\R~u', '</w:t><w:br/><w:t>', $item);
            }
            unset($item);
        } else {
            $replace = preg_replace('~\R~u', '</w:t><w:br/><w:t>', $replace);
        }
        // Note: we can't use the same function for both cases here, because of performance considerations.
        if (self::MAXIMUM_REPLACEMENTS_DEFAULT === $limit) {
            return str_replace($search, $replace, $documentPartXML);
        }
        $regExpEscaper = new RegExp();
        return preg_replace($regExpEscaper->escape($search), $replace, $documentPartXML, $limit);
    }
    /**
     * Get the name of the header file for $index.
     *
     * @param int $index
     *
     * @return string
     */
    protected function getHeaderName($index)
    {
        return sprintf('word/header%d.xml', $index);
    }
    /**
     * @return string
     */
    protected function getMainPartName()
    {
        return 'word/document.xml';
    }
    /**
     * Get the name of the footer file for $index.
     *
     * @param int $index
     *
     * @return string
     */
    protected function getFooterName($index)
    {
        return sprintf('word/footer%d.xml', $index);
    }
    /**
     * Find the start position of the nearest tag before $offset.
     *
     * @param string $searchString The string we are searching in (the mainbody or an array element of Footers/Headers)
     * @param string $tag  Fully qualified tag, for example: '<w:p>' (with brackets!)
     * @param int $offset Do not look from the beginning, but starting at $offset
     * @param bool $throwException
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @return int Zero if not found (due to the nature of xml, your document never starts at 0)
     */
    protected function findOpenTagLeft(&$searchString, $tag, $offset = 0, $throwException = false)
    {
        $tagStart = strrpos(
            $searchString,
            substr($tag, 0, -1) . ' ',
            ((strlen($searchString) - $offset) * -1)
        );
        if ($tagStart === false) {
            $tagStart = strrpos(
                $searchString,
                $tag,
                ((strlen($searchString) - $offset) * -1)
            );
            if ($tagStart === false) {
                return $this->failGraciously(
                    'Can not find the start position of the item to clone.',
                    $throwException,
                    0
                );
            }
        }
        return $tagStart;
    }
    /**
     * Find the start position of the nearest tag before $offset.
     *
     * @param string  $searchString The string we are searching in (the mainbody or an array element of Footers/Headers)
     * @param string  $tag  Fully qualified tag, for example: '<w:p>' (with brackets!)
     * @param int $offset Do not look from the beginning, but starting at $offset
     * @param bool $throwException
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @return int Zero if not found (due to the nature of xml, your document never starts at 0)
     */
    protected function findOpenTagRight(&$searchString, $tag, $offset = 0, $throwException = false)
    {
        $tagStart = strpos(
            $searchString,
            substr($tag, 0, -1) . ' ',
            $offset
        );
        if ($tagStart === false) {
            $tagStart = strrpos(
                $searchString,
                $tag,
                $offset
            );
            if ($tagStart === false) {
                return $this->failGraciously(
                    'Can not find the start position of the item to clone.',
                    $throwException,
                    0
                );
            }
        }
        return $tagStart;
    }
    /**
     * Find the end position of the nearest $tag after $offset.
     *
     * @param string $searchString The string we are searching in (the MainPart or an array element of Footers/Headers)
     * @param string $tag  Fully qualified tag, for example: '</w:p>'
     * @param int $offset Do not look from the beginning, but starting at $offset
     *
     * @return int Zero if not found
     */
    protected function findCloseTagLeft(&$searchString, $tag, $offset = 0)
    {
        $pos = strrpos($searchString, $tag, ((strlen($searchString) - $offset) * -1));
        if ($pos !== false) {
            return $pos + strlen($tag);
        }
        return 0;
    }
    /**
     * Find the end position of the nearest $tag after $offset.
     *
     * @param string  $searchString The string we are searching in (the MainPart or an array element of Footers/Headers)
     * @param string  $tag  Fully qualified tag, for example: '</w:p>'
     * @param int $offset Do not look from the beginning, but starting at $offset
     *
     * @return int Zero if not found
     */
    protected function findCloseTagRight(&$searchString, $tag, $offset = 0)
    {
        $pos = strpos($searchString, $tag, $offset);
        if ($pos !== false) {
            return $pos + strlen($tag);
        }
        return 0;
    }
    /**
     * Get a slice of a string.
     *
     * @param string $searchString The string we are searching in (the MainPart or an array element of Footers/Headers)
     * @param int $startPosition
     * @param int $endPosition
     *
     * @return string
     */
    protected function getSliceSearch(&$searchString, $startPosition, $endPosition = 0)
    {
        if (!$endPosition) {
            $endPosition = strlen($searchString);
        }
        return substr($searchString, $startPosition, ($endPosition - $startPosition));
    }

}