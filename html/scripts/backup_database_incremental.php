<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';

  $date_week = date('W', NOW);
  $date_part = date('Ymd', NOW);
  $date_part_min  = date('Ymd', strtotime('-5 day', NOW));

  $backup_db    = PATH_BACKUP_DB;
  $db_path_final = $backup_db . '/' . $date_week;
  $host_name    = system('hostname');

  echo "  - Determine current bin file...";
  $list = explode("\n", `ls /var/lib/mysql/{$host_name}-bin.[0-9]*`);
  sort($list);
  $last_bin_file = $list[count($list)-1];
  $last_number   = substr($last_bin_file, strrpos($last_bin_file, '.'));
  echo "{$last_number}...OK\n";

  echo "  - Flushing logs...";
  exec("mysqladmin -u root -p..f0t05erv-mysq1.. flush-logs");
  echo "OK\n";

  echo "  - Copying last bin file...";
  copy($last_bin_file, $db_path_final . '/' . basename($last_bin_file));
  echo "OK\n";

  echo "  - GZipping bin files...";
  exec("tar -zPcf {$db_path_final}/incremental_" . date('mdH', NOW) . ".tar.gz {$db_path_final}/*{$host_name}*bin*");
  echo "OK\n";

  echo "  - Removing copied binary logs from {$db_path_final}...";
  exec("rm -Rf {$db_path_final}/*bin.*");
  echo "OK\n";

  echo "  - Writing to backup.log file...";
  $fp = fopen($db_path_final . '/backup.log', 'a');
  fputs($fp, 'Incremental backup for ' . date(FF_FORMAT_DATE_LONG, NOW) . "\n", 1024);
  fclose($fp);
  echo "OK\n";
    
  echo "  - ------" . date('m-d-Y', NOW) . "------\n\n";
?>