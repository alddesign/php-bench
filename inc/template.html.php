<!DOCTYPE html>
<?php
	function h(string $s){return htmlspecialchars($s);}

	/** @var BenchmarkHandler $handler The handler with the data to print */
?>
<html>
<head>
	<meta charset="utf-8">
	<style type="text/css"><?= file_get_contents(__DIR__ .  '/template.css') ?></style>
	<script id="php-bench-js">
		var args = <?= json_encode(ARGS) ?>;
		var data = <?= json_encode($handler->data) ?>;
		var threadsData = <?= json_encode($handler->threadsData) ?>;
	</script>
</head>
<body>
	<div id="main">
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
							<td class="time"><?= number_format(round($e['time'], DECIMAL_PLACES), DECIMAL_PLACES) ?></td>
						<?php endif; ?>
					</tr>
				<?php $i++; endif; endforeach; ?>
			</table>
		<?php endforeach; ?>

		<h2 class="section-heading">TOTAL</h2>
		<table id="totals" class="section">
			<tr class="total even">
				<td class="text">TOTAL TIME</td>
				<td class="value"><?= number_format(round($handler->data['total_time'], DECIMAL_PLACES), DECIMAL_PLACES) ?></td>
			</tr>
		</table>
	</div>
	</div>
</body>
</html>
	