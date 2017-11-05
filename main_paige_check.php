<?php
	//mb_internal_encoding("UTF-8");
	//подключение библиотеки phpQuerry
	require_once '/home/i/project_for_forum/phpQuery.php';
	//данные логина
	echo "\nлогин";
	$ec=readline(': ');
	echo "\nпароль";
	$eg=readline(': ');
	$ec=iconv('utf-8','windows-1251',$ec);
	$logCredits = [
		"username"=> $ec,
		"password"=> $eg,
		"login"=>" "
		];
		//начинаем cURL
	$ch = curl_init();
	$url='http://roleplay.lainurol.com/login.php';

	curl_setopt($ch, CURLOPT_URL, $url);
	// curl_setopt($ch, CURLOPT_USERAGENT, "Version 60.0.3112.113 (Developer Build) Built on Ubuntu , running on elementary 0.4.1 (64-bit)");
	curl_setopt($ch, CURLOPT_COOKIEJAR, '/home/i/project_for_forum/gearbest.txt'); 
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/home/i/project_for_forum/gearbest.txt'); 
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $logCredits);
	$output=curl_exec($ch);
	//переходим на закрытую для не авторизированного юзера страницу
	$url='http://roleplay.lainurol.com';

	curl_setopt($ch, CURLOPT_URL, $url);
	//$output=curl_exec($ch);	
	//$page_text - переменная для хранения выкачанного текста
	$page_text=curl_exec($ch);
	$page_decode_text=iconv('windows-1251','utf-8',$page_text);
	file_put_contents('downloaded_page.txt', $page_decode_text);
	//echo $page_text;
	//парс текста
	$str=$page_text;
	//$text_for_parce=phpQuery::newDocument($str);
	//$elem=$text_for_parce->find('.postbody');
	$elem=iconv('windows-1251','utf-8',$str); // содержит исходный текст поста из тега postbody
	//пишем регулярное выражение
	$regexp = '!(Последние затронутые игры)(.*?)(<table width=100% cellpadding=2 cellspacing=1 border=0 class=forumline>)!si';
	$text3=preg_match_all ($regexp, $elem, $new);
	file_put_contents('last_games.txt', $new[0]);
	//$new[0] = блок "недавно затронутые игры"
	$text4=file_get_contents('last_games.txt');
	$regexp2='!(</th></tr>

</thead>

<tbody>

<tr>

<td class=row1 valign=top>

<table width=100% cellspacing=0 cellpadding=0 border=0 class=scrolling-table-recent>


<tr>

<td nowrap=nowrap valign=top><span class="genmed"><b>&#155;</b>&nbsp;</span></td>

<td valign=top width=100%><a href=")|(</a></em></td>

</tr>


<tr>

<td nowrap=nowrap valign=top><span class="genmed"><b>&#155;</b>&nbsp;</span></td>

<td valign=top width=100%><a href=")|(" class=genmed>)|(</a> <- <em><a href=")|(" class=genmed>)|(</a></em></td>

</tr>


</table>

</td>

</tr>

</tbody>

</table>


<table width=100% cellpadding=2 cellspacing=1 border=0 class=forumline>)!si';
	$text5=preg_split($regexp2, $text4);
	file_put_contents('from_lg.txt', $text5);
	//echo count($text5);
	//echo $text5[119];
	//0  элемент - название таблицы
	//проверка ошибки
	//==========================
	/*1 - cсылка на тему
	2 - незвание темы
	3 - ссылка на игру (тема - подъэлемент игры)
	4 - название игры*/
	//==========================
	for ($i=1; $i < 15; $i+=4) { 
		$k+=1;
		echo "\n" . $k . ". ссылка на тему: " . $text5[$i] . "| Название темы: " . $text5[$i+1] . "| Название игры: " . $text5[$i+3];
	}
	//читаем выбранную ссылку с клавиатуры
	echo "\n";
	$linkN=readline('Open them: ');
	echo "\n" . $linkN . "\n";
	$url2="roleplay.lainurol.com/" . $text5[$linkN*4-3];
	$regexp12='!(amp;)!si';
	$url2=preg_replace($regexp12 , "" , $url2);	
	echo $url2 . "\n";
	//===========================
	//открытие выбранной ссылки
	curl_setopt($ch, CURLOPT_URL, $url2);
	$page_text=curl_exec($ch);
	$page_decode_text=iconv('windows-1251','utf-8',$page_text);
	file_put_contents('downloaded_post.txt', $page_decode_text); 
	//echo $page_text;
	//парс текста
	$str=$page_text; //содержит тект всей страницы темы
	$text_for_parce=phpQuery::newDocument($str);
	$elem=$text_for_parce->find('.postbody');
	$elem=iconv('windows-1251','utf-8',$elem); // содержит исходный текст поста из тега postbody
	//пишем регулярное выражение
	$regexp = '!(<br>_________________<br>)(.*?)(</span>)|(<)(.*?)(>)!si';
	$text3=preg_split($regexp, $elem);
	file_put_contents('downloaded_post3.txt', $text3);
	//проверка на наличии нескольких страниц
	// ====================================
/*	$regexp3='!(>След.</a></b></span></td>)!si';
	$text7=file_get_contents('downloaded_post.txt');
	$fl=preg_match_all($regexp3, $text7);
	echo "\n\n\n" . $fl . "\n";
	//получение ссылки на следующую страницу
	if ($fl!=0) {
		echo "\nПродолжение на другой странице, желаете перейти? (y/n)\n";
		$linkNPfl=readline('Your chose: ');
		if ($linkNPfl=="y") {
			echo "string";
		}
	}*/
	//=====================================
	//проверка на ошибки
	if ($page_decode_text===FALSE)
	{
	echo "error: " . curl_error($ch);
	}	
	//заканчиваем cURL
	curl_close($ch);
?>