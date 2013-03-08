<?php
class Timer
{
  private $m_Start;

  public function __construct()
  {
      $this->m_Start = 0.0;
  }

  private function GetMicrotime()
  {
      list($micro_seconds, $seconds) = explode(" ", microtime());
      return ((float)$micro_seconds + (float)$seconds);
  }

  public function Init()
  {
      $this->m_Start = $this->GetMicrotime();
  }

  public function GetTime($Decimals = 2)
  {
      return number_format($this->GetMicrotime() - $this->m_Start, $Decimals, '.', '');
  }
}
?>