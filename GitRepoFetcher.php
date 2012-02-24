<?php

class GitRepoFetcher {
	
	private $_repoDirectory = '';

	public function __construct($directory) {
		$this->_repoDirectory = $directory;
		return $this;
	}

	public function getChanges() {
		if (file_exists($this->_repoDirectory)) {
			return $this->updateExistingRepo();
		} else {
			return $this->getNewRepo();
		}
	}

	private function getNewRepo() {
		exec("git clone {$_POST['url']} {$this->_repoDirectory}", $output, $returnCode);
		return $returnCode == 0;
	}

	private function updateExistingRepo() {
		$output = array();
		$backUpDir = getcwd();
		chdir($this->_repoDirectory);
		var_dump(getcwd());
		exec("git pull origin master",$output, $returnCode);
		chdir($backUpDir);
		return $returnCode == 0;
	}
}