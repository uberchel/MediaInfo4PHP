# MediaInfo4PHP
MediaInfo is an easy-to-use static PHP class, for extracting data from audio and video files, the FFMpeg program must be installed on the sevrera or computer for the class to work.

## Requirements
- PHP >= 7.2
- PHP extensions: mbstring
- ffmpeg

## Installation
No complicated installation, just transfer or clone the class file to your project.

## Using
The main methods **Load** and **Get** have 2 parameters, the first is the path to the file, the second is to give as an Object (default = true)
For the rest, it is also possible to assign the path to the file to the first parameters if you need to find out only something specific separately or compare 2 files..

\
**We get all the metadata from the file and put it in the $data variable.**
```
 $data = MediaInfo::Get('1.mkv', true);
 print_r($data);
```
\
**Getting all the metadata from the file**
```
 MediaInfo::Load('1.mkv', true);
```
\
**Output the necessary data**
```
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
```
require './mediainfo.php';

MediaInfo::load('./1.mkv');
if (MediaInfo::Has('size')) {
    echo MediaInfo::GetSize();
}

\
Get data from video track size
```
require './mediainfo.php';

MediaInfo::load('./1.mkv');
if (MediaInfo::Has('video.size')) {
    echo MediaInfo::GetVideo()->size;
}
```
\
Get data from audio tracks
```
require './mediainfo.php';

MediaInfo::load('./1.mkv');

foreach (MediaInfo::GetAudios() AS $audio) {
    echo "ID: {$audio['id']}, Title: {$audio['title']}, Codec: {$audio['codec']}";
}
```
\
Comparing codecs of different files
```
require './mediainfo.php';

MediaInfo::load('./1.mkv');
print_r(MediaInfo::getCodec() == MediaInfo::getCodec('./2.mkv'));

//The same thing without loading the Load function
print_r(MediaInfo::getCodec('./1.mkv') == MediaInfo::getCodec('./2.mkv'));
```

### clone the repository and install the requirements
```
git clone https://github.com/uberchel/MediaInfo4PHP
```

## LICENSE
The MIT License