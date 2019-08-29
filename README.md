# ScorpioTek-WP-Util

## Description

A library that contains classes to carry out WordPress related tasks.

## Installation

1. Create a *lib* folder in your themes directory.
2. CD to that directory
3. Create a file called composer.json and use this JSON as a starting point:

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

4. Save the file.
5. Run composer update
6. From your theme's functions.php file, include the file:

    /lib/vendor/scorpiotek/autoload.php

    ## Classes

    ## Version History

    ### 0.0.1

    * Changed the library to be a composer package instead of a WordPress plugin.
    * Included first version of class AdminMenuModifier