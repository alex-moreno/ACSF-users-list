
**INSTALLATION**

```
composer install
```

Add your secrets file. Rename secrets-sample.php and add your settings:

```
    // URL of a subsection inside the SF REST API; must end with sites/.
    'url' => '',
    'api_user' => '',
    'api_key' => '',
```

url is something like:

```
    'url' => 'https://www.dev-CUSTOMER.acsitefactory.com/api/v1/sites/',
```

**RUNNING QUERIES AGAINST ACSF**

```
// php acsf-list-users.php  @drush-alias
php acsf-list-users.php  @mysite.01dev
```
