<?php

class dmWidgetExcelReadFileView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array('libelle'));
    }

    protected function filterViewVars(array $vars = array()) {
        $vars = parent::filterViewVars($vars);

        $vars['excelHtml'] = $this->renduExcelFile($vars['libelle']);

        return $vars;
    }

    protected function renduExcelFile($file) {
        $objet = PHPExcel_IOFactory::createReader('Excel2007');
        $excel = $objet->load(sfConfig::get('sf_upload_dir') . '/excel/'.$file);

        $objWriter = new PHPExcel_Writer_HTML($excel);
       // $excelHtml = $objWriter->generateHTMLHeader();
        $excelHtml = $objWriter->generateStyles(true);
        $excelHtml .= $objWriter->generateSheetData();
       // $excelHtml .= $objWriter->generateHTMLFooter();

        return $excelHtml;
    }

}