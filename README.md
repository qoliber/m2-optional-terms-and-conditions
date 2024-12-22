![Open Source Love](https://img.shields.io/badge/open-source-lightgrey?style=for-the-badge&logo=github)
![](https://img.shields.io/badge/Magento-2.4.x-orange?style=for-the-badge&logo=magento)
![](https://img.shields.io/badge/MageOS-YES-orange?style=for-the-badge&logo=mageos)
![](https://img.shields.io/badge/HYVA_Checkout-COMPATIBLE-blue?style=for-the-badge)
![](https://img.shields.io/badge/Maintained-yes-gren?style=for-the-badge&)

# qoliber Magento 2 Optional Terms And Conditions

qoliber `OptionalTermsAndConditions` is a Magento Open Source extension that enhances the checkout experience by 
allowing certain terms and conditions to be marked as optional. This simple yet powerful feature provides flexibility 
for merchants while maintaining compatibility with Hyvä Checkout.

## Installation:

```bash
composer require qoliber/m2-optional-terms-and-conditions
```

### Enable the module, compile DI and static content:

```bash
php bin/magento module:enable Qoliber_OptionalTermsAndConditions
php bin/magento setup:di:compile
```

#### For Luma based themes, compile static files:

There is one `mixin` defined for Luma based themes, so it requires to be 
compiled:

```bash
php bin/magento setup:static-content:deploy
```


## Module Compatibility:

### Luma Checkout:

Optional FIelds are not check or validated during checkout process

[![2O2X9p9.md.png](https://iili.io/2O2X9p9.md.png)](https://freeimage.host/i/2O2X9p9)

### Hyvä Checkout:

Optina Terms and Conditions are not checked during checkout process.

[![2O2XgGj.md.png](https://iili.io/2O2XgGj.md.png)](https://freeimage.host/i/2O2XgGj)

