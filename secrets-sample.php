<?php
$config = [
    // URL of a subsection inside the SF REST API; must end with sites/.
    'url' => '',
    'api_user' => '',
    'api_key' => '',
  
    // Site IDs of the sites to process; can also be provided as CLI argument.
    'sites' => [],
  
    // Number of days before backups are deleted; can also be provided on ClI.
    'backup_retention' => 30,
  
    // Request parameter for /api/v1#List-sites.
    'limit' => 100,
  
    // The components of the websites to backup.
    // Details: /api/v1#Create-a-site-backup.
    // 'codebase' is excluded from the default components since those files would
    // be the same in each site backup, and cannot be restored into the factory.
    'components' => ['database', 'public files', 'private files', 'themes'],
  ];
