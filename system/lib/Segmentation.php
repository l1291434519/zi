<?php
/**
 *中文分词
 *
 */
class Segmentation
{
    var $options = array('lowercase' => TRUE, 'segment_english' => FALSE);
    var $dict_name = 'Unknown';
    var $dict_words = array();
    function setLowercase($value)
    {
        if ($value) {
            $this->options['lowercase'] = TRUE;
        } else {
            $this->options['lowercase'] = FALSE;
        }
        return TRUE;
    }
    function setSegmentEnglish($value)
    {
        if ($value) {
            $this->options['segment_english'] = TRUE;
        } else {
            $this->options['segment_english'] = FALSE;
        }
        return TRUE;
    }
    function load($dict_file)
    {
        if (!file_exists($dict_file)) {
            return FALSE;
        }
        $fp   = fopen($dict_file, 'r');
        $temp = fgets($fp, 1024);
        if ($temp === FALSE) {
            return FALSE;
        } else {
            if (strpos($temp, "t") !== FALSE) {
                list($dict_type, $dict_name) = explode("t", trim($temp));
            } else {
                $dict_type = trim($temp);
                $dict_name = 'Unknown';
            }
            $this->dict_name = $dict_name;
            if ($dict_type !== 'DICT_WORD_W') {
                return FALSE;
            }
        }
        while (!feof($fp)) {
            $this->dict_words[rtrim(fgets($fp, 32))] = 1;
        }
        fclose($fp);
        return TRUE;
    }
    function getDictName()
    {
        return $this->dict_name;
    }
    function segmentString($str)
    {
        if (count($this->dict_words) === 0) {
            return FALSE;
        }
        $lines = explode("n", $str);
        return $this->_segmentLines($lines);
    }
    function segmentFile($filename)
    {
        if (count($this->dict_words) === 0) {
            return FALSE;
        }
        $lines = file($filename);
        return $this->_segmentLines($lines);
    }
    function _segmentLines($lines)
    {
        $contents_segmented = '';
        foreach ($lines as $line) {
            $contents_segmented .= $this->_segmentLine(rtrim($line)) . " n";
        }
        do {
            $contents_segmented = str_replace(' ', ' ', $contents_segmented);
        } while (strpos($contents_segmented, ' ') !== FALSE);
        return $contents_segmented;
    }
}
?>