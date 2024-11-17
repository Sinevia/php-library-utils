<?php

// ========================================================================= //
// SINEVIA CONFIDENTIAL                                  http://sinevia.com  //
// ------------------------------------------------------------------------- //
// COPYRIGHT (c) 2008-2019 Sinevia Ltd                   All rights reserved //
// ------------------------------------------------------------------------- //
// LICENCE: All information contained herein is, and remains, property of    //
// Sinevia Ltd at all times.  Any intellectual and technical concepts        //
// are proprietary to Sinevia Ltd and may be covered by existing patents,    //
// patents in process, and are protected by trade secret or copyright law.   //
// Dissemination or reproduction of this information is strictly forbidden   //
// unless prior written permission is obtained from Sinevia Ltd per domain.  //
//===========================================================================//

namespace Sinevia;

class FileUtils {

    // public static function uploadFileAtS3($s3AccessKey, $s3SecretKey, $filePath, $bucketName, $fileName) {
    //     //require_once 'S3.php';
    //     S3::setAuth($s3AccessKey, $s3SecretKey);
    //     return S3::putObject(S3::inputFile($filePath, false), $bucketName, $fileName, S3::ACL_PUBLIC_READ);
    // }

    // public static function deleteFileAtS3($s3AccessKey, $s3SecretKey, $bucketName, $fileName) {
    //     //require_once 'S3.php';
    //     S3::setAuth($s3AccessKey, $s3SecretKey);
    //     return S3::deleteObject($bucketName, $fileName);
    // }

    // public static function downloadFileFromS3($s3AccessKey, $s3SecretKey, $bucketName, $fileName, $saveToPath) {
    //     //require_once 'S3.php';
    //     S3::setAuth($s3AccessKey, $s3SecretKey);
    //     return S3::getObject($bucketName, $fileName, $saveToPath);
    // }

    /**
     * Copies a directory with all its content to a target folder
     * and optionally deletes source folder. If a folder with this
     * name exists, the copied folder name is changed to
     * "copy_of_FOLDERNAME_TIMEOFCOPY".
     * @return mixed the name of the copied folder, false on FAIL
     * @access public
     */
    public static function directoryCopy($directory, $output_directory, $delete = false) {
        // START: init
        if (is_dir($directory) == false) {
            throw new \RuntimeException("Directory <b>" . $directory . "</b> is NOT found!");
        }
        if (is_dir($output_directory) == false) {
            throw new \RuntimeException("Directory <b>" . $directory . "</b> is NOT found!");
        }
        // Does directory exist?
        $dirname = basename($directory);
        if (is_dir($output_directory . DIRECTORY_SEPARATOR . $dirname) == true) {
            $dirname = "copy_of_" . $dirname . "_" . time();
        }
        $output_path = $output_directory . DIRECTORY_SEPARATOR . $dirname;
        // END: init
        // 2. Getting items to copy
        $items = self::directoryListFilesAndDirectories($directory, true);
        $items = self::arrayValueDelete($items, $directory);

        //1. Create copied directory
        if (mkdir($output_directory . DIRECTORY_SEPARATOR . $dirname) === false) {
            return false;
        }


        // 3. ReCreating file structure
        foreach ($items as $item) {
            $output_item = str_replace($directory, $output_path, $item);
            if (is_file($item)) {
                if (copy($item, $output_item) === false) {
                    return false;
                }
                continue;
            }
            if (is_dir($item)) {
                if (mkdir($output_item) === false) {
                    return false;
                }
                continue;
            }
        }

        // 5. Deleting the directory
        if ($delete == true) {
            self::directoryDelete($directory);
        }

        // 6. Return directory name
        return $dirname;
    }

    /** Empties a directory from its content and optionally deletes it.
     * @access public
     */
    public static function directoryEmpty($directory, $delete = false) {
        // 1. Getting a handler to the specified directory
        $handler = opendir($directory);
        // 2. Looping through every content of the directory
        while (false !== ($item = readdir($handler))) {
            // 2.1 Is it this or parent directory?
            if ($item == ".") {
                
            } elseif ($item == "..") {
                
            }
            // 2.2 Is $item a directory? Delete it!
            elseif (is_dir($directory . DIRECTORY_SEPARATOR . $item)) {
                self::directoryEmpty($directory . DIRECTORY_SEPARATOR . $item, true);
            }
            // 2.3 $item is file. Delete it!
            else {
                @unlink($directory . DIRECTORY_SEPARATOR . $item);
            }
        }
        // 4. Closing the handle
        closedir($handler);
        // 5. Deleting the directory
        if ($delete == true) {
            rmdir($directory);
        }
    }

    /**
     * Deletes the directory with its subdirectories and files.
     * <code>
     * utils::delete_directory();
     * </code>
     * @access public
     */
    public static function directoryDelete($directory) {
        // 1. Getting a handler to the specified directory
        $handler = opendir($directory);
        // 2. Looping through every content of the directory
        while (false !== ($item = readdir($handler))) {
            // 2.1 Is it this or parent directory?
            if ($item == ".") {
                
            } elseif ($item == "..") {
                
            }
            // 2.2 Is $item a directory? Delete it!
            elseif (is_dir($directory . DIRECTORY_SEPARATOR . $item)) {
                self::directoryDelete($directory . DIRECTORY_SEPARATOR . $item);
            }
            // 2.3 $item is file. Delete it!
            else {
                @unlink($directory . DIRECTORY_SEPARATOR . $item);
            }
        }
        // 4. Closing the handle
        closedir($handler);
        // 5. Deleting the directory
        return rmdir($directory);
    }

    /**
     * Lists all the files in a directory.
     * <code>
     * $files = utils::dir_list_files("./data");
     * </code>
     * @param string the path to the directory
     * @return array the files in the directory
     */
    public static function directoryListFiles($directory) {
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);

        // 1. An array to hold the files.
        $files = array();

        // 2. Getting a handler to the specified directory
        $handler = opendir($directory);

        // 3. Looping through every content of the directory
        while (false !== ($file = readdir($handler))) {
            // 3.2 Checking if $file is not a directory
            if (is_file($directory . DIRECTORY_SEPARATOR . $file)) {
                $files[] = $directory . DIRECTORY_SEPARATOR . $file;
            }
        }

        // 4. Closing the handle
        closedir($handler);

        // 5. Returning the file array
        return $files;
    }

    /**
     * Lists the files and folders and returns them in a handy array.
     * @access public
     */
    public static function directoryListFilesAndDirectories($directory, $recurse = false) {
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);
        // 1. An array to hold the files.
        $items = array();
        // 2. Getting a handler to the specified directory
        $handler = opendir($directory);
        // 3. Looping through every content of the directory
        while (false !== ($item = readdir($handler))) {
            // 2.1 Is it this or parent directory?
            if ($item == ".") {
                
            } elseif ($item == "..") {
                
            }
            // 2.2 Is $item a directory? Add and Recurse it!
            elseif (is_dir($directory . DIRECTORY_SEPARATOR . $item)) {
                $items[] = $directory . DIRECTORY_SEPARATOR . $item;
                if ($recurse == true) {
                    $recursed_items = self::directoryListFilesAndDirectories($directory . DIRECTORY_SEPARATOR . $item, true);
                    $items = array_merge($items, $recursed_items);
                }
            }
            // 2.3 $item is file. Add it!
            else {
                $items[] = $directory . DIRECTORY_SEPARATOR . $item;
            }
        }
        // 4. Closing the handle
        closedir($handler);
        // 5. Returning the file array
        return $items;
    }

    /**
     * Lists the files and folders and returns them in a handy array.
     * @access public
     */
    public static function directoryListDirectories($directory, $recurse = false) {
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);
        // 1. An array to hold the files.
        $items = array();
        // 2. Getting a handler to the specified directory
        $handler = opendir($directory);
        // 3. Looping through every content of the directory
        while (false !== ($item = readdir($handler))) {
            // 2.1 Is it this or parent directory?
            if ($item == ".") {
                
            } elseif ($item == "..") {
                
            }
            // 2.2 Is $item a directory? Add and Recurse it!
            elseif (is_dir($directory . DIRECTORY_SEPARATOR . $item)) {
                $items[] = $directory . DIRECTORY_SEPARATOR . $item;
                if ($recurse == true) {
                    $recursed_items = self::directoryListDirectories($directory . DIRECTORY_SEPARATOR . $item, true);
                    $items = array_merge($items, $recursed_items);
                }
            }
        }
        // 4. Closing the handle
        closedir($handler);
        // 5. Returning the file array
        return $items;
    }

    /**
     * Counts the size of a folder.
     * @access public
     */
    public static function directorySize($directory) {
        $items = self::directoryListFilesAndDirectories($directory, true);
        $folder_size = 0;
        foreach ($items as $item) {
            if (is_file($item) === true) {
                $folder_size += filesize($item);
            }
        }
        return $folder_size;
    }    
    
    /**
     * Converts bytes to human friendly format (byte/KB/MB/GB)
     * @param int $filesize
     */
    public static function humanFilesize($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    
    /**
     * Sets access and modification time of file. Creates the file if does not exist.
     * @param string $filePath
     * @return bool
     */
    public static function touchFile($filePath){
        return file_put_contents($filePath, '', FILE_APPEND);
    }

    /**
     * Deletes an element from an array, with the given value (if exists)
     * and reduces the size of the array.
     * <code>
     *     $array = array("key1"=>"value1","key2"=>"value2");
     *     $array = utils::array_value_delete($array,"value2");
     * </code>
     * @param array the array, whose key is to be deleted
     * @param string the key to be deleted
     * @return array the resulting array
     * @tested true
     */
    private static function arrayValueDelete($array, $value): array {
        $value_index = array_keys(array_values($array), $value);
        if (count($value_index) != '') {
            array_splice($array, $value_index[0], 1);
        }
        return $array;
    }

}
