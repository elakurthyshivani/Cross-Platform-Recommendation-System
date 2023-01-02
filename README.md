# Cross-Platform-Recommendation-System

Netflix is one of the most famous OTT platforms available in the current trend. Most
people watch Netflix, Hulu, Peacock, etc. as a means to relieve stress or for fun. Once we complete watching a show, these OTT Platforms provide us recommendations for new content based on the content we finished watching. But the recommended content is only available on their own OTT Platform. Netflix doesn’t recommend something from Hulu or Hulu doesn’t recommend something that we finished watching on Netflix.

This is a website, where a user can log in and browse through the list of shows
and provide ratings, set status, etc. Collaborative filtering is used to find other users similar to the current user and recommend content to the current user. For example, a user ‘antony’ liked F.R.I.E.N.D.S. but didn’t enjoy The Office. Among the users present, let’s say users ‘maria’ and ‘esha’ also liked F.R.I.E.N.D.S and didn’t like The Office. ‘maria’ also liked Brooklyn Nine-Nine, and ‘esha’ liked Modern Family. These two shows will be recommended to ‘antony’.

This project aims to provide personalized recommendations to the user who has finished watching any specific show on some OTT platform. These recommendations won’t belong just to the same OTT platform.

## Data
The data is extracted from various sources:
- Datasets with shows information present on Netflix, Prime, Hulu, and Disney+ from Kaggle
- IMDb Dataset with individual ratings provided by the users for these shows from Kaggle
- To fill in the missing information by extracting them using IMDb API
- To get language and poster URLs for these shows using TMDB API

All this data is merged and preprocessed using data cleaning, transformation, and reduction techniques.

The final data files are `shows_information_list.csv` and `user_ratings.csv`.

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

Update the following line in `CrossPlatformRecommendationSystem/templates/SendEmail.php`:

```php
require 'path to file autoload.php';
```

## License

[![License](https://img.shields.io/badge/License-BSD_2--Clause-orange.svg)](https://opensource.org/licenses/BSD-2-Clause)
