<?php
declare(strict_types=1);

//Line length on the output
define('LINE_LEN_HTML', 80);
define('LINE_LEN_CLI', 60);
define('HTML_TPL_FILE', './template.html');
define('OUTPUT_PLACEHOLDER', '{{output}}');
define('WARNING_MESSAGES_PLACEHOLDER', '{{warning-messages}}');
define('ERROR_MESSAGE_TPL', '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body><pre class="error-message" style="color:crimson;">Error. %s</pre></body></html>');

class Output
{
	/** @var string[] */
	public static $warnings = [];

	private function __construct() 
	{
		//Prevent making an object
	}

	public static function errorAndDie(string $message)
	{
		if(!IS_CLI)
		{
			@http_response_code(500);
		}

		echo IS_CLI ? $message : sprintf(ERROR_MESSAGE_TPL, htmlspecialchars($message));
		exit(1);
	}

	public static function addWarning(string $message)
	{
		self::$warnings[] = sprintf('Warning. %s', $message);
	}

	public static function result(BenchmarkHandler $handler)
	{
		if(!$handler->runComplete)
		{
			self::errorAndDie('Cannot print results. Please call BenchmarkHandler->run() first.');
		}

		if(IS_CLI)
		{
			echo self::resultCli($handler->data);
		}
		else
		{
			echo self::generateOutputHtml2($handler->data);
		}
	}

	private static function generateOutputHtml2(array $data)
	{
		self::addWarning('Test warn!');
		require __DIR__ . '/template2.php';
	}

	private static function generateOutputHtml(array $data)
	{
		$output = '<pre class="output">';
		$output .= self::makeLineHtml('','','-');
		$output .= sprintf('<h1 id="title">%s</h1>', htmlspecialchars(TITLE));
		$output .= self::makeLineHtml('','','-');

		$output .= self::makeLineHtml('### SYSTEM INFO ','','#', '<div class="section-heading">%s%s%s</div>');
		foreach($data['sysinfo'] as $k => $d)
		{
			$tpl = '';
			$tpl .= '<div class="sysinfo" data-key="'.$k.'" data-value="'.htmlspecialchars((string)$d['value']).'">';
				$tpl .= '<span class="name">%s</span>%s<span class="value">%s</span>';
			$tpl .= '</div>';
			$output .= self::makeLineHtml($d['text'], $d['value'], '.', $tpl);
		}

		$output .= self::makeLineHtml('### BENCHMARK RESULTS [seconds] ','','#', '<div  class="section-heading">%s%s%s</div>');
		$lastGroup = '';
		foreach($data['results'] as $d)
		{
			//Show group heading
			if($lastGroup != $d['group'])
			{
				$output .= self::makeLineHtml($d['group'] . ' ','','-', '<div  class="group-section-heading">%s%s%s</div>');
			}

			$status = $d['status'];
			$name = sprintf('[%s]%s', $status, $d['name']);
			$time = number_format($d['time'], DECIMAL_PLACES);

			$tpl = '';
			$tpl .= '<div class="result '.$status.'-result" data-status="'.$status.'" data-time="'.$d['time'].'">';
				$tpl .= '<span class="name">%s</span>%s<span class="value">%s</span>';
				$tpl .= $status === 'error' ? '<div class="error">'.htmlspecialchars($d['error']).'</div>' : '';
			$tpl .= '</div>';
			$output .= self::makeLineHtml($name, $time, '.', $tpl);

			$lastGroup = $d['group'];
		}

		$output .= self::makeLineHtml('### TOTALS ','','#', '<div class="section-heading">%s%s%s</div>');
		foreach($data['totals'] as $k => $d)
		{
			$tpl = '';
			$tpl .= '<div class="total" data-key="'.$k.'" data-value="'.htmlspecialchars((string)$d['value']).'">';
				$tpl .= '<span class="name">%s</span>%s<span class="value">%s</span>';
			$tpl .= '</div>';

			$output .= self::makeLineHtml($d['text'], number_format($d['value'], DECIMAL_PLACES), '.', $tpl);
		}

		//Add data as JSON to textarea
		$output .= self::makeLineHtml('### JSON DATA ','','#', '<div class="section-heading">%s%s%s</div>');
		$output .= sprintf('<input type="text" id="json-data" readonly="" onclick="this.focus();this.select();" value="%s">', htmlspecialchars(json_encode($data)));
		$output .= '</pre>';

		//Add data to JS
		$output .= '<script>';
		$output .= sprintf('var jsonData = %s;', json_encode($data));
		$output .= 'console.log(jsonData);';
		$output .= '</script>';

		return self::composeHtmlDocument($output);
	}

	/**
	 * @return string
	 */
	private static function composeHtmlDocument(string $output)
	{
		$html = file_get_contents(HTML_TPL_FILE);
		$html = str_replace(OUTPUT_PLACEHOLDER, $output, $html);
		$html = str_replace(WARNING_MESSAGES_PLACEHOLDER, empty(self::$warnings) ? '' : implode("\n", self::$warnings) ,$html);

		return $html;
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

	private static function resultCli(array $data)
	{

	}
}