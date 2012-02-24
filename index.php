<pre>
<?php
require __DIR__.'/CommitCounter.php';
require __DIR__.'/GitRepoFetcher.php';
require __DIR__.'/Command.php';

if (!empty($_POST) && isset($_POST['url'])) {
	$url = $_POST['url'];
	$repoPath = "/tmp/".md5($_POST['url']);
	$cmds = array();
	$repo = new GitRepoFetcher($repoPath);
	$updated = $repo->getChanges();
	

	$command = new PHPLOC_Horse_Command();
    $command->main(array($repoPath), array(), array());

    $commits = new CommitCounter($repoPath);

    var_dump($commits->getAuthors());
}
?>
</pre>

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