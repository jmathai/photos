<?php
class CMp3
{
  function length()
  {
    $retval = isset($this->length) ? intval($this->length) : '0';
    return $retval;
  }
  
	function load($filename)
	{
		$this->free();
		@$fp = fopen($filename,"rb");
		if(!$fp)
		{
			$this->error = true;
			$this->errorstring = "COULD NOT OPEN FILE";
		}

		if($fp)
		{
			$this->filename = $filename;
			$this->size = filesize($filename);
	
			$try_id3v2 = fread($fp,3);
	
			if($try_id3v2 != "ID3"){fseek($fp,0);}
	
			if($try_id3v2 == "ID3")
			{
				$idv2ver1 = ord(fread($fp,1));
				$idv2ver2 = ord(fread($fp,1));
				$this->id3v2version =  "2.$idv2ver1.$idv2ver2";
				$this->id3v2 = true;
				$idv2headerflag = $this->getbin($fp,1);
				$idv2headerlen = bindec(substr($this->getbin($fp),1).substr($this->getbin($fp),1).substr($this->getbin($fp),1).substr($this->getbin($fp),1));

				if($idv2ver1 >= 3)
				{
				
					$this->id3v2array = array();
				
					$id3v2pos = 0;
					$id3v2frameid = "XXXX";
					$id3v2type = "XRAW";

					while($id3v2pos < $idv2headerlen && $id3v2frameid != "" && ($id3v2pos + $id3v2framelen) < $this->size)
					{
						$id3v2frameid = trim(fread($fp,4));
					
						$id3v2framelen = bindec(substr($this->getbin($fp),0).substr($this->getbin($fp),0).substr($this->getbin($fp),0).substr($this->getbin($fp),0));

						#Seems to be a líle confusing... Framelength should get encoded as Syncafe, but doesn't get (at least by Winamp) So this should work.

						if(($id3v2pos + $id3v2framelen) < $this->size)
						{ 
							$id3v2frameflag = $this->getbin($fp,2);
							$id3v2framecontent = fread($fp,$id3v2framelen);
							$id3v2pos = $id3v2pos + 10 + $id3v2framelen;
	
							$id3v2type = $this->id3v2types[$id3v2frameid];


							if($id3v2type == ""){$id3v2type = "XRAW";}
	
							if($id3v2type == "XRAW")
							{
								$this->id3v2array[$id3v2frameid] = $id3v2framecontent;
							}
		
							if($id3v2type == "TEXT")
							{
								$this->id3v2array[$id3v2frameid] = trim(substr($id3v2framecontent,1));
							}

							if($id3v2type == "COMM")
							{
								$commstring = substr($id3v2framecontent,4);
								$this->id3v2array[$id3v2frameid] = trim($commstring);
							}

							if($id3v2type == "GENR")
							{
								$id3v2framecontent = trim(substr($id3v2framecontent,1));
								$genrfs = substr($id3v2framecontent,0,1);
								if($genrfs == "(" )
								{
									$id3v2framecontent = substr($id3v2framecontent,1);					
									$number = $id3v2framecontent + 1 -1;
									$this->id3v2array[$id3v2frameid] = $this->gentable[$number];
								}

								if($genrfs != "(" )
								{
									$this->id3v2array[$id3v2frameid] = $id3v2framecontent;
								}

							}
							
						}
						if(($id3v2pos + $id3v2framelen) >= $this->size){$this->error = true;$this->errorstring = "CORRUPT ID3V2 FRAME";}
					}
					
					$this->v2_artist = $this->id3v2array["TPE1"];
					$this->v2_title =  $this->id3v2array["TIT2"];
					$this->v2_album = $this->id3v2array["TALB"];
					$this->v2_year = $this->id3v2array["TYER"];
					$this->v2_genre = $this->id3v2array["TCON"];
					$this->v2_comment = $this->id3v2array["COMM"];
					$this->v2_track = $this->id3v2array["TRCK"];
				}

			fseek($fp,$idv2headerlen+10);
      $current_offset = $idv2headerlen+10;
			}
	
			$searchchar = 0;
			$foundheader = false;
			$this->value = false;
			$seacrhi =0;
	
			while($searchchar != 255 && !feof($fp) && $searchi <= 1*1024)
			{
				$searchchar = ord(fread($fp,1));
				$searchi++;
			}

			$mpegfeof = feof($fp);
			if($searchi > 8*1024){$mpegfeof = true;}
    
      $current_offset += $searchi;

			if(!$mpegfeof)
			{
				$this->valid = true;

				//php4: fseek($fp,-1,SEEK_CUR);
        $current_offset--;
        fseek($fp, $current_offset);
			
				$binstring = $this->getbin($fp,4);

				$h_syn = bindec(substr($binstring,0,11));
				$h_ver = bindec(substr($binstring,11,2));
				$h_lay = bindec(substr($binstring,13,2));
				$h_crc = bindec(substr($binstring,15,1));
				$h_bit = bindec(substr($binstring,16,4));
				$h_fre = bindec(substr($binstring,20,2));
				$h_pad = bindec(substr($binstring,22,1));
				$h_pri = bindec(substr($binstring,23,1));
				$h_cha = bindec(substr($binstring,24,2));
				$h_mex = bindec(substr($binstring,26,2));
				$h_cpr = bindec(substr($binstring,28,1));
				$h_org = bindec(substr($binstring,29,1));
				$h_emp = bindec(substr($binstring,30,2));
			
	
				$bitar = 5;	
	
				if($h_lay == 3 && $h_ver == 3)
				{$bitar = 0;}
		
				if($h_lay == 2 && $h_ver == 3)
				{$bitar = 1;}
		
				if($h_lay == 1 && $h_ver == 3)
				{$bitar = 2;}
		
				if(($h_ver == 0 || $h_ver == 2) && $h_lay == 3)
				{$bitar = 3;}
		
				if(($h_ver == 0 || $h_ver == 2) && ($h_lay == 2 || $h_lay == 1))
				{$bitar = 4;}
			
		
		
				if($h_syn != 2047 || $h_ver == 1 || $h_lay == 0 || $h_bit == 15 || $h_fre == 3 || $h_emp == 2 || $bitar == 5)
				{
					$this->valid = false;
				}
		
				if($this->valid)
				{
					$this->version = $this->vertable[$h_ver];
					$this->layer = $this->laytable[$h_lay];
					$this->protection = $h_crc;
					$this->bitrate = $this->bittable[$bitar][$h_bit];
					$this->frequency = $this->freqtable[$h_ver][$h_fre];
					$this->padding = $h_pad;
					$this->private = $h_pri;
					$this->channel = $this->chantable[$h_cha];
					if($h_cha == 1){$this->mode = $this->modetable[$h_lay][$h_mex];}
					$this->copyright = $h_cpr;
					$this->original = $h_org;
					$this->emphasis = $this->emptable[$h_emp];
	
	
					$this->length = floor($this->size / ($this->bitrate * (1000/8)));
	
					$this->lengthstring = floor($this->length/60).":".str_pad( ($this->length%60) ,2,"0",STR_PAD_LEFT);
	
	
					fseek ($fp,-128,SEEK_END);
          //php3fseek($fp, $this->size - 128);

					$s_id3v1 = fread($fp,128);	
	
					if(substr($s_id3v1,0,3) == "TAG")
					{
						$this->id3v1 = true;
						$this->v1_title = trim(substr($s_id3v1,3,30));
						$this->v1_artist = trim(substr($s_id3v1,33,30));
						$this->v1_album = trim(substr($s_id3v1,63,30));
						$this->v1_year = trim(substr($s_id3v1,93,4));
						$this->v1_comment = trim(substr($s_id3v1,97,29));
						$idv1track = ord(substr($s_id3v1,126));
						if($idv1track != 0){$this->v1_track = $idv1track;}
						$gchar = substr($s_id3v1,127,1);
						$this->v1_genre = $this->gentable[ord($gchar)];
						if($this->v1_genre == ""){$this->v1_genre = "Unknown";}
					
					}

					if($this->favtag == 2)
					{
						$this->title = $this->v2_title;
						$this->artist = $this->v2_artist;
						$this->album = $this->v2_album;
						$this->year = $this->v2_year;
						$this->comment = $this->v2_comment;
						$this->track = $this->v2_track;
						$this->genre = $this->v2_genre;
	
						if($this->title == ""){$this->title = $this->v1_title;}
						if($this->artist == ""){$this->artist = $this->v1_artist;}
						if($this->album == ""){$this->album = $this->v1_album;}
						if($this->year == ""){$this->year = $this->v1_year;}
						if($this->comment == ""){$this->comment = $this->v1_comment;}
						if($this->track == ""){$this->track = $this->v1_track;}
						if($this->genre == ""){$this->genre = $this->v1_genre;}
					}

					if($this->favtag == 1)
					{
						$this->title = $this->v1_title;
						$this->artist = $this->v1_artist;
						$this->album = $this->v1_album;
						$this->year = $this->v1_year;
						$this->comment = $this->v1_comment;
						$this->track = $this->v1_track;
						$this->genre = $this->v1_genre;
	
						if($this->title == ""){$this->title = $this->v2_title;}
						if($this->artist == ""){$this->artist = $this->v2_artist;}
						if($this->album == ""){$this->album = $this->v2_album;}
						if($this->year == ""){$this->year = $this->v2_year;}
						if($this->comment == ""){$this->comment = $this->v2_comment;}
						if($this->track == ""){$this->track = $this->v2_track;}
						if($this->genre == ""){$this->genre = $this->v2_genre;}
					}
	
					/*if($this->title == "" || $this->artist == "")
					{
						$rawfile = array_pop(explode("/",$this->filename));
						$preonear = explode(".", trim($rawfile));
						array_pop($preonear);
						$fidarr = explode("-", implode(".", $preonear ) );
						$fdartist = array_shift($fidarr);
						$fdtitle = trim(implode("-",$fidarr));
		
						if($this->title == ""){$this->title = $fdtitle;}
						if($this->artist == ""){$this->artist = $fdartist;}
						if($this->title == ""){$this->title = $this->artist;$this->artist = "";}
					}*/
				}	
		
				fclose($fp);
				return $this->valid;
			}
			if($mpegfeof)
			{
				$this->valid = false;
			}
		}
	}

	function free()
	{
		unset($this->filename);
	  unset($this->size);
		unset($this->valid);

		unset($this->error);
		unset($this->errorstring);

		unset($this->version);
		unset($this->frequency);
		unset($this->layer);
		unset($this->channel);
		unset($this->mode);
		unset($this->emphasis);
		unset($this->private);
		unset($this->padding);
		unset($this->original);
		unset($this->copyright);
		unset($this->protection);

		unset($this->bitrate);
		unset($this->length);
		unset($this->lengthstring);

		unset($this->id3v1);
		unset($this->v1_title);
		unset($this->v1_artist);
		unset($this->v1_album);
		unset($this->v1_year);
		unset($this->v1_comment);
		unset($this->v1_track);
		unset($this->v1_genre);	

		unset($this->id3v2);
		unset($this->id3v2version);
		unset($this->id3v2array);
		unset($this->v2_title);
		unset($this->v2_artist);
		unset($this->v2_album);
		unset($this->v2_year);
		unset($this->v2_comment);
		unset($this->v2_track);
		unset($this->v2_genre);

		unset($this->title);
		unset($this->artist);
		unset($this->album);
		unset($this->year);
		unset($this->comment);
		unset($this->track);
		unset($this->genre);
	}

	function getbin($fp,$len = 1)
	{
		$i = 0;
		$retstr = "";
		while($i < $len)
		{
			$retstr .= str_pad(decbin(ord(fread($fp,1))),8,"0",STR_PAD_LEFT);
			$i++;
		}
		return $retstr;
	}
	
	function CMp3($favtag = 2)
	{
		$this->bittable = array(0,1,2,3,4);
		$this->freqtable = array(0,1,2,3);
		$this->modetable = array(0,1,2,3,4);
		$this->vertable = array("MPEG 2.5","","MPEG 2","MPEG 1");
		$this->laytable = array("","Layer III","Layer II","Layer I");
		$this->emptable = array("none","50/15 ms","","CCIT J.17");
		$this->chantable = array("Stereo","Joint Stereo","Stereo (Dual Channel)","Mono");

		$this->favtag = $favtag;

		$this->gentable = array(	'Blues','Classic Rock','Country','Dance','Disco','Funk','Grunge', #6
						'Hip-Hop','Jazz','Metal','New Age','Oldies','Other','Pop','R&B','Rap', #15
						'Reggae','Rock','Techno','Industrial','Alternative','Ska','Death Metal', #22
						'Pranks','Soundtrack','Euro-Techno','Ambient','Trip-Hop','Vocal', #28
						'Jazz+Funk','Fusion','Trance','Classical','Instrumental','Acid','House', #35
						'Game','Sound Clip','Gospel','Noise','AlternRock','Bass','Soul','Punk','Space', #44
						'Mediative','Instrumental Pop','Instrumental Rock','Ethnic','Gothic','Darkwave', #50
						'Techno-Industrial','Electronic','Pop-Folk','Eurodance','Dream','Southern Rock','Comedy','Cult', #58
						'Gangsta','Top 40','Christian Rap','Pop/Funk','Jungle','Native American','Cabaret', #65
						'New Wave','Psychadelic','Rave','Showtunes','Trailer','Lo-Fi','Tribal','Acid Punk', #73
						'Acid Jazz','Polka','Retro','Musical','Rock & Roll','Hard Rock', #79
						'Folk','Folk-Rock','National Folk','Swing','Fast Fusion','Beobob','Latin','Revival','Celtic', #88
						'Bluegrass','Avantgarde','Gothic Rock','Progressive Rock','Psychedelic Rock','Symphonic Rock', #94
						'Slow Rock','Big Band','Chorus','Easy Listening','Acoustic','Humour','Speech','Chanson','Opera', #103
						'Chamber Music','Sonata','Symphony','Booty Brass','Primus','Porn Groove','Satire','Slow Jam', #111
						'Club','Tango','Samba','Folklore','Ballad','Power Ballad','Rhytmic Soul','Freestyle','Duet', #120
						'Punk Rock','Drum Solo','A Capela','Euro-House','Dance Hall'); #125

		$this->id3v2types = array(	'TALB' => 'TEXT', 'TBPM' => 'TEXT', 'TCOM' => 'TEXT', 'TCON' => 'GENR',
						'TCOP' => 'TEXT', 'TDAT' => 'TEXT', 'TDLY' => 'TEXT', 'TENC' => 'TEXT',
						'TEXT' => 'TEXT', 'TFLT' => 'TEXT', 'TIME' => 'TEXT', 'TIT1' => 'TEXT',
						'TIT2' => 'TEXT', 'TIT3' => 'TEXT', 'TKEY' => 'TEXT', 'TLAN' => 'TEXT',
						'TLEN' => 'TEXT', 'TMED' => 'TEXT', 'TOAL' => 'TEXT', 'TOFN' => 'TEXT',
						'TOLY' => 'TEXT', 'TOPE' => 'TEXT', 'TORY' => 'TEXT', 'TOWN' => 'TEXT',
						'TPE1' => 'TEXT', 'TPE2' => 'TEXT', 'TPE3' => 'TEXT', 'TPE4' => 'TEXT',
						'TPOS' => 'TEXT', 'TPUB' => 'TEXT', 'TRCK' => 'TEXT', 'TRDA' => 'TEXT',
						'TRSN' => 'TEXT', 'TRSO' => 'TEXT', 'TSIZ' => 'TEXT', 'TSRC' => 'TEXT',
						'TSSE' => 'TEXT', 'TYER' => 'TEXT', 'TXXX' => 'TEXT', 'COMM' => 'COMM');

		$this->bittable[0] = array(0,32,64,96,128,160,192,224,256,288,320,352,384,416,448);
		$this->bittable[1] = array(0,32,48,56,64,80,96,112,128,160,192,224,256,320,384);
		$this->bittable[2] = array(0,32,40,48,56,64,80,96,112,128,160,192,224,256,320);
		$this->bittable[3] = array(0,32,48,56,64,80,96,112,128,144,160,176,192,224,256);
		$this->bittable[4] = array(0,8,16,24,32,40,48,56,64,80,96,112,128,144,160);

		$this->freqtable[0] = array(11025,12000,8000);
		$this->freqtable[2] = array(22050,24000,16000);
		$this->freqtable[3] = array(44100,48000,32000);

		$this->modetable[3] = array("bands 4 - 31","bands 8 to 31","bands 12 to 31","bands 16 to 31");
		$this->modetable[2] = array("bands 4 - 31","bands 8 to 31","bands 12 to 31","bands 16 to 31");
		$this->modetable[1] = array("normal","Intensity Stereo","ms Stereo","Intensity Stereo / ms Stereo");
	}
}
?>