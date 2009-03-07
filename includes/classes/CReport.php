<?php

/*******************************************************************************************
 * Name:        CReport.php
 * Class Name:  CReport
 *------------------------------------------------------------------------------------------
 * Mod History: Kevin Hornschemeier   04/11/2006
 *------------------------------------------------------------------------------------------
 * Class to handle the pro reports
 *
 *******************************************************************************************/

class CReport
{

  /*******************************************************************************************
  * Name
  *   getInstance
  *
  * Description
  *   Static method to invoke this class
  *
  * Input
  *
  * Output
  *   Class object
  *******************************************************************************************/
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;

    // if this is the first time the class is instantiated
    // then create and return the class
    // otherwise, just return the current instance
    if($inst === null)
    {
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }

    return $inst;
  }

  /*******************************************************************************************
  * Name
  *   CReport
  *
  * Description
  *   Class constructor (initializes variables)
  *
  * Input
  *
  * Output
  *
  *******************************************************************************************/
  function CReport()
  {
  }

  /*******************************************************************************************
  * Name
  *   Types
  *
  * Description
  *   Gets all the types of reports available
  *
  * Input
  *
  * Output
  *   array - list of report types
  *******************************************************************************************/
  function Types()
  {
    $sql = 'SELECT rt_id AS RT_ID, rt_name AS RT_NAME '
         . 'FROM report_type ';

    return $this->dbh->query_all($sql);
  }

  /*******************************************************************************************
  * Name
  *   ActiveReportRules
  *
  * Description
  *   Gets all the active report rules for a user
  *
  * Input
  *   id - user id
  *
  * Output
  *   array - list of active report rules
  *******************************************************************************************/
  function ActiveReportRules($id = false)
  {
    if( $id !== false )
    {
      $id = $this->dbh->sql_safe($id);
      $sql = 'SELECT r.r_id AS R_ID, r.r_u_id AS R_UID, rt.rt_name AS R_TYPE, r.r_frequency AS R_FREQUENCY, r.r_email AS R_EMAIL, UNIX_TIMESTAMP(r.r_dateCreated) AS R_DATECREATED '
           . 'FROM report AS r, report_type AS rt '
           . 'WHERE r.r_rt_id = rt.rt_id '
           . 'AND r.r_u_id = ' . $id . ' '
           . "AND r_active = 'Y'"
           . 'ORDER BY r_dateCreated desc';
    }
    else
    {
      $sql = 'SELECT r.r_id AS R_ID, r.r_u_id AS R_UID, rt.rt_name AS R_TYPE, r.r_frequency AS R_FREQUENCY, r.r_email AS R_EMAIL, UNIX_TIMESTAMP(r.r_dateCreated) AS R_DATECREATED '
           . 'FROM report AS r, report_type AS rt '
           . 'WHERE r.r_rt_id = rt.rt_id '
           . "AND r_active = 'Y'"
           . 'ORDER BY r_dateCreated desc';
    }
    return $this->dbh->query_all($sql);
  }

  /*******************************************************************************************
  * Name
  *   Reports
  *
  * Description
  *   Gets all the reports for a user
  *
  * Input
  *   id - user id
  *   dates - array of begin date and end date
  *
  * Output
  *   array - list of reports
  *******************************************************************************************/
  function Reports($id, $type = false, $freq = false, $limit = false, $offset = false, $dates = false)
  {
    $id = $this->dbh->sql_safe($id);
    $sql = 'SELECT ra.ra_id AS RA_ID, ra.ra_key AS RA_KEY, ra.ra_u_id AS RA_UID, ra.ra_r_id AS RA_RID, ra.ra_title AS RA_TITLE, ra.ra_timeCreated AS RA_TIMECREATED, r.r_rt_id AS R_TYPE, r.r_frequency AS R_FREQUENCY '
         . 'FROM report_archive AS ra, report AS r '
         . 'WHERE ra.ra_r_id = r.r_id '
         . 'AND ra_u_id = ' . $id;

    if( $type !== false )
    {
      $type = $this->dbh->sql_safe($type);
      $sql .= ' AND r.r_rt_id = ' . $type;
    }

    if( $freq !== false )
    {
      $freq = $this->dbh->sql_safe($freq);
      $sql .= ' AND r.r_frequency = ' . $freq;
    }

    if( $dates !== false )
    {
      $dates = $this->dbh->asql_safe($dates);
      $sql .= ' AND ra_timeCreated BETWEEN ' . $dates[0] . ' AND ' . $dates[1];
    }

    $sql .= ' ORDER BY ra.ra_timeCreated DESC '; // r.r_rt_id DESC, r.r_frequency DESC';

    if( $limit !== false )
    {
      $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
      $sql .= ' LIMIT ' . $limit;
    }

    if( $offset !== false )
    {
      $sql .= ' OFFSET ' . $offset;
    }

    return $this->dbh->query_all($sql);
  }

  /*******************************************************************************************
  * Name
  *   SetNewReport
  *
  * Description
  *   Sets a new report
  *
  * Input
  *   id - user id
  *   type - type of report
  *   freq - frequency of the report
  *   emails - who the report should be sent to
  *
  * Output
  *  int - 0 on failure
  *******************************************************************************************/
  function SetNewReport($userId = false, $type = false, $freq = false, $emails = false)
  {
    $retval = 0;
    if($userId !== false)
    {
      $userId = $this->dbh->sql_safe($userId);
      $type = intval($type);
      $type = $this->dbh->sql_safe($type);
      $freq = $this->dbh->sql_safe($freq);
      $emails = $this->dbh->sql_safe($emails);
      $sql = 'INSERT INTO report (r_u_id, r_rt_id, r_frequency, r_email, r_active, r_dateCreated) '
           . "VALUES (" . $userId . ", " . $type . ", " . $freq . ", " . $emails . ", 'Y', NOW()) ON DUPLICATE KEY UPDATE r_active = 'Y' ";

      $this->dbh->execute($sql);

      $retval = $this->dbh->insert_id();
    }

    return $retval;
  }

  /*******************************************************************************************
  * Name
  *   GetType
  *
  * Description
  *   Gets the string description of the type
  *
  * Input
  *   id - type id
  *
  * Output
  *   string - type
  *******************************************************************************************/
  function GetType($typeId)
  {
    $typeId = $this->dbh->sql_safe($typeId);
    $sql = 'SELECT rt_name AS RT_NAME '
         . 'FROM report_type '
         . 'WHERE rt_id = ' . $typeId;

    return $this->dbh->query_first($sql);
  }

  /*******************************************************************************************
  * Name
  *   GetTypes
  *
  * Description
  *   Gets the types
  *
  * Output
  *   array
  *******************************************************************************************/
  function GetTypes()
  {
    $typeId = $this->dbh->sql_safe($typeId);
    $sql = 'SELECT rt_id AS R_ID, rt_name AS R_NAME '
         . 'FROM report_type ';

    return $this->dbh->query_all($sql);
  }

  /*******************************************************************************************
  * Name
  *   DeleteReport
  *
  * Description
  *   Deletes the specified report
  *
  * Input
  *   r_id - report id
  *
  * Output
  *
  *******************************************************************************************/
  function DeleteReport($r_id)
  {
    $sql = 'UPDATE report '
         . "SET r_active = 'N' "
         . 'WHERE r_id = ' . $r_id;

    $this->dbh->execute($sql);

    return;
  }

  /*******************************************************************************************
  * Name
  *   UpdateReport
  *
  * Description
  *   Updates the specified report
  *
  * Input
  *   r_id - report id
  *   type - type of report
  *   freq - frequency of the report
  *   emails - email addresses
  *
  * Output
  *
  *******************************************************************************************/
  function UpdateReport($r_id, $type, $freq, $emails)
  {
    $type = intval($type);
    $type = $this->dbh->sql_safe($type);
    $freq = $this->dbh->sql_safe($freq);
    $emails = $this->dbh->sql_safe($emails);
    if( $emails == "" )
    {
      $emails = null;
    }
    $sql = 'UPDATE report '
         . "SET r_active = 'Y', r_rt_id = " . $type . ", r_frequency = " . $freq . ", r_email = " . $emails . " "
         . 'WHERE r_id = ' . $r_id;

    $this->dbh->execute($sql);

    return;
  }

  /*******************************************************************************************
  * Name
  *   ArchiveReport
  *
  * Description
  *   Inserts a report into the archive
  *
  * Input
  *   u_id - user id
  *   r_id - report id
  *   title = title of the report
  *
  * Output
  *   insert id
  *******************************************************************************************/
  function ArchiveReport($u_id, $r_id, $title, $data)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $r_id = $this->dbh->sql_safe($r_id);
    $title= $this->dbh->sql_safe($title);
    $data = $this->dbh->sql_safe($data);
    $key  = $this->dbh->sql_safe(md5(uniqid(rand(), true)));
    $sql = 'INSERT INTO report_archive (ra_key, ra_u_id, ra_r_id, ra_title, ra_data, ra_timeCreated) '
         . 'VALUES (' . $key . ', ' . $u_id . ', ' . $r_id . ', ' . $title . ', ' . $data . ", '" . NOW . "' ) ";
    $this->dbh->execute($sql);

    return intval($this->dbh->insert_id());
  }

  /*******************************************************************************************
  * Name
  *   FotoReport
  *
  * Description
  *   Gets the report data for a specific type of report
  *
  * Input
  *   u_id - user id
  *   type - type of report
  *   dates - array of begin date/end date
  *
  * Output
  *   array
  *******************************************************************************************/
  function fotoReport($u_id, $type = false, $date = false, $limit = false)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $type = $this->dbh->sql_safe($type);

    $sql = 'SELECT SQL_CALC_FOUND_ROWS COUNT(*) AS RD_COUNT, rd.rd_element_key AS RD_ELEMENT, rd.rd_type AS RD_TYPE, '
         . 'up.up_name AS UP_NAME, up.up_description AS UP_DESCRIPTION, up.up_tags AS UP_TAGS, up.up_thumb_path AS UP_THUMBPATH, up.up_web_path AS UP_WEBPATH, UNIX_TIMESTAMP(up.up_created_at) AS UP_DATECREATED '
         . 'FROM report_data AS rd INNER JOIN user_fotos AS up '
         . 'ON rd.rd_element_key = up.up_key '
         . 'WHERE up.up_u_id = ' . $u_id . ' '
         . 'AND rd.rd_type = ' . $type;

    if( date !== false )
    {
      $beginDate = date('Y-m-d H:i:s', $date[0]);
      $endDate = date('Y-m-d H:i:s', $date[1]);
      $beginDate = $this->dbh->sql_safe($beginDate);
      $endDate = $this->dbh->sql_safe($endDate);
      $sql .= ' AND rd.rd_dateCreated BETWEEN ' . $beginDate . ' AND ' . $endDate;
    }

    $sql .= ' GROUP BY rd.rd_element_key';
    $sql .= ' ORDER BY RD_COUNT DESC';

    if( $limit !== false )
    {
      $sql .= ' LIMIT ' . $limit;
    }

    return $this->dbh->query_all($sql);
  }

  /*******************************************************************************************
  * Name
  *   FlixReport
  *
  * Description
  *   Gets the report data for a specific type of report
  *
  * Input
  *   u_id - user id
  *   type - type of report
  *   dates - array of begin date/end date
  *
  * Output
  *   array
  *******************************************************************************************/
  function flixReport($u_id, $type = false, $date = false, $limit = false)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $type = $this->dbh->sql_safe($type);

    $sql = 'SELECT SQL_CALC_FOUND_ROWS COUNT(*) AS RD_COUNT, rd.rd_element_key AS RD_ELEMENT, rd.rd_type AS RD_TYPE, '
         . 'us.us_tags AS US_TAGS, us.us_name AS US_NAME, UNIX_TIMESTAMP(us.us_dateCreated) AS US_DATECREATED '
         . 'FROM report_data AS rd INNER JOIN user_slideshows AS us '
         . 'ON rd.rd_element_key = us.us_key '
         . 'WHERE us.us_u_id = ' . $u_id . ' '
         . 'AND rd.rd_type = ' . $type;

    if( $date !== false )
    {
      $beginDate = date('Y-m-d H:i:s', $date[0]);
      $endDate = date('Y-m-d H:i:s', $date[1]);
      $beginDate = $this->dbh->sql_safe($beginDate);
      $endDate = $this->dbh->sql_safe($endDate);
      $sql .= ' AND rd.rd_dateCreated BETWEEN ' . $beginDate . ' AND ' . $endDate;
    }

    $sql .= ' GROUP BY rd.rd_element_key';
    $sql .= ' ORDER BY RD_COUNT DESC';

    if( $limit !== false )
    {
      $sql .= ' LIMIT ' . $limit;
    }

    return $this->dbh->query_all($sql);
  }

  function getReport($reportKey = null)
  {
    $retval = false;
    if(strlen($reportKey) == 32)
    {
      $sql  = 'SELECT r.r_id AS R_ID, r.r_rt_id AS T_ID, ra.ra_id AS RA_ID, ra.ra_key AS RA_KEY, ra.ra_u_id AS RA_U_ID, ra.ra_r_id AS RA_R_ID, ra.ra_title AS RA_TITLE, ra.ra_data AS RA_DATA, ra.ra_timeCreated AS RA_TIMECREATED '
            . 'FROM report as r INNER JOIN report_archive AS ra ON r.r_id = ra.ra_r_id '
            . 'WHERE ra.ra_key = ' . $this->dbh->sql_safe($reportKey);

      $retval = $this->dbh->query_first($sql);
    }

    return $retval;
  }

  function getReportArchive($id = null)
  {
    $retval = false;
    $id = intval($id);
    if($id > 0)
    {
      $sql = 'SELECT ra.ra_id AS RA_ID, ra.ra_key AS RA_KEY, ra.ra_u_id AS RA_U_ID, ra.ra_r_id AS RA_R_ID, ra.ra_title AS RA_TITLE, ra.ra_timeCreated AS RA_TIMECREATED '
           . 'FROM report_archive AS ra '
           . 'WHERE ra_id = ' . $id;
      $retval = $this->dbh->query_first($sql);
    }

    return $retval;
  }

  /*******************************************************************************************
  * Name
  *   getCurrentReport
  *
  * Description
  *   Gets the current report data for a specific report
  *
  * Input
  *   id - report id
  *   or
  *   key - report key
  *
  * Output
  *   array
  *******************************************************************************************/
  function getCurrentReport($identifier)
  {
    $field = strlen($identifier) == 32 ? 'ra.ra_key' : 'ra.ra_r_id';
    $identifier = $this->dbh->sql_safe($identifier);

    $sql = 'SELECT ra.ra_id AS RA_ID, ra.ra_key AS RA_KEY, ra.ra_u_id AS RA_U_ID, ra.ra_r_id AS RA_R_ID, ra.ra_title AS RA_TITLE, ra.ra_timeCreated AS RA_TIMECREATED '
         . 'FROM report_archive AS ra '
         . 'WHERE ' . $field . ' = ' . $identifier . ' '
         . 'ORDER BY ra.ra_timeCreated DESC '
         . 'LIMIT 1 ';

    return $this->dbh->query_first($sql);
  }
}
?>