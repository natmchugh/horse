<?php

class GitRepoFetcher {
	
	private $_repoDirectory = '';
	private $_repoUrl = '';

	public function __construct($directory, $url) {
		$this->_repoDirectory = $directory;
		$this->_repoUrl = $url;
	}

	public function getChanges() {
		if (file_exists($this->_repoDirectory)) {
			return $this->updateExistingRepo();
		} else {
			return $this->getNewRepo();
		}
	}

	private function getNewRepo() {
		exec("git clone {$this->_repoUrl} {$this->_repoDirectory}", $output, $returnCode);
		var_dump("git clone {$this->_repoUrl} {$this->_repoDirectory}");
		return $returnCode == 0;
	}

	private function updateExistingRepo() {
		$output = array();
		$backUpDir = getcwd();
		chdir($this->_repoDirectory);
		var_dump(getcwd());
		exec("git pull origin master",$output, $returnCode);
		chdir($backUpDir);
		if ($returnCode == 0 && $this->thereAreChanges($output)) {
			return true;
		}
		return false;
	}

	private function thereAreChanges($output) {
		foreach ($output as $line) {
			if (false !== strpos($line, 'Already up-to-date.')) {
				return false;
			}
		}
		return true;
	}
}