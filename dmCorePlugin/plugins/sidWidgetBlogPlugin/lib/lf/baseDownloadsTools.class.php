<?php

/**
 * class baseEditorialTools
 *
 */
class baseDownloadsTools {

    /**
     * création d'une fonction pour télécharger un fichier sur le poste du client
     * @param string $table    table du module en question
     * @param string $file     nom du fichier à récupérer
     *
     * @return fenêtre de téléchargment
     */
    public static function DownloadFile($table,$file) {
        
        $request = dmDb::table($table)->findOneByFile($file);
        $posRep = strpos($request->Files, '/');
            $nameFile = substr($request->Files, $posRep + 1);
            header('Content-Type: application/force-download;name=' . $request->getFiles());
            header('Content-Disposition: attachment;filename=' . $nameFile);
            readfile($request->getFiles());
            return sfView::HEADER_ONLY;
    }

   

}

