# Synced Beat
This program is a clone of Aduros' "Whirled Beat." 
It was open-source, so we decided to implement it into Synced Online (www.syncedonline.com).

The actionscript files are pre-compiled and ready to go.

# Website
Currently the website build only supports Windows packaging, so you'll need a windows system to work the website.
If you go to the projects folder, then go to the website folder, you will see the files for the website.
The website is neccessary to allow songs to be played, or else a "Security Error" will show in Synced.

The website requires any Haxe version that is below 3.0.
If you use a Haxe 3.0+ version, then it will give "php.db" package errors.
If you go to the SVN's lib folder, you'll see a compatible haxe version for the website.

# Server Agent
Whenever uploading the game to Synced, type the server agent class as "dance.server.Server"