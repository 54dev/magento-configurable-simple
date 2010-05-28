Simple Configurable Products Extension For Magento
==================================================

*This documentation applies to SCP versions 0.7 onwards.
The documentation for SCP v0.6 and earlier can be seen [here](http://github.com/organicinternet/magento-configurable-simple/blob/34bda60fe4f0ab75d28135748528c08d2e134834/README.markdown)*

This extension changes the way that the pricing of configurable products works in Magento.
With this extension enabled, a configurable product's own price is never used. Instead, the price used is that of the matching associated product.

This gives site owners direct control to set the price of every configuration of a product, while still giving users the flexibility they usually get with configurable products.
(There's no more having to set rules such as: +20% for blue, -£10 for small, +15% for leather. You just price the underlying small-blue-leather product at £199.99 and that's what the user pays.)


This change has two effects on the behaviour of a Magento site:

* When an attempt is made to add a configurable product to the basket/cart, the matching associated simple product is added instead.
* Configurable product prices are shown with "Price from:" followed by the lowest price that this product can be configured to. (Once configurable options have been chosen by the user and the specific product price is known, the 'Price from:' text disappears)



Installation
------------

Installation of SCP is the same as for most extensions, that is via your Magento Connect Manager using the extension key found on the MagentoCommerce site.
Important: Once installed you will need to logout then login of Admin. You must also refresh cache and reindex all data (under System->Cache Management and System->Index Management).

There are also some SCP configuration options under System->Configuration->SCP Config
It's likely you will want to change these from their default values. What each option does should hopefully be self-explanatory.



Uninstallation
------------
Uninstallation of SCP is the same as for most extensions, that is via your Magento Connect Manager.
Once SCP is uninstalled you must go into Admin and refresh cache and reindex all data (under System->Cache Management and System->Index Management)



Some key things to be aware of
------------------------------
* SCP does not allow you to have some configurable products using the SCP logic and some others using the default Magento logic on the same site. If SCP is installed all configurable products will use SCP logic.
* As well as price, any custom options, tier prices, or special prices directly assigned to a Configurable Product will not be used when SCP is installed. They may well be displayed on the Product Page if you assign them to the Configurable Product, but they may cause undefined behaviour so please don't do it.  Assign them to the associated simple products instead, and they'll work just fine.



Functionality in detail
-----------------------
In Magento a Configurable Product has one or several Associated Products. These Associated Products are just Simple Products that you've chosen to 'associate' with the Configurable Product.
A Configurable Product also has a set of Configurable Options (say Colour, Size, Material), and a combination of each of these options maps onto a specific Associated Product.
For example, if the configurable options are {Colour, Size, Material} the choices {red, small, steel} will map onto one associated simple product, and the choices {orange, medium, plastic} will map onto another, etc.

With vanilla Magento, when working with configurable products any pricing that has been directly assigned to the associated simple products in the Magento admin interface is ignored. The configurable product price is actually calculated from the price assigned directly to the configurable product itself, plus any modifiers that can be set per configurable option. (eg +10% for green, +€99 for titanium, etc)

SCP's original goal was to allow site owners to have more direct control over product pricing by changing the way pricing for Magento Configurable Products work, such that the usual rules for Configurable Product pricing (described above) are ignored, and the price that's directly assigned to the associated simple products is used instead. The mechanism SCP uses to achieve this is actually to add the simple product to the cart, instead of adding the configurable product. This approach has a number of benefits, and some limitations.

The main benefit is that it allows site owners to not only directly choose the price that each combination of configurable options will result in, but it also allows them to assign completely different tier prices, custom options, special prices (aka offer prices) etc on the same basis.  So for example if the products in question are tables, a {small, oak} table could have a 'buy 2 for only £129.99 each' offer, whereas the {large, pine} version of the same table may have a Custom Option which allows the customer to specify what kind of finish or dye they require, or have a 10% discount this week. At the time of writing this kind of flexibility is not present with standard Magento Configurable Products.

The main downside to this flexibility is only when you don't need it. If you just want flexiblity around pricing, but want to have the same set of custom options, discounts, etc for every associated product there currently isn't an easy way to do this with SCP. At the moment you have to manually assign the same values to each associated product. It's something I'll look into handling better in future versions of SCP.


Notes
-----

v0.7 of SCP is a significant rewrite. Magento 1.3 and earlier are no longer supported.

* Magento doesn't normally allow Simple Products which have compulsory Custom Options to be associated to a configurable product, as Magento isn't normally able to display the Custom Option to the user. (so it could never be selected despite being compulsory)  
SCP *does* allow this association, as it's able to show these custom options to the user. However, if you uninstall SCP then later save any Configurable Products that have Associated Products with compulsory Custom Options they'll no longer be associated to the Configurable Product and will need re-associating if you later install SCP.  (without SCP installed you can't re-associate them while there are still compulsory custom options on the simple product)

* SCP uses a JavaScript file called product_extension.js. This needs to be loaded after the Magento product.js file, and SCP is written such that it will be. However in some cases the new Magento 'Merge JavaScript Files' option may cause it to be loaded earlier, which will cause SCP to break. If you are seeing JavaScript errors, or if you are seeing the Configurable Product being added to the cart instead of the Associated Products, turn off the 'Merge JavaScript Files' option in Admin->Configuration->Developer

* Some of SCP's JavaScript is dependent on the DOM structure of the Product Page (just as the core Magento product.js is). If you have a very heavily customised theme you may find you have JavaScript errors and may need to modify some of the JS in product_extension.js to match your modified theme.



## Feature Aspirations
* Option to show price ranges rather than just 'Price From'.
* Backordering enhancements. Currently only in-stock associated products are shown even if allow backordering is enabled. This is inline with default Magento behaviour, but it's something that could possibly be enhanced by SCP.
* Possibly allow SCP logic and Magento logic for Configurable Options to run side-by-side.
* Investigate whether it's possible to allow custom options to be set on the Configurable Product (for when they need to be the same across all associated products).


## Bugs / Issues

#### Open Bugs
* If an associated product has a special price, but another product associated to the same configurable product is cheaper yet doesn't have a special price, then on the product page the 'special price' html will not be displayed when the associated product with the special price is chosen.

#### Magento (i.e. not SCP) Bugs/Limitations
* Selecting custom options does not affect the displayed tier price on the product page.
* Discounts which result from Custom Options applying a percentage reduction are only reflected on the cart page, not the product page.
    
#### Reporting Bugs
Please report and/or fix bugs [here](http://www.magentocommerce.com/boards/viewchild/11415/)

When reporting bugs, please specify:

* How to reproduce it
* Which version of Magento you are using
* Which version of SCP you are using
* Whether you're seeing any JavaScript errors, what they are and when they occur (install firebug for firefox, and look at the script console)
* Whether there are any errors output to your error log (turn it on in admin) or output into the page's html.
* Which other extensions you have installed
* If possible, whether the problem goes away when SCP is uninstalled

