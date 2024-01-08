<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style type="text/css">
		html
		{
			font-size: 13px;
			font-family: monospace;
		}
		body
		{
			background-color: white;
			padding: 0;
			margin: 1rem;
		}
		#title
		{
			margin: 0;
		}
		.section-heading
		{
			color: teal; 
			margin-top: .5rem;
		}
		.group-section-heading
		{
			color: teal;
			margin-top: .25rem;
		}
		.error-result,
		.error-message
		{
			color: crimson;
		}
		.warning-result,
		.warning-message
		{
			color: darkorange;
		}
		.skipped-result
		{
			color: gray;
		}
		#json-data
		{
			margin-top: .25rem;
			width: 40rem;
			outline: none;
			font-family: monospace;
		}
	</style>
</head>
<?php
	/** @var array $data The data to print*/
?>
<body>
	<div id="warning-messages">
		<?php foreach(Output::$warnings as $warning): ?>
			<div class="warning-message"><?= htmlspecialchars($warning) ?></div>
		<?php endforeach; ?>
	</div>
	<div id="sysinfos">
		<div class="section-heading">SYSTEM INFO</div>
		<table>
			<?php foreach($data['sysinfo'] as $k => $d): ?>
				<tr class="sysinfo" data-key="<?= htmlspecialchars($k) ?>" data-value="<?= htmlspecialchars((string)$d['value']) ?>">
					<td><?= htmlspecialchars($d['text']) ?></td>
					<td><?= htmlspecialchars($d['value']) ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</body>
</html>
	