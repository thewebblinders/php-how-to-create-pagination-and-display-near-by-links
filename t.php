<?php
include './PaginationLinks.php';
class DemoPagination
{

    /**
     *
     * @param number $requestedPage - current request page number
     * @param number $totalResults  - total number of results , this value may come from a database
     * @param number $nearbyLinks   - specifies how many pagination links to be displayed
     * @param number $numberOfResultsPerPage - number of results you want to display for each page
     * @param string $linksFormat   - specifies how pagination links should be displayed (HTML string)
     * @param string $currentPageLinkFormat - specifies how current page link should be displayed (HTML string - you could use this to highlight current page from the rest of the links)
     */
    public function getPagination(
        $requestedPage,
        $totalResults,
        $nearbyLinks = 5,
        $numberOfResultsPerPage=5,
        $linksFormat = '<a href="?page=%d">%d</a>',
        $currentPageLinkFormat = '<a class="currentPage" href="#">%d</a>') {

        $requestedPage=$this->validatePageNumber($requestedPage,$totalResults,$numberOfResultsPerPage);

        return PaginationLinks::create(
            $requestedPage,
            $this->getTotalPages($totalResults,$numberOfResultsPerPage),
            $nearbyLinks,
            $linksFormat,
            $currentPageLinkFormat
        ); 

    }

    /*
    If the requested page number is not in available page numbers range
    or
    if page number is not set or empty
    we will set page number to 1 (1st page)
     */
    public function validatePageNumber($requestedPage,$totalResults,$numberOfResultsPerPage)
    {
        try{
            return (!isset($requestedPage) || empty($requestedPage)  || !($this->isPositiveInteger($requestedPage)) || (ceil($totalResults / $numberOfResultsPerPage) < $requestedPage)) ? 1 : $requestedPage;
        }
        catch(Exception $e){
            return 1;
        }
    }


  

    /**
     * Returns total pages that can be created based on number of results and number of results per page
     */
    public function getTotalPages($totalResults,$numberOfResultsPerPage){
        return ceil($totalResults/$numberOfResultsPerPage);
    }

    // Used to make sure the requested page is positive
    public function isPositiveInteger($val)
    {
        $filter_options = array(
            'options' => array('min_range' => 0),
        );

        return filter_var($val, FILTER_VALIDATE_INT, $filter_options);
    }
}

$dp=new DemoPagination();
echo <<<DEMO
<!DOCTYPE html>
<html>
<head>
    <title> PAGINATION LINKS </title>
    <style>
      a , button{
          display:inline-block;
          margin:0.5em;
          padding:0.5em;
          background:aquamarine;
          border:2px solid black;
          color:black;
          text-decoration:none;
          cursor:pointer;
      }
      .currentPage{
          background-color:orange;
          color:white;
      }
    </style>
</head>
<body> 
   <div>
    <p>Example 1 :</p>
    {$dp->getPagination(2,45)}
   </div>
   <div> 
   <p>Example 2 :</p>
    {$dp->getPagination(3,60)}
   </div> 
   <div> 
   <p>Example 3 :</p>

    {$dp->getPagination(10,1000,10)}
   </div> 
   <div> 
   <p>Example 4 :</p>

    {$dp->getPagination(10,1000,10,5,'<button data-page="?page=%d">%d</button>','<button class="currentPage" data-page="?page=%d">%d</button>')}
   </div>  
   <div> 
   <p>Example 5 :</p>

   {$dp->getPagination(2,30,10,5,'<a href="./page=%d">PAGE %d</a>','<a class="currentPage" href="./page=%d">PAGE %d</a>')}
  </div>  
</body>
</html> 
DEMO;
