<?php
declare(strict_types=1);

//Line length on the output
define('LINE_LEN_HTML', 80);
define('LINE_LEN_CLI', 60);

//CSS Colors
define('COLOR_ERROR', 'crimson');
define('COLOR_SECTION_HEADING', 'teal');
define('COLOR_WARNING', 'orange');
define('COLOR_SKIPPED', 'gray');
define('COLOR_OK', 'forestgreen');
define('COLOR_BG', 'white');
define('FONT_SIZE', '13px');

class Output
{
	private function __construct() 
	{
		//Prevent making an object
	}

	public static function error(string $message)
	{
		if(!IS_CLI)
		{
			http_response_code(500);
		}

		echo IS_CLI ? $message : sprintf('<pre class="error" style="color: %s;">Error. %s</pre>', COLOR_ERROR, htmlspecialchars($message));
		exit(1);
	}

	public static function warning(string $message)
	{
		echo IS_CLI ? $message : sprintf('<pre class="warning" style="color: %s;">Warning. %s</pre>', COLOR_WARNING, htmlspecialchars($message));
	}

	public static function data(BenchmarkHandler $handler)
	{
		if(!$handler->runComplete)
		{
			Output::error('Cannot print results. Please call BenchmarkHandler->run() first.');
		}

		if(IS_CLI)
		{
			self::dataCli($handler->data);
		}
		else
		{
			self::dataHtml($handler->data);
		}
	}

	private static function dataHtml(array $data)
	{
		$output = '<!DOCTYPE html>';
		$output .= '<html style="font-size: '.FONT_SIZE.';">';
		$output .= '<head></head>';
		$output .= '<body style="background-color: '.COLOR_BG.'; padding: 0; margin: 1rem;">';
		$output .= '<pre>';
		$output .= self::makeLineHtml('','','-');
		$output .= sprintf('<h1 id="title" style="margin: 0;">%s</h1>', htmlspecialchars(TITLE));
		$output .= self::makeLineHtml('','','-');

		$output .= self::makeLineHtml('### SYSTEM INFO ','','#', '<div style="color: '.COLOR_SECTION_HEADING.'; margin-top: .5rem;">%s%s%s</div>');
		foreach($data['sysinfo'] as $k => $d)
		{
			$tpl = '';
			$tpl .= '<div class="sysinfo" data-key="'.$k.'" data-value="'.htmlspecialchars((string)$d['value']).'">';
				$tpl .= '<span class="name">%s</span>%s<span class="value">%s</span>';
			$tpl .= '</div>';
			$output .= self::makeLineHtml($d['text'], $d['value'], '.', $tpl);
		}

		$output .= self::makeLineHtml('### BENCHMARK RESULTS [seconds] ','','#', '<div style="color: '.COLOR_SECTION_HEADING.'; margin-top: .5rem;">%s%s%s</div>');
		$lastGroup = '';
		foreach($data['results'] as $d)
		{
			//Show group heading
			if($lastGroup != $d['group'])
			{
				$output .= self::makeLineHtml($d['group'] . ' ','','-', '<div style="color: '.COLOR_SECTION_HEADING.'; margin-top: .25rem;">%s%s%s</div>');
			}

			$name = sprintf('[%s]%s', $d['status'], $d['name']);
			$time = number_format($d['time'], DECIMAL_PLACES);

			$color = 'inherit';
			$color = $d['status'] === 'ok' ? COLOR_OK : $color;
			$color = $d['status'] === 'skipped' ? COLOR_SKIPPED : $color;
			$color = $d['status'] === 'error' ? COLOR_ERROR : $color;

			$tpl = '';
			$tpl .= '<div class="result" data-status="'.$d['status'].'" data-time="'.$d['time'].'" style="color: '.$color.';">';
				$tpl .= '<span class="name">%s</span>%s<span class="value">%s</span>';
			$tpl .= '</div>';

			$output .= self::makeLineHtml($name, $time, '.', $tpl);

			$lastGroup = $d['group'];
		}

		$output .= self::makeLineHtml('### TOTALS ','','#', '<div style="color: '.COLOR_SECTION_HEADING.'; margin-top: .5rem;">%s%s%s</div>');
		foreach($data['totals'] as $k => $d)
		{
			$tpl = '';
			$tpl .= '<div class="total" data-key="'.$k.'" data-value="'.htmlspecialchars((string)$d['value']).'">';
				$tpl .= '<span class="name">%s</span>%s<span class="value">%s</span>';
			$tpl .= '</div>';

			$output .= self::makeLineHtml($d['text'], number_format($d['value'], DECIMAL_PLACES), '.', $tpl);
		}

		$output .= '</pre>';
		$output .= '</body>';
		$output .= '</html>';

		echo $output;
	}

	private static function dataCli(array $data)
	{

	}

	/**
	 * @return string
	 */
	private static function makeLineHtml(string $start, string $end, string $pad, $tpl = '<div>%s%s%s</div>')
	{
		$len = LINE_LEN_HTML - strlen($start) - strlen($end);

		$start = IS_CLI ? $start : htmlspecialchars($start);
		$end = IS_CLI ? $start : htmlspecialchars($end);

		return sprintf($tpl, $start, str_pad('', $len, $pad), $end);
	}
}