<?php
/**

//사용법
//기본적으로 들어가는 부분
include "./XMLparse.php";                       // 클래스 파일 include
$xml = file_get_contents("./ex.xml");         // 파싱할 대상XML 가져오기
$parser = new XMLParser($xml);             // 객체생성 parser라는 객체를 생성함
$parser->Parse();                                  // Parse()메소를 호출하여 xml을 dom 방식으로 파싱함

//파싱된 xml결과값을 사용하는 방법
echo $parser->document->widgetprefs[0]->title[0]->tagData;
 // 타이틀 데이터를 가져올때 (하나의 데이터를 지정해서 가져올 경우)
// "위젯 제목을 제공하는~~" 출력됨
echo $parser->document->content[0]->tagAttrs['src'];
 // 속성값 가져오기
 // "index.html"이 출력됨 
echo $parser->GenerateXML();               
// 위 ex.xml과 똑같은 xml 문서가 출력됨

**/

class XMLParser 
{
    /**
     * The XML parser
     *
     * @var resource
     */
    private $parser;

    /**
    * The XML document
    *
    * @var string
    */
    private $xml;

    /**
    * Document tag
    *
    * @var object
    */
    public $document;

    /**
    * Current object depth
    *
    * @var array
    */
    private $stack;
    
    /**
     * Whether or not to replace dashes and colons in tag
     * names with underscores.
     * 
     * @var bool
     */
    private $cleanTagNames;

    
    /**
     * Constructor. Loads XML document.
     *
     * @param string $xml The string of the XML document
     * @return XMLParser
     */
    function __construct($xml = '', $cleanTagNames = true)
    {
        //Load XML document
        $this->xml = $xml;

        //Set stack to an array
        $this->stack = array();
        
        //Set whether or not to clean tag names
        $this->cleanTagNames = $cleanTagNames;
    }

    /**
     * Initiates and runs PHP's XML parser
     */
    public function Parse()
    {
        //Create the parser resource
        $this->parser = xml_parser_create();
        
        //Set the handlers
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'StartElement', 'EndElement');
        xml_set_character_data_handler($this->parser, 'CharacterData');

        //Error handling
        if (!xml_parse($this->parser, $this->xml))
            $this->HandleError(xml_get_error_code($this->parser), xml_get_current_line_number($this->parser), xml_get_current_column_number($this->parser));

        //Free the parser
        xml_parser_free($this->parser);
    }
    
    /**
     * Handles an XML parsing error
     *
     * @param int $code XML Error Code
     * @param int $line Line on which the error happened
     * @param int $col Column on which the error happened
     */
    private function HandleError($code, $line, $col)
    {
        trigger_error('XML Parsing Error at '.$line.':'.$col.'. Error '.$code.': '.xml_error_string($code));
    }

    
    /**
     * Gets the XML output of the PHP structure within $this->document
     *
     * @return string
     */
    public function GenerateXML()
    {
        return $this->document->GetXML();
    }

    /**
     * Gets the reference to the current direct parent
     *
     * @return object
     */
    private function GetStackLocation()
    {
        //Returns the reference to the current direct parent
        return end($this->stack);
    }

    /**
     * Handler function for the start of a tag
     *
     * @param resource $parser
     * @param string $name
     * @param array $attrs
     */
    private function StartElement($parser, $name, $attrs = array())
    {
        //Make the name of the tag lower case
        $name = strtolower($name);
        
        //Check to see if tag is root-level
        if (count($this->stack) == 0) 
        {
            //If so, set the document as the current tag
            $this->document = new XMLTag($name, $attrs);

            //And start out the stack with the document tag
            $this->stack = array(&$this->document);
        }
        //If it isn't root level, use the stack to find the parent
        else
        {
            //Get the reference to the current direct parent
            $parent = $this->GetStackLocation();
            
            $parent->AddChild($name, $attrs, count($this->stack), $this->cleanTagNames);

            //If the cleanTagName feature is on, clean the tag names
            if($this->cleanTagNames)
                $name = str_replace(array(':', '-'), '_', $name);

            //Update the stack
            $this->stack[] = end($parent->$name);        
        }
    }

    /**
     * Handler function for the end of a tag
     *
     * @param resource $parser
     * @param string $name
     */
    private function EndElement($parser, $name)
    {
        //Update stack by removing the end value from it as the parent
        array_pop($this->stack);
    }

    /**
     * Handler function for the character data within a tag
     *
     * @param resource $parser
     * @param string $data
     */
    private function CharacterData($parser, $data)
    {
        //Get the reference to the current parent object
        $tag = $this->GetStackLocation();

        //Assign data to it
        $tag->tagData .= trim($data);
    }
}



class XMLTag
{
    /**
     * Array with the attributes of this XML tag
     *
     * @var array
     */
    public $tagAttrs;
    
    /**
     * The name of the tag
     *
     * @var string
     */
    public $tagName;
    
    /**
     * The data the tag contains 
     * 
     * So, if the tag doesn't contain child tags, and just contains a string, it would go here
     *
     * @var stat
     */
    public $tagData;
    
    /**
     * Array of references to the objects of all direct children of this XML object
     *
     * @var array
     */
    public $tagChildren;
    
    /**
     * The number of parents this XML object has (number of levels from this tag to the root tag)
     *
     * Used presently only to set the number of tabs when outputting XML
     *
     * @var int
     */
    public $tagParents;
    
    /**
     * Constructor, sets up all the default values
     *
     * @param string $name
     * @param array $attrs
     * @param int $parents
     * @return XMLTag
     */
    function __construct($name, $attrs = array(), $parents = 0)
    {
        //Make the keys of the attr array lower case, and store the value
        $this->tagAttrs = array_change_key_case($attrs, CASE_LOWER);
        
        //Make the name lower case and store the value
        $this->tagName = strtolower($name);
        
        //Set the number of parents
        $this->tagParents = $parents;
        
        //Set the types for children and data
        $this->tagChildren = array();
        $this->tagData = '';
    }
    
    /**
     * Adds a direct child to this object
     *
     * @param string $name
     * @param array $attrs
     * @param int $parents
     * @param bool $cleanTagName
     */
    public function AddChild($name, $attrs, $parents, $cleanTagName = true)
    {    
        //If the tag is a reserved name, output an error
        if(in_array($name, array('tagChildren', 'tagAttrs', 'tagParents', 'tagData', 'tagName')))
        {
            trigger_error('You have used a reserved name as the name of an XML tag. Please consult the documentation (http://www.criticaldevelopment.net/xml/) and rename the tag named "'.$name.'" to something other than a reserved name.', E_USER_ERROR);

            return;
        }

        //Create the child object itself
        $child = new XMLTag($name, $attrs, $parents);

        //If the cleanTagName feature is on, replace colons and dashes with underscores
        if($cleanTagName)
            $name = str_replace(array(':', '-'), '_', $name);
        
        //Toss up a notice if someone's trying to to use a colon or dash in a tag name
        elseif(strstr($name, ':') || strstr($name, '-'))
            trigger_error('Your tag named "'.$name.'" contains either a dash or a colon. Neither of these characters are friendly with PHP variable names, and, as such, you may have difficulty accessing them. You might want to think about enabling the cleanTagName feature (pass true as the second argument of the XMLParser constructor). For more details, see http://www.criticaldevelopment.net/xml/', E_USER_NOTICE);
        
        //If there is no array already set for the tag name being added, 
        //create an empty array for it
        if(!isset($this->$name))
            $this->$name = array();
        
        //Add the reference of it to the end of an array member named for the tag's name
        $this->{$name}[] = &$child;
        
        //Add the reference to the children array member
        $this->tagChildren[] = &$child;
        
        //Return a reference to this object for the stack
        return $this;
    }
    
    /**
     * Returns the string of the XML document which would be generated from this object
     * 
     * This function works recursively, so it gets the XML of itself and all of its children, which
     * in turn gets the XML of all their children, which in turn gets the XML of all thier children,
     * and so on. So, if you call GetXML from the document root object, it will return a string for 
     * the XML of the entire document.
     * 
     * This function does not, however, return a DTD or an XML version/encoding tag. That should be
     * handled by XMLParser::GetXML()
     *
     * @return string
     */
    public function GetXML()
    {
        //Start a new line, indent by the number indicated in $this->parents, add a <, and add the name of the tag
        $out = "\n".str_repeat("\t", $this->tagParents).'<'.$this->tagName;

        //For each attribute, add attr="value"
        foreach($this->tagAttrs as $attr => $value)
            $out .= ' '.$attr.'="'.$value.'"';
        
        //If there are no children and it contains no data, end it off with a />
        if(empty($this->tagChildren) && empty($this->tagData))
            $out .= " />";
        
        //Otherwise...
        else
        {    
            //If there are children
            if(!empty($this->tagChildren))
            {
                //Close off the start tag
                $out .= '>';
                
                //For each child, call the GetXML function (this will ensure that all children are added recursively)
                foreach($this->tagChildren as $child)
                    $out .= $child->GetXML();

                //Add the newline and indentation to go along with the close tag
                $out .= "\n".str_repeat("\t", $this->tagParents);
            }
            
            //If there is data, close off the start tag and add the data
            elseif(!empty($this->tagData))
                $out .= '>'.$this->tagData;
            
            //Add the end tag    
            $out .= '</'.$this->tagName.'>';
        }
        
        //Return the final output
        return $out;
    }
    
    /**
     * Deletes this tag's child with a name of $childName and an index
     * of $childIndex
     *
     * @param string $childName
     * @param int $childIndex
     */
    public function Delete($childName, $childIndex = 0)
    {
        //Delete all of the children of that child
        $this->{$childName}[$childIndex]->DeleteChildren();
        
        //Destroy the child's value
        $this->{$childName}[$childIndex] = null;
        
        //Remove the child's name from the named array
        unset($this->{$childName}[$childIndex]);
        
        //Loop through the tagChildren array and remove any null
        //values left behind from the above operation
        for($x = 0; $x < count($this->tagChildren); $x ++)
        {
            if(is_null($this->tagChildren[$x]))
                unset($this->tagChildren[$x]);
        }
    }
    
    /**
     * Removes all of the children of this tag in both name and value
     */
    private function DeleteChildren()
    {
        //Loop through all child tags
        for($x = 0; $x < count($this->tagChildren); $x ++)
        {
            //Do this recursively
            $this->tagChildren[$x]->DeleteChildren();
            
            //Delete the name and value
            $this->tagChildren[$x] = null;
            unset($this->tagChildren[$x]);
        }
    }
}
?> 