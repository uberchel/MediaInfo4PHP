<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {margin:0; padding:0; box-sizing:border-box;}
        body {background-color:rgb(107, 160, 74); color:#CCC;}
        .box {margin:18px 10px;}
        .box .function, .functions { border-top:1px solid rgb(46, 79, 25); padding:10px;}
        .box .function b, .functions b {color:rgb(43, 47, 72)}
        .box .functions {height:250px; }
        .box .functions textarea {width:70%; height:80%; background-color:rgb(39, 121, 7); color:#c9c9c9;}
    </style>
    <title>MediaInfo4PHP DEMO</title>
</head>
<body>
    <div class="box">
        <h2>MediaInfo functions:</h2>
        <?php

        /**
         * Test php class MediaInfo
         */


        // include class
        require 'mediainfo.php';

        // Load and parse info
        MediaInfo::Load('./1.mkv', true);

        // get Title file
        echo "<div class=\"function\"><b>GetTitle():</b> ";
        echo (MediaInfo::GetTitle());
        echo "</div>";

        // get Duration
        echo "<div class=\"function\"><b>GetDuration():</b> ";
        echo (MediaInfo::GetDuration());
        echo "</div>";

        // get VideoCodec
        echo "<div class=\"function\"><b>GetVideoCodec():</b> ";
        echo (MediaInfo::GetVideoCodec());
        echo "</div>";

        // get AudioCodec
        echo "<div class=\"function\"><b>GetAudioCodec():</b> ";
        echo (MediaInfo::GetAudioCodec());
        echo "</div>";

        // get Start time
        echo "<div class=\"function\"><b>GetStart():</b> ";
        echo (MediaInfo::GetStart());
        echo "</div>";

        // get Bitrate
        echo "<div class=\"function\"><b>GetBitrate():</b> ";
        echo (MediaInfo::GetBitrate());
        echo "</div>";

        // get Encoder
        echo "<div class=\"function\"><b>GetEncoder():</b> ";
        echo (MediaInfo::GetEncoder());
        echo "</div>";

        // get Created file
        echo "<div class=\"function\"><b>GetCreated():</b> ";
        echo (MediaInfo::GetCreated());
        echo "</div>";

        // get Videos
        echo "<div class=\"functions\"><b>GetVideos():</b><br>";
        ?>
        <textarea>
            <?php print_r (MediaInfo::GetVideos()); ?>
        </textarea>
        <?php
        echo "</div>";
        // get Pictutes
        echo "<div class=\"functions\"><b>GetPictutes():</b><br>";
        ?>
        <textarea>
            <?php print_r (MediaInfo::GetPictures()); ?>
        </textarea>
        <?php
        echo "</div>";
        // get Streams
        echo "<div class=\"functions\"><b>GetAudios():</b><br>";
        ?>
        <textarea>
            <?php print_r (MediaInfo::GetAudios()); ?>
        </textarea>
        <?php
        echo "</div>";
        // get Streams
        echo "<div class=\"functions\"><b>GetSubtitles():</b><br>";
        ?>
        <textarea>
            <?php print_r (MediaInfo::GetSubtitles()); ?>
        </textarea>
        <?php
        echo "</div>";
        // get All Streams
        echo "<div class=\"functions\"><b>GetStreams():</b><br>";
        ?>
        <textarea>
            <?php print_r (MediaInfo::GetStreams()); ?>
        </textarea>
        <?php
        echo "</div>";
        // get All data
        echo "<div class=\"functions\"><b>Get():</b><br>";
        ?>
        <textarea>
            <?php print_r (MediaInfo::Get()); ?>
        </textarea>

    </div>
</body>
</html>

