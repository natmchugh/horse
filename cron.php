<?php

require __DIR__.'/GitRepoFetcher.php';
require __DIR__.'/RunLogger.php';

$projects = RunLogger::listProjects();
foreach ($projects as $project) {
	$repoPath = "/tmp/{$project->hash}";
	$repo = new GitRepoFetcher($repoPath,$project->url);
	if ($repo->getChanges()) {
		$logger = new RunLogger($project->url, $repoPath);
		$logger->logRun();
	} else {
		var_dump('nothing doin');
	}
}
echo 'done';