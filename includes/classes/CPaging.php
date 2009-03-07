<?php
/*
 *******************************************************************************************
 * Class Name:  CPaging
 *------------------------------------------------------------------------------------------
 * Mod History: Jaisen Mathai (February 6, 2003)
 *------------------------------------------------------------------------------------------
 * Paging class, displays running page numbers to traverse resultsets (usually)
 *
 * Usage:
 *   include('/export/home/jacor-common/classes/CPaging.php');
 *    $page = new CPaging($page, 15, 25, $PHP_SELF, 'page');
 *    echo $page->getPages();
 * 
 ******************************************************************************************
 */

class CPaging
{
 /*
  *******************************************************************************************
  * Name
  *   getPages
  * Description
  *   generate string of links for pages
  *
  * Output
  *   string
  ******************************************************************************************
  */
  function getPages()
  {
    $retval     = '';
    $variance   = floor($this->pagestodisplay / 2); // determines half of what's to be displayed - to determine "padding" on each side of current page
    $endpage    = ($this->currentpage <= $variance) ? ($this->pagestodisplay) : ($this->currentpage + $variance);
    $styleclass = (strlen($this->styleclass)   > 0) ? " class=\"{$this->styleclass}\"" : "";
    $styleinline= (strlen($this->styleinline)  > 0) ? " style=\"{$this->styleclass}\"" : "";
    
    if($this->currentpage <= $variance)
    {
      $startpage  = 1;
    }
    else
    {
      $startpage  = $this->currentpage - $variance;
    }
    
    // adjust left padding as page number approaches $endpage
    //echo "{$this->currentpage} + $variance > {$this->maxpages}<br/>{$this->currentpage} - ({$this->pagestodisplay} - ({$this->maxpages} - {$this->currentpage})";
    if(($this->currentpage + $variance) >= $this->maxpages)
    {
      $startpage = $this->currentpage - ($this->pagestodisplay - ($this->maxpages - $this->currentpage + 1)); // add one to retain $this->maxpages
    }
    
    if($startpage < 1)
    {
      $startpage = 1;
    }
    
    for($i=$startpage; ($i<=$endpage && $i<=$this->maxpages); $i++)
    {
      if($this->currentpage != $i)
      {
      if($this->linktype == 'html')
      {
          $retval .= str_replace(
                          array('{linkfile}','{linkgetvars}','{pagevarname}','{i}','{styleclass}','{styleinline}','{idisplay}'),
                          array($this->linkfile,$this->linkgetvars,$this->pagevarname,$i,$this->styleclass,$this->styleinline,$i),
                          $this->linktemplate
                        );
        }
        else
        if($this->linktype == 'js')
        {
          $retval .= str_replace(
                          array('{functionname}','{parameters}','{pagevarname}','{i}','{styleclass}','{styleinline}','{idisplay}'),
                          array($this->functionname,$this->parameters,$this->pagevarname,$i,$this->styleclass,$this->styleinline,$i),
                          $this->jslinktemplate
                        );
        }
      }
      else
      {
        if($this->styleclass != '')
        {
          $retval .= '<span class="' . $this->styleclass . '">' . $i . '&nbsp;</span>';
        }
        else
        if($this->styleinline != '')
        {
          $retval .= '<span style="' . $this->styleinline . '">' . $i . '&nbsp;</span>';
        }
        else
        {
          $retval .= $i . '&nbsp;';
        }
      }
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   
  * Description
  *   Generates link to next page / $reverse = true makes this return the previous page
  *
  * Output
  *   string
  ******************************************************************************************
  */
  function getNextPage($text = 'Next', $mode = 'next')
  {
    $retval = '';
    //echo $mode;
    if(($mode === 'next' && $this->currentpage < $this->maxpages) || ($mode === 'previous' && $this->currentpage > 1) || ($mode == 'first' || $mode == 'last'))
    {
      switch($mode)
      {
        case 'previous':
          $pagenum = $this->currentpage-1;
          break;
        case 'first':
          $pagenum = 1;
          break;
        case 'last':
          $pagenum = $this->maxpages;
          break;
        case 'next':
        default:
          $pagenum = $this->currentpage+1;
          break;
      }
      
      if($this->linktype == 'html')
      {
        $retval = str_replace(
                        array('{linkfile}','{linkgetvars}','{pagevarname}','{i}','{styleclass}','{styleinline}','{idisplay}'),
                        array($this->linkfile,$this->linkgetvars,$this->pagevarname,$pagenum,$this->styleclass,$this->styleinline,$text),
                        $this->linktemplate
                      );
      }
      else
      if($this->linktype == 'js')
      {
        $retval = str_replace(
                        array('{functionname}','{parameters}','{pagevarname}','{i}','{styleclass}','{styleinline}','{idisplay}'),
                        array($this->functionname,$this->parameters,$this->pagevarname,$pagenum,$this->styleclass,$this->styleinline,$text),
                        $this->jslinktemplate
                      );
      }
    }
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getPrevPage
  * Description
  *   alias to getNextPage
  *
  * Output
  *   string
  ******************************************************************************************
  */
  function getPrevPage($text = 'Prev')
  {
    return $this->getNextPage($text, 'previous');
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getFirstPage
  * Description
  *   alias to getNextPage
  *
  * Output
  *   string
  ******************************************************************************************
  */
  function getFirstPage($text = '1')
  {
    return $this->getNextPage($text, 'first');
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getLastPage
  * Description
  *   alias to getNextPage
  *
  * Output
  *   string
  ******************************************************************************************
  */
  function getLastPage($text = 'last')
  {
    return $this->getNextPage($text, 'last');
  }
  
 /*
  *******************************************************************************************
  * Name
  *   setHtmlParams
  * Description
  *   set html parameters
  *
  * Output
  *   bool
  ******************************************************************************************
  */
  function setHtmlParams($pagevarname='page', $linkfile='', $linkgetvars='', $styleclass='', $styleinline='')
  {
    $this->pagevarname    = $pagevarname;
    $this->linkfile       = strlen($linkfile) == 0 ? $PHP_SELF : $linkfile;
    $this->linkgetvars    = $this->stripPage($linkgetvars);
    $this->styleclass     = $styleclass;
    $this->styleinline    = $styleinline;
    
    return true;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   setJsParams
  * Description
  *   set javascript parameters
  *
  * Output
  *   bool
  ******************************************************************************************
  */
  function setJsParams($functionname=false, $parameters='', $styleclass='', $styleinline='')
  {
    $this->functionname   = $functionname;
    $this->parameters     = $parameters;
    $this->styleclass     = $styleclass;
    $this->styleinline    = $styleinline;
    
    return true;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   setLinkType
  * Description
  *   set links to javascript
  *
  * Output
  *   bool
  ******************************************************************************************
  */
  function setLinkType($str = false)
  {
    if($str != false)
    {
      $this->linktype = $str;
    }
    
    return true;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   stripPage
  * Description
  *   strips page variable from $this->linkgetvars string
  *
  * Output
  *   string
  ******************************************************************************************
  */
  function stripPage($string)
  {
    $retval = preg_replace(array("/\&{$this->pagevarname}=\\d{1,5}/", "/{$this->pagevarname}=\\d{1,5}/"), "", $string);
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   CPaging
  * Description
  *   constructor, sets initial variables
  *
  * Output
  *   n/a
  ******************************************************************************************
  */
  function CPaging($currentpage=1, $pagestodisplay=16, $totalpages=16, $pagevarname='page', $linkfile=false, $linkgetvars=false, $styleclass='', $styleinline='')
  {
    $this->currentpage    = (int)$currentpage;
    $this->pagestodisplay = (int)$pagestodisplay;
    $this->maxpages       = (int)$totalpages;
    $this->pagevarname    = $pagevarname;
    $this->linkfile       = strlen($linkfile) == 0 ? $_SERVER['PHP_SELF'] : $linkfile;
    $this->linkgetvars    = strlen($linkgetvars) == 0 ? $this->stripPage($_SERVER['QUERY_STRING']) : $this->stripPage($linkgetvars);
    $this->styleclass     = $styleclass;
    $this->styleinline    = $styleinline;
    $this->linktype       = 'html';
    
    $this->linktemplate   = '<a href="{linkfile}?{linkgetvars}&{pagevarname}={i}" class="{styleclass}" {styleinline}>{idisplay}</a>&nbsp;';
    $this->jslinktemplate = '<a href="javascript:{functionname}({parameters}, {i});" {styleclass} {styleinline}>{idisplay}</a>&nbsp;';
  }
}
?>