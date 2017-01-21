<?php
$this->bufferCreate();$t_list__mtt_0 = 'design.mtt';
$this->buf .= '
<h1>Song List</h1>

<div>
<form action="?">
<input type="text" name="q" value="';
if($ctx->query !== null) {$this->buf .= _hxtemplo_string($ctx->query);}
$this->buf .= '"/> <input type="submit" value="Search"/> ';
 if($ctx->query !== null) {
$this->buf .= '<a href="?">Show all</a>';
 }
$this->buf .= '
</form>
</div>

';
if($ctx->top !== null) {
$this->buf .= '
<div>
<h2>Top uploaders this month</h2>
<ol>
';
$repeater_row = _hxtemplo_repeater($ctx->top);  while($repeater_row->hasNext()) {$ctx->row = $repeater_row->next(); 
$this->buf .= '<li>
    <a href="http://whirled.com/#people-';
$this->buf .= _hxtemplo_string($ctx->row->uploaderId);
$this->buf .= '" target="_blank">';
$this->buf .= _hxtemplo_string($ctx->row->uploaderName);
$this->buf .= '</a> - <a href="?q=';
$this->buf .= _hxtemplo_string($ctx->row->uploaderName);
$this->buf .= '">';
$this->buf .= _hxtemplo_string($ctx->row->count);
$this->buf .= ' songs</a>
</li>';
}
$this->buf .= '
</ol>
</div>
';
}
$this->buf .= '

';
if(_hxtemplo_length($ctx->songs) > 0) {
$this->buf .= '
<div>
    <table border="0" cellspacing="0">
    <tr>
    <th>#</th>
    <th>Title</th>
    <th>Artist</th>
    <th>BPM</th>
    <th>Uploader</th>
    </tr>
    ';
$repeater_song = _hxtemplo_repeater($ctx->songs);  while($repeater_song->hasNext()) {$ctx->song = $repeater_song->next(); 
$this->buf .= '<tr class="even_';
$this->buf .= _hxtemplo_string($repeater_song->even);
$this->buf .= '">
        <td class="id">';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '</td>
        <td class="title"><a href="?do=detail&id=';
$this->buf .= _hxtemplo_string($ctx->song->id);
$this->buf .= '">';
$this->buf .= _hxtemplo_string($ctx->song->title);
$this->buf .= '</a></td>
        <td class="artist">';
$this->buf .= _hxtemplo_string($ctx->song->artist);
$this->buf .= '</td>
        <td class="bpm">';
$this->buf .= _hxtemplo_string($ctx->song->bpm);
$this->buf .= '</td>
        <td class="uploaderName">
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
        </td>
    </tr>';
}
$this->buf .= '
    </table>
</div>

    ';
if($ctx->query !== null) {
$this->buf .= '
        ';
$ctx->q = _hxtemplo_add(_hxtemplo_add('?q=',$ctx->query),'&');
$this->buf .= '
    ';
} else {
$this->buf .= '
        ';
$ctx->q = '?';
$this->buf .= '
    ';
}
$this->buf .= '

    <div style="text-align:center;font-size:large;"><b>Page: </b>
        ';
if($ctx->page > 2) {
$this->buf .= '<a href="';
$this->buf .= _hxtemplo_string($ctx->q);
$this->buf .= 'p=0">1</a> ... ';
}
$this->buf .= '
        ';
 if($ctx->page > 1) {
$this->buf .= '<a href="';
$this->buf .= _hxtemplo_string($ctx->q);
$this->buf .= 'p=';
$this->buf .= _hxtemplo_string($ctx->page - 2);
$this->buf .= '">';
$this->buf .= _hxtemplo_string($ctx->page - 1);
$this->buf .= '</a>';
 }
$this->buf .= '
        ';
 if($ctx->page > 0) {
$this->buf .= '<a href="';
$this->buf .= _hxtemplo_string($ctx->q);
$this->buf .= 'p=';
$this->buf .= _hxtemplo_string($ctx->page - 1);
$this->buf .= '">';
$this->buf .= _hxtemplo_string($ctx->page);
$this->buf .= '</a>';
 }
$this->buf .= '
        ';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->page,1));
$this->buf .= '
        ';
 if($ctx->page < $ctx->lastPage) {
$this->buf .= '<a href="';
$this->buf .= _hxtemplo_string($ctx->q);
$this->buf .= 'p=';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->page,1));
$this->buf .= '">';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->page,2));
$this->buf .= '</a>';
 }
$this->buf .= '
        ';
 if($ctx->page < $ctx->lastPage - 1) {
$this->buf .= '<a href="';
$this->buf .= _hxtemplo_string($ctx->q);
$this->buf .= 'p=';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->page,2));
$this->buf .= '">';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->page,3));
$this->buf .= '</a>';
 }
$this->buf .= '
        ';
if($ctx->lastPage - $ctx->page > 2) {
$this->buf .= ' ... <a href="';
$this->buf .= _hxtemplo_string($ctx->q);
$this->buf .= 'p=';
$this->buf .= _hxtemplo_string($ctx->lastPage);
$this->buf .= '">';
$this->buf .= _hxtemplo_string(_hxtemplo_add($ctx->lastPage,1));
$this->buf .= '</a>';
}
$this->buf .= '
    </div>
';
} else {
$this->buf .= '
    <p>No matching songs found! Why not be the first to <a href="?do=creation">upload</a>?</p>
';
}
$this->buf .= '

';
$this->includeTemplate($t_list__mtt_0, 'list__mtt', $ctx);
$this->buf .= '
';

?>