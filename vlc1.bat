@echo off
cd\Program Files (x86)\VideoLAN\VLC\
vlc dshow:// :dshow-vdev="Logitech HD Webcam C270" :dshow-size="752x416" :dshow-fps=10.000000 :sout=#transcode{vcodec=theo,vb=64,scale=1,acodec=none}:http{dst=:8080/stream64p.ogg}}