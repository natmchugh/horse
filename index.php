<pre>
<?php
require __DIR__.'/CommitCounter.php';
require __DIR__.'/GitRepoFetcher.php';
require __DIR__.'/RunLogger.php';
require __DIR__.'/Command.php';

if (!empty($_POST) && isset($_POST['url'])) {
	$url = $_POST['url'];
	$repoPath = "/tmp/".md5($_POST['url']);

	$repo = new GitRepoFetcher($repoPath, $_POST['url']);
	$updated = $repo->getChanges();
	
	if ($updated) {
		$logger = new RunLogger($url, $repoPath);
		$logger->logRun();
	} else {
		var_dump('nothing doin');
	}
	$command = new PHPLOC_Horse_Command();
    $command->main(array($repoPath), array(), array());

    $commits = new CommitCounter($repoPath);

    $authorDataString = '';
    $authorNameString = '';
    foreach ($commits->getAuthors() as $author => $commits) {
    	$angle = ($commits / 16) * 360;
    	$authorDataString .= $commits.',';
    	$authorNameString .= $author.'|';
    }
    $authorDataString = trim($authorDataString, ',');
    $authorNameString = trim($authorNameString, '|');
    $url = "http://chart.apis.google.com/chart?chs=300x225&cht=p&chd=t:$authorDataString&chdl=$authorNameString&chp=0.233&chtt=Git+commits&chco=FFFF10,FF0000";
}
?>
</pre>
<?php if ($url) :?>
<img src="<?php echo $url ?>">
<?php endif; ?>
<table>
<?php foreach (RunLogger::listProjects() as $project): ?>
	<tr>
		<td><?php echo $project->name; ?></td>
		<td><?php echo $project->url; ?></td>
		<td><?php echo $project->runs; ?></td>
		<td><?php echo $project->last_run; ?></td>
		<td>
			<form method="POST" action="#">
				<input type="hidden" name="url" value="<?php echo $project->url; ?>"/>
				<input type="submit" value="run again">
			</form>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<div>
<?php echo file_get_contents('/var/www/.ssh/id_rsa.pub'); ?>
</div>
<form method="POST" action="#" >
<fieldset>
<label for="url">Github URL</label>
<input name="url" type="text" size="50" value="<?php if (isset($url)) {echo $url;} ?>" >
<input type="submit">
</fieldset>
</form>