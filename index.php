<?php

require_once('./line_class.php');

$channelAccessToken = '4fAUzLrBwgVLBRabPdu1FUyd5ulUBgxhfssDecRl4zso4iyCWJgAn/rIe6fwmbT//WrlF0QG06+2Q2UQxjKzMI/oIrPC9N0dtDEtsj6dU2ycWF65L+euCNyJAbOBqVXsVRUO77yXqaAF9oJPwkrDIwdB04t89/1O/w1cDnyilFU=
'; //Your Channel Access Token
$channelSecret = '1547f01580b29e650bce95fa14714497';//Your Channel Secret

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$groupId 	= $client->parseEvents()[0]['source']['groupId'];
$userId 	= $client->parseEvents()[0]['source']['userId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$message 	= $client->parseEvents()[0]['message'];
$profil = $client->profil($userId);
$pesan_datang = $message['text'];

if($message['type']=='sticker')
{	
	$balas = array(
							'UserID' => $profil->userId,	
                                                        'replyToken' => $replyToken,							
							'messages' => array(
								array(
										'type' => 'text',									
										'text' => 'Terima Kasih Stikernya.'										
									
									)
							)
						);
						
}
else
$pesan=str_replace(" ", "%20", $pesan_datang);
$usernm = str_replace(" ", "%20", $profil->displayName);
$url = 'http://karyakreatif.com/tebakkata2/?pesan='.$pesan.'&gr='.$groupId.'&u='.$userId.'&un='.$usernm;
$json_data = file_get_contents($url);
$url=json_decode($json_data,1);
$diterima = $url['response'];
if($message['type']=='text')
{
if($url['result'] == 404)
	{
		$balas = array(
							'UserID' => $profil->userId,	
                                                        'replyToken' => $replyToken,													
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => 'Mohon Gunakan Bahasa Indonesia Yang Benar :D.'
									)
							)
						);
				
	}
else
if($url['result'] != 100)
	{
		
		
		$balas = array(
							'UserID' => $profil->userId,
                                                        'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => 'Maaf '.$profil->displayName.' Server Kami Sedang Sibuk Sekarang.'
									)
							)
						);
				
	}
	else{
		$balas = array(
							'UserID' => $profil->userId,
                                                        'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => ''.$diterima.''
									)
							)
						);
						
	}
}
 
$result =  json_encode($balas);

file_put_contents('./reply.json',$result);


$client->replyMessage($balas);
