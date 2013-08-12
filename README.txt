Sitewards SiteCatalyst
======================

Integration of SiteCatalyst Snippet to Typo3

Easy installation
-----------------
Copy your s_code.js to fileadmin/templates/js/
Map the plugin in your TS, i.e.:
   lib.sitecatalyst < plugin.tx_swsitecatalyst_pi1

Modify behavior
---------------
You can rewrite template file:
plugin.tx_swsitecatalyst_pi1.templateFile = EXT:sw_sitecatalyst/templates/sitecatalyst.html

js file:
plugin.tx_swsitecatalyst_pi1.jsFile = /fileadmin/templates/js/s_code.js

default language:
plugin.tx_swsitecatalyst_pi1.defaultLanguageId = 0
plugin.tx_swsitecatalyst_pi1.defaultLanguageCode = de

and the user function to determine if the user is logged in:
plugin.tx_swsitecatalyst_pi1.loggedIn.userFunc = tx_swsitecatalyst_pi1->isLoggedIn

Contact
-------
tobias.zander@sitewards.com

License: GPL 2

Contribution is appreciated, even new issues!
