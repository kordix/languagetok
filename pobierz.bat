@echo off
set URL=https://www.youtube.com/watch?v=6FdS0boM-IM
set FOLDER=output
yt-dlp --write-auto-sub --sub-lang en-GB --convert-subs srt --skip-download -o "%FOLDER%\video.%%(ext)s" %URL%


pause
