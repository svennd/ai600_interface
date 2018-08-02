<?php
# create zip & download it

$zip    = "/data/download/download.zip";
// $dir    = "/data/ai600/mng/SvenB 2017.11.08_09.20.47_Ch";

# this should be better checked (like ../../)
$dir    = (isset($_GET['f'])) ? $_GET['f'] : exit;

# stolen from
# https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
function zip($source, $destination)
{
    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);
            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/')+1), array('.', '..'))) {
                continue;
            }

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } elseif (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

# create zip
zip('/data/' . $dir, $zip);

# send it
header("Content-type: application/zip");
header("Content-Disposition: attachment; filename=$zip");
header("Content-length: " . filesize($zip));
header("Pragma: no-cache");
header("Expires: 0");
readfile($zip);
unlink($zip);
