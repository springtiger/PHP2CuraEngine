<?php
/*
ArOZ Online File System Binary Selector
--------------------------------------------------
This is a simple function to select the suitable binary for your operating system.
Call with binarySelectExecution("{binary_filename}","{command following the binary}");

Speically modified for aPrint CuraEngine slicing binary file selector
*/
function binarySelectExecution ($binaryName, $command){
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        //Use windows binary
        $commandString = "start windows/" . $binaryName . ".exe " . $command;
		pclose(popen($commandString, 'r'));		
    } else {
        //Use linux binary
    	$cpuMode = exec("uname -m 2>&1",$output, $return_var);
    	switch(trim($cpuMode)){
    	    case "armv7l": //raspberry pi 3B+
    	    case "armv6l": //Raspberry pi zero w
    	            $commandString = "sudo ./armv6l/" . $binaryName . "_armv6l.elf " . $command; 
    	        break;
    	   case "aarch64": //Armbian with ARMv8 / arm64
    	            $commandString = "sudo ./arm64/" . $binaryName . "_arm64.elf " . $command;
				break;
    	   case "i686": //x86 32bit CPU
    	   case "i386": //x86 32bit CPU
				$commandString = "sudo ./i386/" . $binaryName . "_i386.elf " . $command;
				break;
    	   case "x86_64": //x86-64 64bit CPU
    	            $commandString = "sudo ./amd64/" . $binaryName . "_amd64.elf " . $command;
				break;
    	   default:
    	       //No idea why uname -m not working. In that case, x86 32bit binary is used.
    	            $commandString = "sudo ./i386/" . $binaryName . "_i386.elf " . $command;
				break;
		}
	    pclose(popen($commandString . " > null.txt 2>&1 &", 'r'));
    }
}

function getPlatformPath(){
    	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return "windows/";
    } else {
        //Use linux binary
    	$cpuMode = exec("uname -m 2>&1",$output, $return_var);
    	switch(trim($cpuMode)){
    	    case "armv7l": //raspberry pi 3B+
    	    case "armv6l": //Raspberry pi zero w
    	            return "armv6l/";
    	        break;
    	   case "aarch64": //Armbian with ARMv8 / arm64
    	            return "arm64/";
				break;
    	   case "i686": //x86 32bit CPU
    	   case "i386": //x86 32bit CPU
				return "i386/";
				break;
    	   case "x86_64": //x86-64 64bit CPU
    	            return "amd64/";
				break;
    	   default:
    	       //No idea why uname -m not working. In that case, x86 32bit binary is used.
    	            return false;
				break;
		}
    }
}
?>