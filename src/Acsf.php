<?php

/**
 * Example script for making backups of several sites through the REST API.
 * Two things are left up to the script user:
 * - Including Guzzle, which is used by request();
 * e.g. by doing: 'composer init; composer require guzzlehttp/guzzle'
 *
 */

namespace ACSF;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use \Exception as Exception;

/**
 * Some methods to query ACSF
 */
class ACSF
{
    /**
     * The filename of the template to load.
     *
     * @access protected
     * @var string
     */
    protected $config;

    /**
     * Load the configuration file and other minors.
     *
     * @param Array $config
     */
    public function __construct($secrets_file = "secrets.php")
    {
        // Load the config from the secrets file.
        if (file_exists($secrets_file)) {
            include_once $secrets_file;
            // Lower the 'limit' parameter to the maximum which the API allows.
            if ($config['limit'] > 100) {
                $config['limit'] = 100;
            }
        } else {
            throw new Exception("Secrets file needed to connect with Acquia Cloud Site Factory (ACSF)");
        }

        $this->config = $config;
    }

    /**
     * Fetches the list of all sites using the Site Factory REST API.
     *
     * @param [type] $config
     * @return void
     */
    public function getAllSites()
    {
        $config = $this->config;
        // Starting from page 1.
        $page = 1;
  
        $sites = array();
  
        printf("Getting all sites - Limit / request: %d\n", $config['limit']);
  
        // Iterate through the paginated list until we get all sites, or
        // an error occurs.
        do {
            printf("Getting sites page: %d\n", $page);
  
            $method = 'GET';
            $url = $this->config['url'] . "?limit=" . $this->config['limit'] . "&page=" . $page;
            $has_another_page = false;
            $res = $this->request($url, $method, $this->config);
  
            if ($res->getStatusCode() != 200) {
                echo "Error whilst fetching site list!\n";
                exit(1);
            }
  
            $next_page_header = $res->getHeader('link');
            $response = json_decode($res->getBody()->getContents());
  
            // If the next page header is present and has a "next" link, we know we
            // have another page.
            if (!empty($next_page_header) && strpos($next_page_header[0], 'rel="next"') !== false) {
                $has_another_page = true;
                $page++;
            }
  
            foreach ($response->sites as $site) {
                $site_data = array();
                $site_data['id'] = $site->id;
                $site_data['db_name'] = $site->db_name;
                $site_data['alias'] = $site->domain;
                $site_data['site'] = $site->site;
        
                $sites[] = $site_data;
            }
        } while ($has_another_page);
  
        return $sites;
    }


    /**
     * Helper function to return API user and key.
     *
     * @param [type] $config
     * @return void
     */
    public function getRequestAuth($config)
    {
        return [
      'auth' => [$config['api_user'], $config['api_key']],
    ];
    }
  
    // Sends a request using the guzzle HTTP library; prints out any errors.
    public function request($url, $method, $config, $form_params = [])
    {
        // We are setting http_errors => FALSE so that we can handle them ourselves.
        // Otherwise, we cannot differentiate between different HTTP status codes
        // since all 40X codes will just throw a ClientError exception.
        $client = new Client(['http_errors' => false]);
  
        $parameters = $this->getRequestAuth($config);
        if ($form_params) {
            $parameters['form_params'] = $form_params;
        }
  
        try {
            $res = $client->request($method, $url, $parameters);
            return $res;
        } catch (RequestException $e) {
            printf("Request exception!\nError message %s\n", $e->getMessage());
        }

        return null;
    }

    /**
     * Execute drush to fetch users and roles in a given site.
     *
     * @param [type] $site_alias
     * @param [type] $drush_alias
     * @return void
     */
    public function execDrushUserInfo($site_alias, $drush_alias)
    {
        $result = exec("drush  $drush_alias  -l  $site_alias uinf \"$(drush   $drush_alias  -l  $site_alias sqlq \"SELECT GROUP_CONCAT(name) FROM users_field_data\")\"", $output, $return);
        if (strpos($output[2], "Command user-information was not found. Drush was unable to query the database")) {
            echo "\nCould not query database in $site_alias \n ";
        } else {
            echo "\nUsers found in $site_alias \n";
            print_r($result);
            print_r($output);
        }
    }
}
