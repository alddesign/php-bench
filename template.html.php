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
		#main
		{
			width: 50rem;
			max-width: 100%;
		}
		#title
		{
			color: teal;
			margin: 0;
			border-bottom: 1px dashed teal;
		}
		.section-heading
		{
			color: teal; 
		}
		h2.section-heading
		{
			margin: .5rem 0 .3rem 0;
			font-size: 1.5rem;
			border-bottom: 1px dashed teal;
		}
		h3.section-heading
		{
			margin: .25rem 0 0 0;
			font-size: 1.25rem;
		}
		.group-section-heading
		{
			color: teal;
			margin-top: .25rem;
		}
		.status-error
		{
			color: crimson;
		}
		.status-skipped
		{
			color: gray;
		}
		.warning-message
		{
			color: peru;
			font-size: 1.25rem;
		}
		table.section
		{
			width: 100%;
			border-collapse: collapse;
		}
		table.section td
		{
			width: 50%;
		}

		table.section tr.odd
		{
			background-color: #ddd;
		}

		table.section td.value,
		table.section td.time
		{
			text-align: right;
		}

		table#totals
		{
			font-weight: bold;
		}
	</style>
</head>
<?php
	function h(string $s){return htmlspecialchars($s);}

	/** @var BenchmarkHandler $handler The handler with the data to print */
?>
<body>
	<div id="main">
	<div id="warning-messages">
		<?php foreach(Output::$warnings as $warning): ?>
			<div class="warning-message"><?= h($warning) ?></div>
		<?php endforeach; ?>
	</div>
	<h1 id="title"><?= h(TITLE) ?></h1>
	<div id="sysinfos">
		<h2 class="section-heading">SYSTEM INFO</h2>
		<table id="sysinfos" class="section">
			<?php $i = 0; foreach($handler->data['sysinfo'] as $name => $e): ?>
				<tr class="sysinfo <?= $i%2 ? 'odd' : 'even' ?>">
					<td class="text"><?= h($e['text']) ?></td>
					<td class="value"><?= h($e['value']) ?></td>
				</tr>
			<?php $i++; endforeach; ?>
		</table>

		<h2 class="section-heading">BENCHMARK RESULTS (sec.)</h2>
		<?php foreach($handler->groups as $group): ?>
			<h3 class="section-heading"><?= h($group) ?></h3>
			<table class="section group-results">
				<?php $i = 0; foreach($handler->data['results'] as $name => $e): if($e['group'] === $group): ?>
					<tr class="result status-<?= h($e['status']) ?> <?= $i%2 ? 'odd' : 'even' ?>">
						<td class="name"><?= sprintf('[%s] %s', h($e['status']), h($name)) ?></td>
						<?php if($e['status'] === 'error'): ?>
							<td class="time"><?= h($e['error']) ?></td>
						<?php else: ?>
							<td class="time"><?= number_format(h((string)$e['time']), DECIMAL_PLACES) ?></td>
						<?php endif; ?>
					</tr>
				<?php $i++; endif; endforeach; ?>
			</table>
		<?php endforeach; ?>

		<h2 class="section-heading">TOTALS</h2>
		<table id="totals" class="section">
			<?php $i = 0; foreach($handler->data['totals'] as $name => $e): ?>
				<tr class="total <?= $i%2 ? 'odd' : 'even' ?>">
					<td class="text"><?= h($e['text']) ?></td>
					<td class="value"><?= h(number_format($e['value'], DECIMAL_PLACES)) ?></td>
				</tr>
			<?php $i++; endforeach; ?>
		</table>
	</div>
	</div>
</body>
</html>
	