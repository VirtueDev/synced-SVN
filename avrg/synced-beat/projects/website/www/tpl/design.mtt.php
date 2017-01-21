<?php

$this->buf .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Beat Library</title>

    <link href="css/default.css" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" type="image/gif" href="/favicon.gif"/>
    <link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen"/>

    <script src="js/prototype.js" type="text/javascript"></script>
    <script src="js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
    <script src="js/lightbox.js" type="text/javascript"></script>
</head>
<body>
<div id="wrapper">
    <div id="header">
        <div id="logo">
            <a href="."><img src="icons/logo1.png"/></a>
        </div>
        <div id="menu">
            <ul>
                <li><a href=".">Browse</a></li>
                <li><a href="?do=creation">Upload</a></li>
                <li><a href="http://whirled.com/#groups-d_13396" target="_blank">Community</a></li>
                <li><a href="http://bit.ly/PlayBeat" target="_blank">Play</a></li>
            </ul>
        </div>
    </div>
    <div id="page">
        <div id="content">';
$this->buf .= $this->content;
$this->buf .= '</div>
    </div>
    <div id="footer">
        <p id="links"><a href="http://twitter.com/b_garcia" target="_blank">Bruno Garcia</a> | <a href="http://whirled-dance.googlecode.com" target="_blank">Open Source</a> | <a href="http://freecsstemplates.org" target="_blank">Design</a></p>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9490853-5");
pageTracker._trackPageview();
} catch(err) {}
//]]>
</script>

</body>
</html>
';

?>