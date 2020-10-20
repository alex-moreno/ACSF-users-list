
**Requirements**

At the moment it will work with Drupal 8 (and potentially Drupal 9) sites.

**Installation**

```
git clone git@github.com:alex-moreno/ACSF.git
cd ACSF
composer install
```

Add your secrets file. Rename secrets-sample.php to secrets.php and add your settings from Account Settings in yoursubscription.acsitefactory.com url:

```
// URL of a subsection inside the SF REST API; must end with sites/.
'url' => '',
'api_user' => '',
'api_key' => '',
```

url is the url of the factory for which you want to get the list. It shoudl be something like this:

```
'url' => 'https://www.dev-CUSTOMER.acsitefactory.com/api/v1/sites/',
```

**Running queries against ACSF (get list of users and their roles)**

```
// php acsf-list-users.php  @drush-alias
php acsf-list-users.php  @mysite.01dev
```
