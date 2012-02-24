<?php

class CommitCounter {
	
	private $_repoDirectory = '';
	private $_authors = array();

	public function __construct($directory) {
		$this->_repoDirectory = $directory;

		$output = array();
		$backUpDir = getcwd();
		chdir($this->_repoDirectory);
		exec("git log",$output);
		chdir($backUpDir);
		$this->_authors = array();
		foreach($output as $line){
			$commit = array();
		    if(strpos($line, 'Author')===0){
				$commit['author'] = substr($line, strlen('Author:'));
		    }
			if(!empty($commit)){
			    $author = trim(strip_tags($commit['author']));
				    if (!empty($author)) {
					    if (empty($this->_authors[$author])) {
						    $this->_authors[$author] = 1;
					    } else {
						    $this->_authors[$author] = ++$this->_authors[$author];
					    }
				    }
			}
		}
		return $this;
	}

	public function getAuthors() {
		return $this->_authors;
	}



}