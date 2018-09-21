#!/bin/bash
# pass one arg that is the RTMP endpoint
raspivid -rot 180 -o - -t 0 -w 1280 -h 720 -fps 24 -b 8000000 -g 40 | ffmpeg -re -ar 44100 -ac 2 -acodec pcm_s16le -f s16le -ac 2 -i /dev/zero -f h264 -i pipe:0 -c:v copy -c:a aac -ab 128k -g 40 -strict experimental -f flv -r 30 $1
