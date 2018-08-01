<?php

# quick & dirty hacked. #notsafe


$dir = urldecode($_POST['dir']);
$pwd = "/data/ai600_rgb/";

# check if dir is here
if( file_exists( $pwd . $dir ) )
{
        # remove . and ..
        $dir_and_files = array_diff(scandir($pwd . $dir), array('..', '.'));
		
		# natural sort
        natcasesort($dir_and_files);

        # nothing
        if (!$dir_and_files) { exit; }

        echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        
		# process
        foreach( $dir_and_files as $d )
        {
                $path = $pwd . $dir . $d;
                if (is_dir($path))
                {
                        echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($dir . $d) . "/\">" . htmlentities($d) . "</a></li>";
                }
                else
                {
                        $ext = preg_replace('/^.*\./', '', $d);
                        if (in_array($ext, array("tif", "jpg")))
                        {
                                echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($dir . $d) . "\">" . htmlentities($d) . "</a></li>";
                        }
                }
        }
        echo "</ul>";
}


