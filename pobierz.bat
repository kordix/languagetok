@echo off
set URL=https://www.youtube.com/watch?v=Fa9WJMrQLvw
set FOLDER=output
yt-dlp --write-auto-sub --sub-lang en-GB --convert-subs srt --skip-download -o "%FOLDER%\video.%%(ext)s" %URL%
yt-dlp -f bestaudio --extract-audio --audio-format mp3 --audio-quality 0 -o "%FOLDER%\audio.%%(ext)s" %URL%


pause
