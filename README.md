Dolibarr Module Template (inventaire)
========================================

This is a full featured module template for Dolibarr.
It's a tool for module developers to kickstart their project and give an hands-on sample of which features Dolibarr has to offer for module development.

If you're not a module developer you have no use for this.

Documentation
-------------

[Module tutorial](http://wiki.dolibarr.org/index.php/Module_development)

[Dolibarr development](http://wiki.dolibarr.org/index.php/Developer_documentation)

### Translations

Dolibarr uses [Transifex](http://transifex.com) to manage it's translations.

This template also contains a sample configuration for Transifex managed translations under the hidden [.tx](.tx) directory.

For more informations, see the [translator's documentation](http://wiki.dolibarr.org/index.php/Translator_documentation).

The Transifex project for this module is available at <http://transifex.com/projects/p/dolibarr-module-template>

Install
-------

- Make sure Dolibarr (>= 3.3.x) is already installed and configured on your workstation or development server.

- In your Dolibarr installation directory, edit the ```htdocs/conf/conf.php``` file

- Find the following lines:
    ```php
    //$dolibarr_main_url_root_alt ...
    //$dolibarr_main_document_root_alt ...
    ```

- Uncomment these lines (delete the leading ```//```) and assign a sensible value according to your Dolibarr installation

    For example :

    - UNIX:
        ```php
        $dolibarr_main_url_root = 'http://localhost/Dolibarr/htdocs';
        $dolibarr_main_document_root = '/var/www/Dolibarr/htdocs';
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = '/var/www/Dolibarr/htdocs/custom';
        ```

    - Windows:
        ```php
        $dolibarr_main_url_root = 'http://localhost/Dolibarr/htdocs';
        $dolibarr_main_document_root = 'C:/My Web Sites/Dolibarr/htdocs';
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = 'C:/My Web Sites/Dolibarr/htdocs/custom';
        ```

    For more information about the ```conf.php``` file take a look at the conf.php.example file.






