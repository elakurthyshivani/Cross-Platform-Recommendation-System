# Cross-Platform-Recommendation-System

This project aims to provide personalized recommendations to the user who has finished watching any specific show on some OTT platform. These recommendations won’t belong just to the same OTT platform.

## Pre-requisites

Use the package manager [pip](https://pip.pypa.io/en/stable/) to install pandas, pymysql, surprise, and nltk packages in Python.

```bash
pip install pandas
pip install pymysql
pip install surprise
pip install nltk
```

Use [composer](https://getcomposer.org/download/) to install PHPMailer in PHP. See command-line installation to install composer using PHP easily. Next, to install PHPMailer, go to the directory that consists of the file `composer.phar` and copy the path. In CMD, enter the following commands:

```bash
cd <directory-containing-composer.phar>
php composer.phar require phpmailer/phpmailer
``` 

A folder `vendor` with be created which consists of the folders `composer` and `phpmailer`, and the file `autoload.php`.
