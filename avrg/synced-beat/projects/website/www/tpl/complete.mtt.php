<?php
$this->bufferCreate();$t_complete__mtt_0 = 'design.mtt';
$this->buf .= '
<h1>Upload Complete</h1>
<p>Hurray! <i>';
$this->buf .= _hxtemplo_string($ctx->song->title);
$this->buf .= '</i> has been stored. To play, accept this processed MP3 download (<a href="?do=mp3&id=';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '">retry</a>) and <a target="_blank" href="http://whirled.com/#stuff-c_7_0">upload it to Whirled.</a>
Take your pick of one of these shop thumbnails, or remix your own:</p>
<p>
    <img src="icons/logo1.png"/>
    <img src="icons/logo2.png"/>
    <img src="icons/logo3.png"/>
    <img src="icons/logo4.png"/>
    <img src="icons/logo5.png"/>
    <img src="icons/logo6.png"/>
    <img src="icons/logo7.png"/>
    <img src="icons/logo8.png"/>
    <img src="icons/logo9.png"/>
</p>
<p><b>Don\'t forget to tag your song with "whirledbeat" so people can find it!</b></p>
<ul>
    <li><a href="?do=detail&id=';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '">View this song</a></li>
    <li><a href="?do=creation">Upload another song</a></li>
</ul>
<script type="text/javascript">
//<![CDATA[
window.onload = function () {
    setTimeout("document.location = \'?do=mp3&id=';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '\'", 1000);
}
//]]>
</script>
';
$this->includeTemplate($t_complete__mtt_0, 'complete__mtt', $ctx);
$this->buf .= '
';

?>