<?php

/**
 * class phpExcelTools 
 * 
 */
class phpExcelTools {
    
    /*
     * $color : ARGB (Alpha Red Green Blue)
     */
    public static function bg($worksheet, $cellCoord, $color='FFFFCCCC') {

        $worksheet->getStyle($cellCoord)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $worksheet->getStyle($cellCoord)->getFill()->getStartColor()->setARGB($color);

        return $worksheet;
    }

    public static function border($worksheet, $cellCoord, $color='FFFF2222') {

        // ajouter une bordure
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => $color),
                ),
            ),
        );
        $worksheet->getStyle($cellCoord . ':' . $cellCoord)->applyFromArray($styleArray);

        return $worksheet;
    }

    public static function writeCell($worksheet, $cellCoord, $text='') {

        $worksheet->setCellValue($cellCoord, $text);

        return $worksheet;
    }

    /*
     * $worksheet : teteetetet
     */

    public static function formateCells($worksheet, $cellCoordDeb, $cellCoordFin) {

        $styleArray = array(
            'font' => array(
                'bold' => false,
                'size' => 10,
                'color' => array(
                    'argb' => 'FF000000',
                ),
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
                'endcolor' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),

        );

        $worksheet->getStyle($cellCoordDeb.':'.$cellCoordFin)->applyFromArray($styleArray);
    }

}

