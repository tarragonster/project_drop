<?php

class Dashboard_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function countUsers($from = 0, $to = 0) {
		$this->db->from('user');
		if ($from > 0)
			$this->db->where('joined >=', $from);
		if ($to > 0)
			$this->db->where('joined <', $to);
		return $this->db->count_all_results();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function getNextStep($time, $i, $steps) {
		if ($i == 0)
			return $time;
		if ($steps == -1) {
			return strtotime(date('Y-m', strtotime($i . ' months', $time)), '-1');
		} else {
			return $time + $i * $steps;
		}
	}

	public function formatLabel($time, $steps, $sampleYear = false) {
		if ($steps == -1) {
			return $sampleYear ? date('M', $time) : date('M Y', $time);
		} else if ($steps == 86400) {
			return date('M d', $time);
		} else {
			return date('hA', $time);
		}
	}

	public function formatRange($startDate, $endDate) {
		$diff = $endDate - $startDate;
		if ($diff <= 86400) {
			// by hours
			return date('M d, Y', $startDate);
		} else if ($diff <= 31 * 86400) {
			// by day
			if (is_sample_years($startDate, $endDate - 86400)) {
				return date('M d', $startDate) . ' - ' . date('M d', $endDate - 86400);
			} else {
				return date('M d', $startDate) . ' - ' . date('M d, Y', $endDate - 86400);
			}
		} else {
			// by month
			if (is_sample_years($startDate, $endDate - 86400)) {
				return date('M', $startDate) . ' - ' . date('M Y', $endDate - 86400);
			} else {
				return date('M Y', $startDate) . ' - ' . date('M Y', $endDate - 86400);
			}
		}
	}

	public function getDashBoard($startDate, $endDate, $secondFromDate, $secondToDate, $is_required = true, $unix_timestamp = 0) {
		$diff = $endDate - $startDate;
		if (empty($secondFromDate) || empty($secondToDate)) {
			$secondStart = $startDate - $diff;
			$secondEnd = $startDate;
		} else {
			$secondStart = strtotime($secondFromDate);
			$secondEnd = strtotime($secondToDate) + 86400;
		}
		$sampleYear = is_sample_years($startDate, $endDate - 86400);
		if ($diff <= 86400) {
			// by hours
			$steps = 3600;
		} else if ($diff <= 31 * 86400) {
			// by day
			$steps = 86400;
		} else {
			// by month
			$steps = -1;
		}

		$params = ['unix_timestamp' => time()];
		$params['startDate'] = $startDate;
		$params['endDate'] = $endDate;
		$params['secondStart'] = $secondStart;
		$params['secondEnd'] = $secondEnd;

		// collect users
		$user_chart = [
			'first' => [
				'label' => $this->formatRange($startDate, $endDate),
				'value' => $this->countUsers($startDate, $endDate),
			],
			'second' => [
				'label' => $this->formatRange($secondStart, $secondEnd),
				'value' => $this->countUsers($secondStart, $secondEnd),
			],
		];
		$user_chart['percent'] = getPercent($user_chart['first']['value'], $user_chart['second']['value']);

		$data = [];
		$temp = $startDate;
		$i = 0;
		while ($temp < $endDate) {
			$item = [
				"label" => $this->formatLabel($temp, $steps, $sampleYear),
				"first" => $this->countUsers($temp, min($endDate, $this->getNextStep($startDate, $i + 1, $steps))),
				"second" => $this->countUsers($this->getNextStep($secondStart, $i, $steps), min($secondEnd, $this->getNextStep($secondStart, $i + 1, $steps))),
			];
			$data[] = $item;

			$i = $i + 1;
			$temp = $this->getNextStep($startDate, $i, $steps);
		}
		$user_chart['data'] = $data;
		$params['user_chart'] = $user_chart;

		return $params;
	}
}