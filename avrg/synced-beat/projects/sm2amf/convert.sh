#!/bin/sh

# Handy script to batch process a directory with a bunch of .sm and .mp3/ogg files, such
# as a Stepmania "Songs" directory.
#
# Usage ./convert.sh <songs-dir> <output-dir>
# Depends: sm2amf

find "$1" -iregex '.*sm' | while read sm;
do
    dir=`dirname "$sm"`
    name=`basename "$dir"`
    `dirname "$0"`/sm2amf.py "$sm" ${2-.}
done
