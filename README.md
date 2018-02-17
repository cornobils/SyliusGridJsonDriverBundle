# SyliusGridJsonDriverBundle

This bundle adds json driver to SyliusGridBundle, which helps get data from specified url and display it as common Sylius Grid.

## Installation

1. require the bundle with Composer:

  ```bash
  $ composer require doctorx32/sylius-grid-json-driver-bundle
  ```

  2. enable the bundle in `app/AppKernel.php`:

  ```php
  public function registerBundles()
  {
    $bundles = array(
      // ...
      new Sylius\Bundle\GridBundle\Driver\Json\SyliusGridJsonDriverBundle(),
      // ...
    );
  }
  ```
  
  3. prepare your grid for looking similar as:
  ```yaml
  sylius_grid:
      grids:
          app_admin_supplier:
              driver:
                  name: json
                  options:
                      url: "/api/v1/products/"
                      host: "http://localhost:8000"
              fields:
                  "[name]":
                      type: string
                  "[code]":
                      type: string
              filters:
                  search:
                      type: string
  ```