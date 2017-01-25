# Synced Beat
This program is a clone of Aduros' "Whirled Beat." 
It was open-source, so we decided to implement it into Synced Online (www.syncedonline.com).

The actionscript and php files are pre-compiled and ready to go.

# Setting up the Website
Download and install a Linux virtual machine (Ubuntu is best); we'll be using this virtual machine for the website. The reason for this is because the Beat Library website only works within Linux because that's how Aduros set the file directories.

- Download and install XAMPP v1.8.1 within your Linux virtual machine (https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/1.8.1/)
- Download soX within your Linux virtual machine (sudo apt-get install sox).
- Download lame within your Linux virtual machine (sudo apt-get install lame).
- Download python 2.4 within your Linux virtual machine (sudo apt-get install python2.4).

Copy that entire www folder in the synced-beat/projects/website folder into your webserver's root folder (htdocs). I'm using XAMPP as a webserver, so it uses localhost. 

Go to www/lib/Index.class.php and then go to line 37 and change 
$sm2amf = new php_io_Process("/home/aduros/local/bin/python2.4", $params1); to your own directory for python2.4.

Now go into localhost/phpmyadmin and create a new database named "whirledbeat" then import the schema.sql file in your synced-beat/projects/websitefile to the database (this creates all of the neccesssary schemas for the Beat Library website)

Make sure the field "bpm" has a default value in your whirledbeat database. In order to do this, for the bpm value, tinyint(4) should be tinyint(1)

Type "localhost/www" in your web browser and your Beat Library page should show up.

# Server Agent
Whenever uploading the game to Synced, type the server agent class as "dance.server.Server"


