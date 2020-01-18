<?php
/*
aPrint slicer - PHP2CuraEngine bridge for Debian Buster on armv6l / v7, arm64, amd64 and windows

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT 
NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Required paramter: slice
slice should be a json string that contain all information required for slicing.

infile (string) - Input file location relative to this php file (CuraEngine  -l )
outfile (string) - Output file location relative to this php file (CuraEngine -o )
extraDefs (string array) - Extra definations file located inside ./definations/ (CuraEngine -j )
extraSettings (string array) - Extra settings that will overwrite the default defination in file (CuraEngine -s )
*/

//include_once("../../auth.php");
include_once("binarySelector.php");

//System paramter declaration
$dir_def = "definations/";
$dir_export = "export/";

//Default paramters
$extraDefs = [];
$outfile = $dir_def . "out-" . time() . ".gcode";
$settings = ['adhesion_type=None']; //You can also try: 'layer_height=0.2', 'infill_sparse_density=20', 'speed_print=50', 'adhesion_type=(Skirt/Brim/Raft/None)'
$infile = "test.stl";
$definations = ["fdmprinter.def.json"];
$defOverride = getPlatformPath();
if ($defOverride == false){
    die("ERROR. Platform not supported.");    
}

if (isset($_GET['slice'])){
    $sliceSettings = json_decode($_GET['slice'],true);
    if (isset($sliceSettings["infile"])){
        $infile = $sliceSettings["infile"];
    }
    if (isset($sliceSettings["outfile"])){
        $outfile = $sliceSettings["outfile"];
    }
    if (isset($sliceSettings["extraDefs"])){
        $extraDefs = $sliceSettings["extraDefs"];
    }
    if (isset($sliceSettings["extraSettings"])){
        $settings = $sliceSettings["extraSettings"];
    }
    
    //Check if there are extra definations. If yes, load them as well.
    array_merge($definations,$extraDefs);

    //Check if all the defination files exists.
    foreach ($definations as $def_file){
        if (!file_exists($dir_def . $def_file)){
            die("ERROR. Defination file not found: " . $dir_def . $def_file);
        }
    }
    
    //Check if the user defined an custom output file. If yes, use that as the default outfile name
    if (file_exists($outfile)){
        //File already exists. Delete the old one and replace with new one.
        unlink($outfile);
    }
    
    //Start parsing the command with the given paramters above
    $command = "slice -v -p ";
    
    //Prase defination files
    foreach ($definations as $def){
        //Check if this defination is inside of the representative folder. 
        //If yes, use the one in platform folder. If not, use the one in definations/
        if (file_exists($defOverride .  $def)){
            $command = $command . '-j "' . $defOverride .  $def . '" ';
        }else{
            $command = $command . '-j "' . $dir_def . $def . '" ';
        }
    }
    
    //Parse extra settings
    foreach ($settings as $setting){
        //Append settings into the command, without the need to wrap it in "" becase the setting value itself have it
        $command = $command . '-s ' . $setting . " ";
    }
    
    //Parse input output file
    $command = $command . '-o "' . $outfile . '" -l "' . $infile . '"';
    
    var_dump($command);
    
    binarySelectExecution("CuraEngine",$command);
    
}else{
    die("ERROR. slice info not given.");
}

?>