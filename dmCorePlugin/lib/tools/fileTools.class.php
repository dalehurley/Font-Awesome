<?php
/**
 * class fileTools
 *
 */

class fileTools {
    /**
     * Attempts to remove recursively the directory named by dirname.
     *
     * @author Mehdi Kabab <http://pioupioum.fr>
     * @copyright Copyright (C) 2009 Mehdi Kabab
     * @license http://www.gnu.org/licenses/gpl.html  GNU GPL version 3 or later
     *
     * @param string $dirname Path to the directory.
     * @param boolean $followLinks Removes symbolic links if set to TRUE.
     * @return boolean Returns TRUE on success or FALSE on failure.
     * @throws Exception
     */
    public static function recursive_rmdir($dirname, $followLinks = false) {
        if (is_dir($dirname) && !is_link($dirname)) {
            if (!is_writable($dirname)) throw new Exception('You do not have renaming permissions!');
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirname) , RecursiveIteratorIterator::CHILD_FIRST);
            
            while ($iterator->valid()) {
                if (!$iterator->isDot()) {
                    if (!$iterator->isWritable()) {
                        throw new Exception(sprintf('Permission Denied: %s.', $iterator->getPathName()));
                    }
                    if ($iterator->isLink() && false === (boolean)$followLinks) {
                        $iterator->next();
                    }
                    if ($iterator->isFile()) {
                        unlink($iterator->getPathName());
                    } else if ($iterator->isDir()) {
                        rmdir($iterator->getPathName());
                    }
                }
                $iterator->next();
            }
            unset($iterator); // Fix for Windows.
            
            return rmdir($dirname);
        } else {
            throw new Exception(sprintf('Directory %s does not exist!', $dirname));
        }
    }
}
