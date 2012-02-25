<?php

class RunLogger {
	
	private $_repoPath;
	private $_repoName = '';
	private $_repoURL;

	public function __construct($repoURL, $repoPath) {
		$this->_repoPath = $repoPath;
		$this->_repoURL = $repoURL;

		if (preg_match('/([\w-_]+)\.git$/',$this->_repoURL, $matches)) {
			$this->_repoName = $matches[1];
		}
	}

	public function logRun() {
		$mysqli = new mysqli("localhost", "root", "root", "horse");
		if (! $project_id = $this->checkIfProjectExists($mysqli)) {
			$this->insertNewProject($mysqli);
			$project_id = $mysqli->insert_id;
		}

		$this->insertRun($mysqli, $project_id);
	}

	private function checkIfProjectExists($mysqli) {
		$hash = md5($this->_repoURL);
		$query = "SELECT id from project WHERE hash =  '{$hash}'";
		$result = $mysqli->query($query);
		return (int) array_pop($result->fetch_array());
	}

	private function insertRun($mysqli, $project_id) {
		$query = "INSERT INTO run (project_id, date) values ".
		"('{$project_id}', now() )";
		return $mysqli->query($query);
	}

	private function insertNewProject($mysqli) {
		$hash = md5($this->_repoURL);
		$query = "INSERT INTO project (hash, name, url) values ".
		"('{$hash}', '{$this->_repoName}', '{$this->_repoURL}' )";
		return $mysqli->query($query);
	}
}