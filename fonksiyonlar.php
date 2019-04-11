<?php
	date_default_timezone_set('Europe/Istanbul');

	function gecerliMi($id){
		$gecerli = array('-', '_',".");
		$id = str_replace($id,$gecerli,"");
		if (!ctype_alnum($id)){
			return true;
		}else{
			return false;
		}
	}

	function ayristir($id){
		if (isset($id) and !$id == ""){
			if (strpos($id,"instagram") != false){
				$id = str_replace(substr($id, -26, strlen($id)),"/","");
				echo $id;
				if (gecerliMi($id) != false){
					return $id;
				}else{
					return false;
				}
			}elseif (strlen($id) >= 3 and strlen($id) <= 60){
				if (gecerliMi($id)){
					return $id;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	function isChachedJs($id){
		$klasor = "cache";
		$t = md5(date('d.m.Y'));
		$tarih = $klasor."/".$id."_".$t;
		$json = $klasor."/".$id."_info_".$t.".js";
		if (!file_exists($tarih)){
			$js = getir("https://i.instagram.com/api/v1/users/$id/info/");
			if ($js != false){
				$fa = fopen($json,"x+");
				fwrite($fa,$js);
				fclose($fa);
				$f = fopen($tarih,"x+");
				$t = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"),-1);
				fwrite($f,$t);
				fclose($f);
				//lo("Cache bulunmadığı için yenisi oluşturuldu");
				return $js;
			}else{
				return false;
			}
		}else{
			$js = file_get_contents($json);
			$stamp = file_get_contents($tarih);
			//lo("Cache bulunduğu için veri diskten getirildi");
			return $js;
		}
	}

	function idNumarasiylaGetir($id,$fotolar = false){
		$d = json_decode(isChachedJs($id),true);
		if ($d != false){
			if ($d["status"] == "ok"){
				if (isset($d["user"]["hd_profile_pic_versions"]["0"]["url"])){
					$bilgiler = array(
						"img" => $d['user']['hd_profile_pic_url_info']['url'],
						"w" => $d['user']['hd_profile_pic_url_info']['width'],
						"h" => $d['user']['hd_profile_pic_url_info']['height'],
						"kullaniciAdi" => $d['user']["username"],
						"name" => $d['user']["full_name"],
						"id" => $d['user']["pk"],
						"bio" => $d['user']["biography"],
						"takipci" => $d['user']["follower_count"],
						"takipEdilen" => $d['user']["following_count"],
						"postSayisi" => $d['user']["media_count"],
						"ufakResim" => $d["user"]["hd_profile_pic_versions"]["0"]["url"],
						"profilResmiAnonimmi" => $d['user']["has_anonymous_profile_picture"] ? 1 : 0,
						"gizlimi" => $d['user']["is_private"] ? 1 : 0,
						"fotolar" => $fotolar
					);
					return $bilgiler;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function getData($id){
		$js = getir("https://www.instagram.com/".$id."/");//isChached($uid);
		if ($js != false){
			$bilgiler = array();
			preg_match_all('/sharedData\s=\s(.*[^\"]);<.script>/ixU', $js, $matches, PREG_SET_ORDER);
			$data = json_decode($matches[0][1], true);
			$uid = $data['entry_data']['ProfilePage']['0']['graphql']['user']['id'];
			$f = $data['entry_data']['ProfilePage']['0']['graphql']['user']["edge_owner_to_timeline_media"]["edges"];
			$fotolar = array();
			if (isset($f) and count($f) >= 3){
				$i = 0;
				foreach($f as $fs){
					$fotolar[$i] = array(
						"tip" => $fs["node"]["__typename"],
						"url" => $fs["node"]["display_url"],
						"w" => $fs["node"]["dimensions"]["width"],
						"h" => $fs["node"]["dimensions"]["height"],
						"ufak" => $fs["node"]["thumbnail_resources"]["2"]["src"],
						"begeniSayisi" => $fs["node"]["edge_liked_by"]["count"],
						"yorumSayisi" => $fs["node"]["edge_media_to_comment"]["count"],
						"yorumlaraKapali" => $fs["node"]["comments_disabled"] ? 1 : 0,
						"konum" => $fs["node"]["location"],
						"kisaKodu" => $fs["node"]["shortcode"],
						"aciklama" => $fs["node"]["edge_media_to_caption"]["edges"]["0"]["node"]["text"],
						);
					$i++;
				}
			}
			if ($uid != false){
				return idNumarasiylaGetir($uid,$fotolar);
			}else{
				return false;
			}
		}
	}

	function getir($url=NULL){
		if($url == NULL) return false;  
		$ch = curl_init($url);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);  
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
		curl_close($ch); 
		if($httpcode == 200){ 
			return $data;
		}else{
			return false;  
		}
	}

	function lo($hubele){
		$hebele = fopen("log","a+");
		fwrite($hebele,"\n[".date('d.m.Y H:i:s')."] [".$hubele."] [".ip()."]");
		fclose($hebele);
	}

	function ip(){
		if(getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
			if (strstr($ip, ',')) {
				$tmp = explode (',', $ip);
				$ip = trim($tmp[0]);
			}
		} else {
		$ip = getenv("REMOTE_ADDR");
		}
		return $ip;
	}
?>