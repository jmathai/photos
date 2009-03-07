<?php
 /*******************************************************************************************
  * Name:  CFaq.php
  *
  * Class to handle Frequently Asked Questions
  *
  * Usage:
  * 
  *******************************************************************************************/
class CFaq {
 /*
  *******************************************************************************************
  * Description
  *   Method to retrieve faqs
  *
  * Input (one of the following combinations)
  *   $category           int   (album_id)
  * Output
  *   $return             array
  ******************************************************************************************
  */
  function faqs($category = false)
  {
    $return = array();
    
    $sql  = 'SELECT f_id AS F_ID, f_category as F_CATEGORY, f_question AS F_QUESTION, f_answer AS F_ANSWER, ' . LF
          . 'f_notes AS F_NOTES, f_keywords AS F_KEYWORDS, f_link AS F_LINK, f_dateCreated AS F_DATECREATED, ' . LF
          . 'f_dateModified AS F_DATEMODIFIED ' . LF
          . 'FROM faqs ' . LF
          . "WHERE f_active = 'Y' "
          . ($category !== false ? 'AND f_category = ' . $this->dbh->sql_safe($category) . ' ' : '') . ' '
          . 'ORDER BY f_category ';
    
    $rs = $this->dbh->query($sql);
    
    while($data = $this->dbh->fetch_assoc($rs))
    {
      $return[] = $data;
    }
    
    return $return;
  }
  
   /*
  *******************************************************************************************
  * Description
  *   Method to retrieve faqs by id
  *
  * Input (one of the following combinations)
  *   $id                 int
  * Output
  *   $return             array
  ******************************************************************************************
  */
  function faqData($id = false)
  {
    $return = array();
    if($id !== false)
    {
      $id = intval($id);
      
      $sql  = 'SELECT f_id AS F_ID, f_category as F_CATEGORY, f_question AS F_QUESTION, f_answer AS F_ANSWER, ' . LF
            . 'f_notes AS F_NOTES, f_keywords AS F_KEYWORDS, f_link AS F_LINK, f_dateCreated AS F_DATECREATED, ' . LF
            . 'f_dateModified AS F_DATEMODIFIED ' . LF
            . 'FROM faqs ' . LF
            . 'WHERE f_id = ' . $id . " AND f_active = 'Y' ";
      
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
   /*
  *******************************************************************************************
  * Description
  *   Method to retrieve faqs based on the provided search word(s)
  *
  * Input (one of the following combinations)
  *   $searchWord       varchar
  * Output
  *   $return             array
  ******************************************************************************************
  */
  function search($search_word = false)
  {
    $return = array();
    if($search_word !== false)
    {
      $search_word = addslashes($search_word); // only because used in LIKE
      
      $sql  = 'SELECT f_id AS F_ID, f_category as F_CATEGORY, f_question AS F_QUESTION, f_answer AS F_ANSWER, ' . LF
            . 'f_notes AS F_NOTES, f_keywords AS F_KEYWORDS, f_link AS F_LINK, f_dateCreated AS F_DATECREATED, ' . LF
            . 'f_dateModified AS F_DATEMODIFIED ' . LF
            . 'FROM faqs ' . LF
            . "WHERE MATCH (f_question,f_answer,f_keywords) AGAINST ('{$search_word}' IN BOOLEAN MODE) AND f_active = 'Y' "
            . 'ORDER BY f_category';
      
      $rs = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[] = $data;
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getInstance
  * Description
  *   Static method to invoke this class
  * Output
  *   Class object
  ******************************************************************************************
  */
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;
    
    if($inst === null)
    {
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
 /*******************************************************************************************
  * Description
  *   Constructor
  *******************************************************************************************/
  function CFaq()
  {
  }
}
?>
