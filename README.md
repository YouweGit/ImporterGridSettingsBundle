# ImporterObjectBundle
The importer object bundle is a pimcore bundle that set the selected grid columns automatically when importing a data object.

## Installation
The installation can be installed through composer. 
1. Run ``composer require davimo/ImporterObjectBundle`` to receive the bundle.
2. Enable the bundle ``bin/console pimcore:bundle:enable ImporterObjectBundle``.
3. Then reload the GUI of Pimcore. 

## How does it work?
The working is quite simple; when you are importing a CSV file, the bundle will set up the grid automatically according to the data object that you're importing.
