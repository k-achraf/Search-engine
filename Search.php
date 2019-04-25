<?php


class Search
{
    /**
     * @var $url string
     * your website url
     */
    private $url = '';

    /**
     * @var $keyword string
     * keyword you want to search
     */
    private $keyword = '';

    /**
     * @var array $linksResult
     * the links result from google
     */
    public $linksResult = [];

    /**
     * @var array $errors
     * search errors
     */
    private $errors = [];

    /**
     * @var string $googleUrl
     * the complete google url with keyword
     */
    private $googleUrl;

    /**
     * @var mixed $page
     * include page with file get content
     */
    private $page;

    /**
     * @var object $html
     * html content of page as object
     */
    private $html;

    /**
     * @var array $details
     * get details from links list
     */
    private $details = [];

    /**
     * @var array $results
     * the final results
     */
    private $results = [];

    public function __construct()
    {
        ini_set('max_execution_time', 300);

        $this->html = new DOMDocument('1.0','utf-8');

        $this->html->validateOnParse=false;
        $this->html->preserveWhiteSpace=true;
        $this->html->strictErrorChecking=false;
        $this->html->substituteEntities=false;
        $this->html->recover=true;
        $this->html->formatOutput=true;

        $parse_errs=serialize( libxml_get_last_error() );
        libxml_clear_errors();
    }

    public function searchIn($url){
        $this->url = $url;
    }

    public function searchFor($keyword){
        $this->keyword = $keyword;
    }

    public function get(){
        if ($this->url == ''){
            $this->errors [] = 'invalid website url';
        }
        if ($this->keyword == ''){
            $this->errors [] = 'empty keyword';
        }

        if (!$this->getErrors()){
            $this->setUrl();
            $this->getPage();
            $this->getLinks();
//            $this->getDetails();
            $this->getResults();

            if (!empty($this->results)){
                return $this->results;
            }
            return 'no result for this keyword';
        }
        return $this->errors;
    }

    public function getErrors(){
        if (!empty($this->errors)){
            return $this->errors;
        }
        return false;
    }

    private function setUrl(){
        $this->googleUrl = 'https://www.google.com/search?q=' . urlencode('site:') . $this->url . '+' . urlencode($this->keyword);
    }

    private function getPage(){
        $this->page = file_get_contents($this->googleUrl);
    }

    private function getLinks(){

        @$this->html->loadHTML($this->page);

        $linkList = $this->html->getElementsByTagName('a');

        foreach ($linkList as $link){
            $lnk = explode('/' , $link->getAttribute('href'));
            foreach ($lnk as $l) {
                $a = explode('?', $l);

                if (strtolower($a[0]) == 'url') {
                    $list [] = $link->getAttribute('href');
                }
            }
        }


        if (!empty($list)){
            foreach ($list as $link){
                $lnk = explode('/' , $link);

                if (strtolower($lnk[2]) == 'settings' or strtolower($lnk[3]) == 'webcache.googleusercontent.com' or strtolower($lnk[3]) == 'www.youtube.com'){
                    continue;
                }
                $newList [] = $link;
            }
        }

        if (isset($newList) and !empty($newList)){
            foreach ($newList as $link){
                $lnk = explode('&sa=U&ved=' , $link);
                $lnk = $lnk[0];


                $lnk = explode('/url?q=' , $lnk);
                $lnk = $lnk[1];

                $lnk = str_replace('%23' , '#' , $lnk);
                $lnk = str_replace('%25' , '%' , $lnk);

                $this->linksResult [] = $lnk;
            }
        }
    }

//    private function getDetails(){
//        if (!empty($this->linksResult)){
//            foreach ($this->linksResult as $link){
//                $d = [];
//
//                $l = explode('/' , $link);
//
//                if (count($l) == 5){
//                    if (is_numeric($l[4])){
//                        $d['matiere'] = $l[4];
//                    }
//                }
//
//                if (count($l) == 6){
//                    if (is_numeric($l[4]) and is_numeric($l[5])){
//                        $d['matiere'] = $l[4];
//                        $d['lesson'] = $l[5];
//                    }
//                }
//
//                if (!empty($d)){
//                    $this->details [] = $d;
//                }
//            }
//        }
//    }

    private function getResults(){
        foreach ($this->linksResult as $link){
            $result = [];

//            $page = ;

            @$this->html->loadHTML(file_get_contents($link));

            $title = $this->html->getElementsByTagName('title');
            $title = $title->item(0)->nodeValue;

            $result['link'] = $link;
            $result['title'] = $title;
            $this->results [] = $result;

        }
    }
}