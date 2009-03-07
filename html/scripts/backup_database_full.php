<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';

  $dbs = $GLOBALS['dbh']->query_all('SHOW DATABASES');

  $date_week = date('W', NOW);
  $date_part = date('Ymd', NOW);
  $date_part_min  = date('Ymd', strtotime('-5 day', NOW));

  $backup_db    = PATH_BACKUP_DB;
  $host_name    = system('hostname');

  echo "Making directory ({$backup_db}/{$date_week})...";
  mkdir($backup_db . '/' . $date_week, 0755);
  echo "OK\n";

  echo "Determine current bin file...";
  $list = explode("\n", `ls /var/lib/mysql/{$host_name}-bin.[0-9]*`);
  sort($list);
  $last_bin_file = $list[count($list)-1];
  $last_number   = substr($last_bin_file, strrpos($last_bin_file, '.'));
  echo "{$last_number}...OK\n";

  echo "Writing backup.log file...";
  $fp = fopen($backup_db . '/' . $date_week . '/backup.log', 'w');
  fputs($fp, $last_number, strlen($last_number));
  fclose($fp);
  echo "OK\n";

  foreach($dbs as $data)
  {
    if($data['Database'] != 'information_schema')
    {
      $db_name = $data['Database'];
      $db_path_final = $backup_db . '/' . intval($date_week);
      $db_path = $db_path_final . '/' . $db_name;
      if($db_path != '/' && strncmp($db_path, PATH_BACKUP_DB, strlen(PATH_BACKUP_DB)) == 0)
      {
        echo "Backing up database ({$db_name})...";
        `mysqlhotcopy --flushlog --user root --password ..f0t05erv-mysq1.. {$db_name} {$db_path_final}`;
        `tar -zPcf  {$db_path_final}/{$db_name}.tar.gz {$db_path_final}/{$db_name}/*`;
        `rm -Rf {$db_path_final}/{$db_name}`; // DON'T LIKE THIS HERE (If statement does sanity check)
        echo "OK\n";
      }
      else
      {
        echo "Backup failed because paths were wrong!!!!!\ndb_path -> {$db_path}\nPATH_BACKUP_DB ->" . PATH_BACKUP_DB . "\n";
      }
    }
    else
    {
      echo "Skipping database information_schema...OK\n";
    }
  }

  echo "------" . date(FF_FORMAT_DATE_LONG, NOW) . "------\n\n";
?>