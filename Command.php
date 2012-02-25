<?php
require __DIR__.'/phploc/PHPLOC/Autoload.php';

class PHPLOC_Horse_Command extends PHPLOC_TextUI_Command {

	public function main($arguments, $excludes, $suffixes) {

        $this->printVersionString();

        $files = $this->findFiles($arguments, $excludes, $suffixes);

        if (empty($files)) {
            $this->showError("No files found to scan.\n");
        }

        $analyser = new PHPLOC_Analyser($verbose);
        $count    = $analyser->countFiles($files, $countTests);

        $printer = new PHPLOC_TextUI_ResultPrinter_Text;
        $printer->printResult($count, $countTests);
        $logCsv = implode('_',$arguments).'.csv';

        $printer = new PHPLOC_TextUI_ResultPrinter_CSV;
        $printer->printResult($logCsv, $count);
	}
}