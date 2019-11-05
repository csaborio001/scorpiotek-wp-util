# ScorpioTek-WP-Util

## Description

A library that contains classes to carry out WordPress related tasks.

## Recommended Installation

1. Create a composer file inside the directory you are creating with this as a starting point:
2. CD to that directory
3. Create a file called composer.json and use this JSON as a starting point:
```
    {
    "name": "PROJECT_NAME",
    "version": "1.0.0",
    "minimum-stability": "dev",
    "repositories": [
        {
        "type": "vcs",
        "url": "https://github.com/csaborio001/scorpiotek-wp-util"
        }
    ],
        "require" : {
            "scorpiotek/wp-util": "dev-master"
        }
    }
```
4. Save the file.
5. Run composer update
6. From your theme's functions.php file, include the file:

    /vendor/scorpiotek/autoload.php

    ## Classes

    ## Version History

    ### 0.1.0

    * Changed everything to use roles instead of capabilities.
    * Rewrote the whole logic and used a code sniffer.

    ### 0.0.2

    * Changed old references to old error function to simply use error_log.
    
    ### 0.0.1

    * Changed the library to be a composer package instead of a WordPress plugin.
    * Included first version of class AdminMenuModifier