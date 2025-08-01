# MediaInfo4PHP
MediaInfo is an easy-to-use static PHP class, for extracting data from audio and video files, the FFMpeg program must be installed on the server or computer for the class to work.
Version: 1.0.8

## Requirements
- PHP >= 7.2
- PHP extensions: mbstring
- ffmpeg

## Installation
No complicated installation, just transfer or copy the class file to your projector and install it using composer.

```bash
composer require uberchel/mediainfo4php
```

## Using
The main methods **Load** and **Get** have 2 parameters, the first is the path to the file, the second is to give as an Object (default = true)
For the rest, it is also possible to assign the path to the file to the first parameters if you need to find out only something specific separately or compare 2 files..

\
**Using composer.**
```php
<?php

use uberchel\MediaInfo;
//use composer
require __DIR__ . '/vendor/autoload.php';
//not use composer
require './mediainfo.php';

$data = MediaInfo::Get('1.mkv', true);
print_r($data);

```

\
**We get all the metadata from the file and put it in the $data variable.**
```php
<?php

 $data = MediaInfo::Get('1.mkv', true);
 print_r($data);

```
\
**Getting all the metadata from the file**
```php
<?php

 MediaInfo::Load('1.mkv', true);

```
\
**Output the necessary data**
```php
<?php

// By default, the MediaInfo4PHP class returns an Object anyway, this method supports a Boolean attribute,
// by default it is set to true, and this method can also return all metadata in the selected type.
// Setting the Array type for subsequent calls
 MediaInfo::asObject(false);
 
// Setting the Object type for subsequent calls
 MediaInfo::asObject();

// The following calls will return True / False
 MediaInfo::isObject();

// Checking for the existence of a method value return True / False
// $value - method, example: size or streams audios.size | video.size
// $index - array index in streams, optional parameter
 MediaInfo::Has($value, $index);

// Print the name, return string
 echo MediaInfo::GetTitle();

// Output the Size file, return string
 echo MediaInfo::GetSize();

// Output the Duration, return string
 echo MediaInfo::GetDuration();

// Output the start position, return string
 echo MediaInfo::GetStart();

// Output the Video Codec, return string
 echo MediaInfo::GetVideoCodec();

// Output the Audio Codec, return string
 echo MediaInfo::GetAudioCodec();

// Output the video bitrate, return string
 echo MediaInfo::GetBitrate();

// Output the encoder, return string
 echo MediaInfo::GetEncoder();

// Print the creation date, return string
 echo MediaInfo::GetCreated();

// Output a stream of Video, return Object or Array
 print_r(MediaInfo::GetVideo());

// Output an Audio stream, return Object or Array
 print_r(MediaInfo::GetAudios());

// Output a Subtitle stream, return Object or Array
 print_r(MediaInfo::GetSubtitles());

// Output a stream of Images, return Object or Array
 print_r(MediaInfo::GetPictures());

// Output Stream All streams, return Object or Array
 print_r(MediaInfo::GetStreams());

// Output All information to the stream, return Object or Array
 print_r(MediaInfo::Get());
```

## Examples
\
Get data from video file size
```php
<?php
use uberchel\MediaInfo;
require './mediainfo.php';

MediaInfo::load('./1.mkv');
if (MediaInfo::Has('size')) {
    echo MediaInfo::GetSize();
}
```

Get data from video track size
```php
<?php
use uberchel\MediaInfo;
require './mediainfo.php';

MediaInfo::load('./1.mkv');
if (MediaInfo::Has('video.size')) {
    echo MediaInfo::GetVideo()->size;
}
```

Get data from audio tracks
```php
<?php
use uberchel\MediaInfo;
require './mediainfo.php';

MediaInfo::load('./1.mkv');

foreach (MediaInfo::GetAudios() AS $audio) {
    echo "ID: {$audio['id']}, Title: {$audio['title']}, Codec: {$audio['codec']}";
}

```

Comparing codecs of different files
```php
<?php
use uberchel\MediaInfo;
require './mediainfo.php';

MediaInfo::load('./1.mkv');
print_r(MediaInfo::getVideoCodec() == MediaInfo::getVideoCodec('./2.mkv'));

//The same thing without loading the Load function
print_r(MediaInfo::getVideoCodec('./1.mkv') == MediaInfo::getVideoCodec('./2.mkv'));

```

### clone the repository and install the requirements
```bash
git clone https://github.com/uberchel/MediaInfo4PHP
```

## LICENSE
The MIT License
