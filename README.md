<img src="https://raw.githubusercontent.com/tobychui/PHP2CuraEngine/master/img/banner.png">

# PHP2CuraEngine
PHP2CuraEngine bridge for Debian Buster on armv6l / v7, arm64, amd64 and windows.

This is a PHP written wrapper for parsing CuraEngine slicing command on a given 3D model and output gcode to given location. This repo include compiled binary to run on Debian based system on multiple platforms with different CPU architectures. (And yes, Raspberry pi with Raspbian Buster also works without the needs to install anything extra.)

### Usage (slice.php)
Required paramter: slice

slice should be a json object that contain all information required for slicing.

infile (string) - Input file location relative to this php file (CuraEngine  -l )

outfile (string) - Output file location relative to this php file (CuraEngine -o )

extraDefs (string array) - Extra definations file located inside ./definations/ (CuraEngine -j )

extraSettings (string array) - Extra settings that will overwrite the default defination in file (CuraEngine -s )

Example:
```
CuraEngine slice -v -p -j fdmprinter.def.json -o test.gcode -l test.stl

//Can be sliced via the following PHP request

slice.php?slice={"infile":"test.stl","outfile":"test.gcode"}

//With custom defination files
slice.php?slice={"infile":"test.stl","outfile":"test.gcode","extraDefs":["myprinter.def.json"]}

//With custom settings (Not recommended, as sometime slicing error will occurs if incorrect settings are passed in)
slice.php?slice={"infile":"test.stl","outfile":"test.gcode","extraSettings":["layer_height=0.2","speed_print=50"]}
```

### Compatibility
Tested running on Debian Jessie (x86), Buster(x64), Armbian (arm64, orange pi zero plus) and Rasbian (armv6l, compatible with armv7l) and Windows 7. See slice.php and binarySelector.php for more information on supported platforms.


### Installation
Copy and paste everything inside src/ to or under your apache web root. The binarySelector will select which binary to execute using binarySelector.php. 

**Required Linux system command: uname**

### Points to Notes
1. This module's development has been terminated. (Aka this is a spin-off module from the platform we are working on)
2. You can use it whereever you like as soon as it is following the CuraEngine's license
3. Feel free to ask questions, but bugs will no longer be fixed or the project be maintained. 
4. On some Linux distro, you might need to add ```chdir('armv6l/');``` to binarySelector.php for it to load the .so files.

### License
This project includes the compiled version of CuraEngine which you can find its source here:

https://github.com/Ultimaker/CuraEngine

This project, according to CuraEngine source code licese, follows the AGPL-3.0 with all its downstream distrubution.
