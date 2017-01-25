#!/usr/bin/python
#!/home/aduros/local/bin/python2.4

# Converts a Stepmania .sm file to a format readable by the game (AMF3 encoded data)
# Eventually this could be used by a web application to allow players to convert/host their own
# songs created in Stepmania, which actually has a decent editor.
#
# Also processes the MP3 to have the correct padding and ID3 tags.
#
# Usage: ./sm2amf.py -s steps.sm [-m music.mp3] [-i song_id] [-u uploader_id] [-d output_directory]
# Dependencies: Use 'easy_install pyamf pyparsing [MySql-python]', also requires mox and lame commands

import os
import getopt
from subprocess import call
from sys import stdin, stdout, stderr, argv, path
from pyparsing import *

stepsInput = None # Required
musicInput = None # Default: Derived from stepfile
songId = None # For repository songs
outputDir = "."
uploaderId = 0
dbPass = None
fileType = None # Leave null for autodetect

opts, args = getopt.getopt(argv[1:], "m:i:d:u:s:p:t:");

for o, a in opts:
    if o == "-s":
        stepsInput = a
    elif o == "-m":
        musicInput = a
    elif o == "-i":
        songId = a
    elif o == "-d":
        outputDir = a
    elif o == "-u":
        uploaderId = a
    elif o == "-p":
        dbPass = a
    elif o == "-t":
        fileType = a

def header (name):
    #value = Word(printables.replace(";", "") + " \t");
    value = CharsNotIn(';')
    line = Suppress("#" + name + ":") + Optional(value) + Suppress(";")
    return SkipTo(line, True)

note = Literal("0") ^ Literal("1") ^ Literal("2") ^ Literal("3") ^ Literal("M")
beat = note + note + note + note

measure = Group(OneOrMore(beat))

trackHeader = \
    Suppress("#NOTES:") + \
    Suppress("dance-single:") + \
    Word(printables + " ").suppress() + \
    Word(alphas) + Suppress(":") + \
    Word(nums) + Suppress(":") + \
    Word(printables + ":").suppress()

trackData = Group(OneOrMore(measure + Word(",;").suppress()))

grammar = \
    header("TITLE") + \
    header("ARTIST") + \
    header("CREDIT") + \
    header("MUSIC") + \
    header("OFFSET") + \
    SkipTo(Suppress("#BPMS:0.000=") + Word(nums + ".") + Suppress(";"), True) + \
    Group(OneOrMore(Group(SkipTo(trackHeader, True) + trackData)))

# Ignore //comments
grammar.ignore(dblSlashComment)

result = grammar.parseFile(stepsInput)

from pyamf.amf3 import ByteArray

out = ByteArray()

# Convert zeros and empty strings to nulls, these make for a smaller file
def nullify (value):
    if (value == 0 or value == ""):
        return None
    else:
        return value

difficulty = {
    "beginner": 0,
    "easy": 1,
    "medium": 2,
    "hard": 3,
    "challenge": 4
};
diffFlags = 0

title = nullify(result[0][1])
if title == None:
    raise

artist = result[1][1]
music = result[3][1]
bpm = float(result[5][1])

out.writeByte(1) # version
out.writeUTF(title)
out.writeUTF(nullify(artist))
out.writeUTF(nullify(result[2][1])) # credit
out.writeFloat(1000*float(result[4][1])) # offset, convert to milliseconds
out.writeFloat(bpm)
out.writeInt(int(uploaderId))

print "Song: " + title
for track in result[6]:
    diff = track[0][1].lower(); # Difficulty
    print "\tDifficulty: " + diff;
    if diff in difficulty:
        diffFlags |= 1 << difficulty.get(diff);
        out.writeByte(difficulty.get(diff)); # Difficulty
        out.writeByte(int(track[0][2])) # Level
        trackArray = [];
        for measure in track[1]:
            measureArray = [];
            for note in measure:
                if note == "M":
                    note = "4"
                measureArray.append(nullify(int(note)))
            trackArray.append(measureArray)
        out.writeObject(trackArray)

out.compress()

beatFile = outputDir + "/";
if songId == None:
    beatFile = beatFile + title
else:
    beatFile = beatFile + songId

file = open(beatFile + ".beat", "wb")
print >>file, out
file.close()

if songId == None:
    ident = "pack://" + title
    soxOut = "/tmp/sm2amf.wav"
    soxIn = os.getcwd() + "/" + os.path.dirname(argv[1]) + "/" + music
    mp3File = title + " - " + artist + " (Synced Beat).mp3"
    id3Title = title + " (Synced Beat)"
else:
    ident = "user://" + songId
    soxIn = musicInput
    soxOut = "/tmp/sm2amf_" + songId + ".wav"
    mp3File = songId + ".mp3"
    id3Title = title + " (Synced Beat #" + songId + ")"

def my_check_call (args):
    retcode = call(args)
    if retcode != 0:
        raise "Error calling " + args[0]

sox = [ "/home/aduros/local/bin/sox" ]
if fileType != None:
    sox += [ "-t", fileType ]
sox += [ soxIn, soxOut, "pad", "3", "30" ]
my_check_call(sox);
my_check_call([ "/home/aduros/local/bin/lame", soxOut, outputDir + "/" + mp3File, "--id3v2-only",
    "--tl", "Whirled Beat", "--tc", "ddr=" + ident, "--tt", id3Title, "--ta", artist ]);
os.remove(soxOut)

# Stuff some data into the DB
if dbPass != None:
    import _mysql
    db = _mysql.connect("mysql.emufarmers.com", "aduros", dbPass, "whirledbeat")
    db.query("UPDATE SongInfo SET title=" + db.string_literal(title) + ",artist=" + db.string_literal(artist) + ",bpm=" + str(bpm) + ",difficulties=" + str(diffFlags) + " WHERE id="+songId);
    db.close()
