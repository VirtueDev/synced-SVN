<?php
$this->bufferCreate();$t_detail__mtt_0 = 'design.mtt';
$this->buf .= '
<h1>Song Detail</h1>
<p>
<table>
<tr><th>Title</th><td>';
$this->buf .= _hxtemplo_string($ctx->song->title);
$this->buf .= ' <a href="';
$this->buf .= _hxtemplo_string($ctx->whirledUrl);
$this->buf .= '" target="_blank">(Buy)</a></td></tr>
<tr><th>Artist</th><td>';
$this->buf .= _hxtemplo_string($ctx->song->artist);
$this->buf .= '</td></tr>
<tr><th>Preview</th><td>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="165" height="38" id="niftyPlayer1" align="">
  <param name="movie" value="niftyplayer.swf?file=songs/';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '.mp3"/>
  <param name="quality" value="high"/>
  <param name="bgcolor" value="#ffffff"/>
  <embed src="niftyplayer.swf?file=songs/';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '.mp3" quality="high" bgcolor="#ffffff" width="165" height="38" name="niftyPlayer1" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
</object>
</td></tr>
<tr><th>BPM</th><td>';
$this->buf .= _hxtemplo_string($ctx->song->bpm);
$this->buf .= '</td></tr>
<tr><th>Charts</th><td>
    ';
 if(($ctx->song->difficulties & 1) !== 0) {
$this->buf .= '<a rel="lightbox[charts]" href="songs/';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '_beginner.png" title="Beginner"><img src="icons/difficulty0.png"/></a>';
 }
$this->buf .= '
    ';
 if(($ctx->song->difficulties & 2) !== 0) {
$this->buf .= '<a rel="lightbox[charts]" href="songs/';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '_light.png" title="Light"><img src="icons/difficulty1.png"/></a>';
 }
$this->buf .= '
    ';
 if(($ctx->song->difficulties & 4) !== 0) {
$this->buf .= '<a rel="lightbox[charts]" href="songs/';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '_standard.png" title="Standard"><img src="icons/difficulty2.png"/></a>';
 }
$this->buf .= '
    ';
 if(($ctx->song->difficulties & 8) !== 0) {
$this->buf .= '<a rel="lightbox[charts]" href="songs/';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '_heavy.png" title="Heavy"><img src="icons/difficulty3.png"/></a>';
 }
$this->buf .= '
    ';
 if(($ctx->song->difficulties & 16) !== 0) {
$this->buf .= '<a rel="lightbox[charts]" href="songs/';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '_guru.png" title="GURU"><img src="icons/difficulty4.png"/></a>';
 }
$this->buf .= '
</td></tr>
';
 if($ctx->song->uploaderName !== null) {
$this->buf .= '<tr><th>Uploader</th><td>
    ';
if($ctx->song->uploaderId !== 0) {
$this->buf .= '
        <a href="http://whirled.com/#people-';
$this->buf .= _hxtemplo_string($ctx->song->uploaderId);
$this->buf .= '" target="_blank">';
$this->buf .= _hxtemplo_string($ctx->song->uploaderName);
$this->buf .= '</a>
    ';
} else {
$this->buf .= '
        ';
$this->buf .= _hxtemplo_string($ctx->song->uploaderName);
$this->buf .= '
    ';
}
$this->buf .= '
</td></tr>';
 }
$this->buf .= '
</table>
</p>
<h2>Comments</h2>
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script><script type="text/javascript">FB.init("3b2eeb94700b358201530cbbed71e701");</script><fb:comments xid="song-';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '" title="';
$this->buf .= _hxtemplo_string($ctx->song->title);
$this->buf .= ' - ';
$this->buf .= _hxtemplo_string($ctx->song->artist);
$this->buf .= '"></fb:comments>

';
$this->includeTemplate($t_detail__mtt_0, 'detail__mtt', $ctx);
$this->buf .= '
';

?>