<?php 

  /****************************************************************** 

   Projectname:   CAPTCHA class 
   Version:       1.2 
   Author:        Pascal Rehfeldt <Pascal@Pascal-Rehfeldt.com> 
   Last modified: 15. March 2004 
   Copyright (C): 2003, 2004 Pascal Rehfeldt, all rights reserved
   Modified by Cristian Navalici cristian.navalici at gmail dot com 

   * GNU General Public License (Version 2, June 1991) 
   * 
   * This program is free software; you can redistribute 
   * it and/or modify it under the terms of the GNU 
   * General Public License as published by the Free 
   * Software Foundation; either version 2 of the License, 
   * or (at your option) any later version. 
   * 
   * This program is distributed in the hope that it will 
   * be useful, but WITHOUT ANY WARRANTY; without even the 
   * implied warranty of MERCHANTABILITY or FITNESS FOR A 
   * PARTICULAR PURPOSE. See the GNU General Public License 
   * for more details. 

   Description: 
   This class can generate CAPTCHAs, see README for more details! 

   Get the "Hurry up!" Font for the Captcha and 
   save it in the same directory as this file. 

   "Hurry up!" Font (c) by Andi 
   See http://www.1001fonts.com/font_details.html?font_id=2366 

  ******************************************************************/ 

  class captcha 
  { 

	private $Length; 
	private $CaptchaString; 
	private $ImageType; 
	private $Font =  'inc/fonts/verdana.ttf'; 
	private $CharWidth = 16;
	var $imageheight = 40;
	public $imagefile = ''; 

	#--------------------------------------------------------------------------------
	#        MAIN FUNCTION
	#--------------------------------------------------------------------------------
	function captcha ($imagefile = '', $length = 8, $type = 'jpeg', $letter = '') 
	{ 

	  $this->Length    = $length; 
	  $this->ImageType = $type;
	  $this->imagefile = $imagefile; 
	   
	  if ($letter == '') 
	  { 

		$this->StringGen(); 

	  } 
	  else 
	  { 

		$this->Length        = strlen($letter); 
		$this->CaptchaString = $letter; 

	  } 

	  //$this->SendHeader(); 

	  $this->MakeCaptcha(); 

	} 

#--------------------------------------------------------------------------------
#        generate the string to display
#--------------------------------------------------------------------------------

	private function StringGen () 
	{ 

	  $uppercase  = range('A', 'Z'); 
	  $numeric    = range(0, 9); 

	  $CharPool   = array_merge($uppercase, $numeric); 
	  $PoolLength = count($CharPool) - 1; 

	  for ($i = 0; $i < $this->Length; $i++) 
	  { 

		$this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)]; 

	  } 

	} 

#--------------------------------------------------------------------------------
#        send header with information about file type
#--------------------------------------------------------------------------------
	private function SendHeader () 
	{ 

	  switch ($this->ImageType) 
	  { 

		case 'jpeg': header('Content-type: image/jpeg'); break; 
		case 'png':  header('Content-type: image/png');  break; 
		default:     header('Content-type: image/png');  break; 

	  } 

	} 

#--------------------------------------------------------------------------------
#        generate the image to display
#--------------------------------------------------------------------------------
	private function MakeCaptcha () 
	{ 

	  $imagelength = $this->Length * $this->CharWidth + 65; 
	  
	  $image       = imagecreate($imagelength, $this->imageheight); 

	  $bgcolor     = imagecolorallocate($image, 222, 222, 222); 

	  $stringcolor = imagecolorallocate($image, 0, 0, 0); 
	  $linecolor   = imagecolorallocate($image, 0, 0, 0); 

	  imagettftext($image, 25, 0, 10, 35, 
				   $stringcolor, 
				   $this->Font, 
				   $this->CaptchaString); 

		$img = $this->DrawLines ($image, $imagelength);
		 
	  switch ($this->ImageType) 
	  { 
		case 'jpeg': imagejpeg($img,"../images/captcha/".$this->imagefile.".jpg",100); break; 
		case 'png':  imagepng($img);  break; 
		default:     imagepng($img);  break; 

	  } 

	} 

#--------------------------------------------------------------------------------
#        return displayed string for checking
#--------------------------------------------------------------------------------
	public function GetCaptchaString () 
	{ 

	  return $this->CaptchaString; 

	} 

#--------------------------------------------------------------------------------
#        draw some line to image
#--------------------------------------------------------------------------------
	private function DrawLines($image, $imagelength) {
	
		// define color  for lines
		$cColor=ImageColorAllocate($image,0,127,0);
		
		for ($i =0 ; $i<15 ; $i++) {
			// first coodinates are in the first half of image
			$x0coordonate = mt_rand (0, 0.5 * $imagelength);
			$y0coordonate = mt_rand (0, $this->imageheight);
			// second coodinates are in the second half of image
			$x1coordonate = mt_rand (0.5 * $imagelength,$imagelength);
			$y1coordonate = mt_rand (0, $this->imageheight);
			
			imageline ($image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );
		}
		return $image;
	} 
  } 

?> 