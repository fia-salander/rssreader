# RSS-reader created in PHP #

Rss-reader in CakePHP using Windows 10 with PHPStorm and XAMPP (apache and db).


## Database ##
Default:
 
- Database: 'rssreader_db' 
- User: 'rssuser'
- Password: 'rsspassword'

DB-settings are found in config/app.php


## Tables and seed ##
From command line in PHPStorm run the following commands from rssreader/bin:

- cake migrations migrate (migrates tables)
- cake migrations seed (adds 3 channels to db)


## Browser features ##

- Add rss feed
- See all feeds
- See all posts
- See one feed posts


## Command line features  ##

- cake channels (prints all rss feeds)
- cake channels addfeed yourFeedUrl (adds your feed to db and prints that feeds posts)
- cake channels posts (prints all feeds posts)
- cake channels posts yourChannelNumber (prints all feed posts for the provided channel id)

The command asks for sorting options and print options (text or HTML)

