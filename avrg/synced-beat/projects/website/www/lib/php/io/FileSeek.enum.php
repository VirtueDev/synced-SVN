<?php

class php_io_FileSeek extends Enum {
		public static $SeekBegin;
		public static $SeekCur;
		public static $SeekEnd;
	}
	php_io_FileSeek::$SeekBegin = new php_io_FileSeek("SeekBegin", 0);
	php_io_FileSeek::$SeekCur = new php_io_FileSeek("SeekCur", 1);
	php_io_FileSeek::$SeekEnd = new php_io_FileSeek("SeekEnd", 2);
