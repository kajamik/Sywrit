<?php
# @Progetto: Gamelite 3.0
# @Ultima modifica: 02-7-2017 Di {{last_modified_by}}
# @Copyright: Daniele Caluri - Giovanni D'Ippolito


namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Articoli;
use App\Models\Review;
use App\Models\Preview;
use App\Models\InfoGiochi;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Giochi;
use App\Models\Piattaforme;
use App\Models\Eventi;
use App\Models\Video;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use GuzzleHttp\Client;

class ToolController extends Controller {

	// 1 - convertire date recensioni
	// 2 - convertire date anteprime
	// 3 - convertire date articoli
	// 4 - creo slug giochi di infogiochi
	// 5 - creo slug per publisher
	// 6 - creo slug per developer
	// 7 - creo slug dei video
	// 8 - creo slug degli eventi
	// 9 - creo pivot_developer
	// 10 - creo pivot_publisher
	// 11 - sistemato caratteri giochi
	// 12 - convertire utenti
	// 13 - nuovo slug
	// 14 - cambio id_autore2

	public function getUpdate($id) {

		// 1 - 2 - 5 - 6 - 7 - 8 - 12

			// 3 4 9 10 11
		if ($id == 'completo') {
			$review	=	Review::get();
			$preview	=	Preview::get();
			$publisher	=	Publisher::get();
			$developer	= Developer::get();
			$eventi = Eventi::get();
			$utente = \DB::table('utente')->get();
			$video = Video::get();

			echo '<textarea rows="50" cols="105">';

			foreach ($review as $value) {
				Date::setLocale('it');
				$date = Date::parse($value->created_at)->format('Y-m-d H:i:s');
				$textarea	=	"UPDATE `Recensione` SET `created_at` = '$date' WHERE `Recensione`.`id` = $value->id;\n";
				echo $textarea;
			}
			foreach ($preview as $value) {
				Date::setLocale('it');
				$date = Date::parse($value->data)->format('Y-m-d H:i:s');
				$textarea	=	"UPDATE `Preview` SET `created_at` = '$date' WHERE `Preview`.`id` = $value->id;\n";
				echo $textarea;
			}
			foreach ($publisher as $row)	{
				$id_dev	=	$row->id;
				$slug	=	$id_dev.'-'.str_slug($row->nome, '-');
				$textarea	=	"UPDATE `Publisher` SET `slug` = '$slug' WHERE `Publisher`.`id` = $id_dev;\n";
				echo $textarea;
			}
			foreach ($developer as $row)	{
				$id_dev	=	$row->id;
				$slug	=	$id_dev.'-'.str_slug($row->nome, '-');
				$textarea	=	"UPDATE `Developer` SET `slug` = '$slug' WHERE `Developer`.`id` = $id_dev;\n";
				echo $textarea;
			}
			foreach ($eventi as $value) {
				$id_evento = $value->id;
				$title = str_slug($value->nome, '-');
				$slug = str_replace('039', '', $title);
				$textarea	=	"UPDATE `Eventi` SET `slug` = '$slug' WHERE `eventi`.`id` = $id_evento;\n";
				echo $textarea;
			}
			foreach ($utente as $value) {
				$compleanno = Date::parse($value->data_nascita)->format('Y-m-d');
				$registrato = Date::parse($value->data_registrazione)->format('Y-m-d H:i:s');
				$slug = str_slug($value->username, '-');
				echo  "INSERT INTO users (`id`,`username`,`name`,`last_name`,`email`,`nascita`,`created_at`,`updated_at`,`slug`) VALUES ('$value->id','$value->username','$value->nome','$value->cognome','$value->email','$compleanno','$registrato','$registrato','$value->id-$slug');\n";
			}
			foreach ($video as $value) {
				$id_video = $value->id;
				$title = str_slug($value->nome, '-');
				$slug = str_replace('039', '', $title);
				$textarea	=	"UPDATE `Video` SET `slug` = '$id_video-$slug' WHERE `video`.`id` = $id_video;\n";
				echo $textarea;
			}
			echo "</textarea>";

		}












		if ($id == '1')	{
			$review	=	Review::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($review as $value) {
				Date::setLocale('it');
				$date = Date::parse($value->created_at)->format('Y-m-d H:i:s');
				$textarea	=	"UPDATE `Recensione` SET `created_at` = '$date' WHERE `Recensione`.`id` = $value->id;\n";
				echo $textarea;
			}
			echo "</textarea>";
		} elseif ($id == '2') {
			$preview	=	Preview::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($preview as $value) {
				Date::setLocale('it');
				$date = Date::parse($value->created_at)->format('Y-m-d H:i:s');
				$textarea	=	"UPDATE `Preview` SET `created_at` = '$date' WHERE `Preview`.`id` = $value->id;\n";
				echo $textarea;
			}
			echo "</textarea>";
		} elseif ($id == '3') {
			$article	=	Articoli::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($article as $value) {
				Date::setLocale('it');
				$date = Date::parse($value->created_at)->format('Y-m-d H:i:s');
				$textarea	=	"UPDATE `Articoli` SET `created_at` = '$date' WHERE `Articoli`.`id` = $value->id;\n";
				echo $textarea;
			}
			echo "</textarea>";
		} elseif ($id == '4') {
			$giochi_info = InfoGiochi::get();
			/* CREIAMO SLUG DI INFOGIOCHI */
			echo '<textarea rows="50" cols="105">';
			foreach ($giochi_info as $row)	{
				$giochi = Giochi::where('id', $row->id_gioco)->first();
				$console = Piattaforme::where('id', $row->id_console)->get();
				foreach ($console as $row2)	{
					$id_info = $row->id;
					$id_gioco = $giochi->id;
					$title = str_slug($giochi->nome, '-');
					$abb_cat	=	str_slug($row2->abb_cat, '-');
					$nome_gioco = str_replace('039', '', $title);

					$url = $id_gioco.'-'.$nome_gioco.'-'.$abb_cat;
					echo	"UPDATE `info_giochi` SET `slug` = '$url' WHERE `info_giochi`.`id` = $id_info;\n";
				}
			}
			echo "</textarea>";
		} elseif ($id == '5') {
			$publisher	=	Publisher::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($publisher as $row)	{
				$id_dev	=	$row->id;
				$slug	=	$id_dev.'-'.str_slug($row->nome, '-');
				$textarea	=	"UPDATE `Publisher` SET `slug` = '$slug' WHERE `Publisher`.`id` = $id_dev;\n";
				echo $textarea;
			}
			echo "</textarea>";
		} elseif ($id == '6') {
			$developer	= Developer::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($developer as $row)	{
				$id_dev	=	$row->id;
				$slug	=	$id_dev.'-'.str_slug($row->nome, '-');
				$textarea	=	"UPDATE `Developer` SET `slug` = '$slug' WHERE `Developer`.`id` = $id_dev;\n";
				echo $textarea;
			}
			echo "</textarea>";
		} elseif ($id == '7') {
			$video = Video::get();
			echo '<textarea rows="50" cols="130">';
			foreach ($video as $value) {
				$id_video = $value->id;
				$title = str_slug($value->nome, '-');
				$slug = str_replace('039', '', $title);
				$textarea	=	"UPDATE `Video` SET `slug` = '$slug' WHERE `video`.`id` = $id_video;\n";
				echo $textarea;
			}
			echo "</textarea>";
		} elseif ($id == '8') {
			$eventi = Eventi::get();
			echo '<textarea rows="50" cols="130">';
			foreach ($eventi as $value) {
				$id_evento = $value->id;
				$title = str_slug($value->nome, '-');
				$slug = str_replace('039', '', $title);
				$textarea	=	"UPDATE `Eventi` SET `slug` = '$slug' WHERE `eventi`.`id` = $id_evento;\n";
				echo $textarea;
			}
			echo "</textarea>";
		} elseif ($id == '9') {
			$giochi = Giochi::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($giochi as $value) {
				if (!empty($value->developer)){

					$prelievo_devs = explode(',', $value->developer);
					$elenco_dev = array();
					foreach($prelievo_devs as $prelievo_dev)
					{
						$elenco_dev[] = Developer::where('id', $prelievo_dev)->first();
					}
					//INSERT INTO `developer_pivot` (`id_gioco`, `id_dev`) VALUES ('', '0', '6')

					foreach ($elenco_dev as $valueS) {
						echo  "INSERT INTO developer_pivot (`id_gioco`,`id_dev`) VALUES ('$value->id','$valueS->id');\n";
					}
				}
			}
			echo "</textarea>";
		} elseif ($id == '10') {
			$giochi = Giochi::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($giochi as $value) {
				if (!empty($value->publisher)){

					$prelievo_pubs = explode(',', $value->publisher);
					$elenco_pub = array();
					foreach($prelievo_pubs as $prelievo_pub)
					{
						$elenco_pub[] = Publisher::where('id', $prelievo_pub)->first();
					}
					//INSERT INTO `publisher_pivot` (`id_gioco`, `id_dev`) VALUES ('', '0', '6')

					foreach ($elenco_pub as $valueS) {
						echo  "INSERT INTO publisher_pivot (`id_gioco`,`id_pub`) VALUES ('$value->id','$valueS->id');\n";
					}
				}
			}
			echo "</textarea>";
		}elseif ($id == '11') {
			$giochi = Giochi::get();
			echo '<textarea rows="50" cols="105">';
			foreach ($giochi as $value) {
				$nome_entity = str_replace("&#039;", "\'", $value->nome);
				$nome_entity2 = str_replace("&amp;", "&", $nome_entity);
				// $nome = addslashes($nome_entity);
				echo "UPDATE `Giochi` SET `nome` = '$nome_entity2' WHERE `giochi`.`id` = $value->id;\n";
			}
			echo "</textarea>";
		} elseif ($id == '12') {
			$utente = \DB::table('utente')->get();
			echo '<textarea rows="50" cols="105">';
			foreach ($utente as $value) {
				$compleanno = Date::parse($value->data_nascita)->format('Y-m-d');
				$registrato = Date::parse($value->data_registrazione)->format('Y-m-d H:i:s');
				$slug = str_slug($value->username, '-');
				echo  "INSERT INTO users (`id`,`username`,`name`,`last_name`,`email`,`nascita`,`created_at`,`updated_at`,`slug`) VALUES ('$value->id','$value->username','$value->nome','$value->cognome','$value->email','$compleanno','$registrato','$registrato','$value->id-$slug');\n";
			}
			echo "</textarea>";
		}		if ($id == '13')	{
					$review	=	Review::get();
					echo '<textarea rows="50" cols="105">';
					foreach ($review as $value) {
						$id	=	$value->id;
						$slug	=	$id.'-'.$value->slug;
						$textarea	=	"UPDATE `recensione` SET `slug` = '$slug' WHERE `recensione`.`id` = $value->id;\n";
						echo $textarea;
					}
					echo "</textarea>";
				}
				if ($id == '14')	{
					$review	=	Review::where('id_autore2', '0')->get();
					echo '<textarea rows="50" cols="105">';
					foreach ($review as $value) {
						$textarea	=	"UPDATE `recensione` SET `id_autore2` = null WHERE `recensione`.`id` = $value->id;\n";
						echo $textarea;
					}
					echo "</textarea>";
				}
				if($id == '20') {
					$url = 'https://gamelite.net/forum/';
					$apiKey = 'aa7c9d8308921209d4722e27a91a55ce';//'9626eb3591ca35eda5deb57682f83ce4';
					$point = '/forums/topics';
					$endpoint = $url. 'api'. $point. '?key='. $apiKey . '&forum=4&title=test2&post=corpo';

					 $options = [
												'headers' => [
													//'Content-Type' => 'application/x-www-form-urlencoded',
													'Accept' => 'application/json',
													//'Authorization' => 'Bearer '. $apiKey
												],
												'json' => [
													'forum' => 4,
													'title' => 'titolo',
													'post'	=> 'corpo'
												]
					];

					$client = new Client();
					$request = $client->post($endpoint);
					//$request->send($request);
					return $request;
				}
	} //chiudo funzione


	public function postForumAPI(Request $request)
	{
			$url = 'https://gamelite.net/forum/';
			$apiKey = 'aa7c9d8308921209d4722e27a91a55ce';//'9626eb3591ca35eda5deb57682f83ce4';
			$point = '/forums/topics';
			$endpoint = $url. 'api'. $point. '?key='. $apiKey;

			 $options = [
										/*'headers' => [
											'Content-Type' => 'application/json',
											'Accept' => 'application/json',
											//'Authorization' => 'Bearer '. $apiKey
										],*/
										'form_params' => [
											'forum' => 4,
											'title' => $request->title,
											'post'	=> $request->body
										]
								];

			$client = new Client();
			$request = $client->post($endpoint, $options);
	}

}
