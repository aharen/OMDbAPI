<?php
/*
The MIT License (MIT)
Copyright (c) 2015 Ahmed Khusaam (@aharen) - http://ahmed.khusaam.com
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

class OMDbAPI {
	
	private $host = 'http://www.omdbapi.com/?';
	private $api_return;
	
	/*
		Fetch Movie/TV Show
			
		$keyword (required)
			description: Movie title that you want to search
			
		$type (optional)
			description: Optionaly define type of result
			options: movie, series, episode
	*/
	public function search($keyword, $type = NULL, $year = NULL) {
		if(!isset($keyword) || empty($keyword)) {
			return $this->output('400', 'Missing required fields');
		}
		
		$api_uri = $this->host.'s='.urlencode($keyword);
			if($type !== NULL) $api_uri .= '&type='.urlencode($type);
			if($year !== NULL) $api_uri .= '&y='.urlencode($year);
		
		$this->doCurl($api_uri);
		
		$returnData = $this->api_return;
		
		return (isset($returnData->Error)) ? $this->output('400', $returnData->Error) : $this->output('200', 'OK', (object)$returnData->Search);
	}
	
	/*
		Fetch Movie/TV Show
		
		$field (required)
			description: fetch movie/tv show by title or IMDB ID
			options: accepts i (IMDB ID) or t (Title)
			
		$keyword (required)
			description: if $field is set to i then has to be IMDB ID or is set to t the Title
	*/
	public function fetch($field, $keyword) {
		if((!isset($field) && empty($field)) || (!isset($keyword) && empty($keyword))) {
			return $this->output('400', 'Missing required fields');
		}
		
		if($field != 'i' && $field != 't') {
			return $this->output('400', 'Search field should be i or t');
		}
		
		if($field == 'i' && $this->validateIMDBid($keyword) == false) {
			return $this->output('400', 'Invalid IMDB ID provided');
		}
		
		$api_uri = $this->host.$field.'='.urlencode($keyword);
		$this->doCurl($api_uri);
		
		$returnData = $this->api_return;
		
		return (isset($returnData->Error)) ? $this->output('400', $returnData->Error) : $this->output('200', 'OK', $returnData);
	}
	
	/*
		Get data from API
	*/
	private function doCurl($uri) {
		try {
			$ch = curl_init ($uri);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			curl_setopt($ch, CURLOPT_NOBODY, false);
			$rawdata = curl_exec($ch);
			curl_close ($ch);

			$this->api_return = json_decode($rawdata);
		
 		} catch (Exception $e) {			
			$this->api_return = 'Error communicating with OMDB API: '.$e->getMessage(); 
		}
	}
	
	/*
		Format return data
	*/
	private function output($code, $message, $return = NULL) {
		return (object)array(
			'code' => $code,
			'message' => $message,
			'data' => $return);
	}
	
	/*
		Validate IMDB ID
	*/
	private function validateIMDBid($imdbid) {
		return preg_match("/tt\\d{7}/", $imdbid);
	}
}