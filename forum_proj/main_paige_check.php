<?php
session_start();
require_once 'phpQuery.php';
$_SESSION['name'] =  $_POST['login'];
$_SESSION['secoundName'] = $_POST['password'];
$_SESSION['them_url'] = $_GET['action'];
echo $_GET['action'];
function ForumLogIn()
{
    //данные логина
    $ec=$_SESSION['name'];
    $eg=$_SESSION['secoundName'];
//    echo 'Логин: ';
//    $ec = fgets(STDIN);
//    $eg = readline('Пароль: ');
    $ec = iconv('utf-8', 'windows-1251', $ec);
    $logCredits = [
        "username" => $ec,
        "password" => $eg,
        "login" => " "
    ];
    return $logCredits;
}

$logCredits = ForumLogIn();

//начинаем cURL
function take_text_by_curl($used_url, $logCredits)
{
    $ch = curl_init();
    $url = $used_url;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'gearbest_rp.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'gearbest_rp.txt');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $logCredits);
    $output = curl_exec($ch);
    curl_setopt($ch, CURLOPT_URL, $url);
    $page_text = curl_exec($ch); //переменная для хранения выкачанного текста
    curl_close($ch);
    // проверяем, удалось ли залогиниться
    $text_for_parce = phpQuery::newDocument($page_text);
    $log_check = $text_for_parce->find('.menu .mainmenu img');
    $log_check = text_convert($log_check);
    $regexp = '!Вход!si';
    $log_fl = preg_match($regexp, $log_check);
    // переменная показывающая, залогинен ли пользваотель
    if ($log_fl === 1) {
        echo 'Ошибка логина';
    } else {
        return $page_text; //в случае ошибки логина переменная равна NULL
    }
}

$page_text = take_text_by_curl('http://roleplay.lainurol.com/login.php', $logCredits);
// конверт текста
function text_convert($text)
{
    $text = iconv('windows-1251', 'utf-8', $text);
    return $text;
}

//функция получения ссылки с главноей страницы на последние затронутые игры
function main_page_parce($page_text)
{
    $arr_links = array();
    $i = 0;
    $text_for_parce = phpQuery::newDocument($page_text);
    foreach ($text_for_parce->find('.scrolling-table-recent a') as $elem) {
        $elem = pq($elem);
        // разбираем ссылки
        $links = $elem->attr('href');
        $arr_links[$i] = $links;
        $i++;
        $thems = $elem->html();
        $thems = text_convert($thems);
        $arr_links[$i] = $thems;
        $i++;
    }

//    require_once ('thems_page.html');
    return $arr_links;
}

$text_elementsLG = main_page_parce($page_text);
require_once ('test.php');
// функция вывода массива последних игр на экран
function links_console_out($text_elementsLG)
{
    //==========================
    // 0 - cсылка на тему
    // 1 - незвание темы
    // 2 - ссылка на игру (тема - подэлемент игры)
    // 3 - название игры
    //==========================
    $k = 0;
    for ($i = 0; $i < count($text_elementsLG); $i += 4) {
        $k += 1;
        echo "\n" . $k . ". ссылка на тему: " . $text_elementsLG[$i] . "| Название темы: " . $text_elementsLG[$i + 1] . "| Название игры: " . $text_elementsLG[$i + 3];
    }
}

//links_console_out($text_elementsLG);

// вывод одномерного массива массивов
function echo_arr($a)
{

    for ($i = 0; $i < count($a); $i++) {
        echo $i . ") " . $a[$i] . "\n";
    }
}

//читаем выбранную ссылку с клавиатуры
function them_selsect($text_elementsLG)
{
    echo "\nОткрыть тему: ";
    $linkNumber = readline(': ');
    // ссылка на тему
    $url_them[0] = 'http://roleplay.lainurol.com/' . $text_elementsLG[$linkNumber * 4 - 4];
    $regexp = '!amp;!si';
    $url_them[0] = preg_replace($regexp, '', $url_them[0]);
    // название темы для именования файла
    $url_them[1] = $text_elementsLG[$linkNumber * 4 - 3]. " (" . $text_elementsLG[$linkNumber * 4 - 1]  . ")" . ".txt";
    return $url_them;
}

$chosen_url_them = them_selsect($text_elementsLG);

//функция парса выбранной темы
function read_them($url_them, $logCredits)
{
    $page_text = take_text_by_curl($url_them[0], $logCredits);
    $text_for_parce = phpQuery::newDocument($page_text);
    $elem = $text_for_parce->find('.postbody');
    //$elem=iconv('windows-1251','utf-8',$elem); // содержит исходный текст поста из тега postbody
    //пишем регулярное выражение~
    $regexp_Post = '!(<br>_________________<br>)(.*?)(</span>)|(<)(.*?)(>)!si';
    $text_Post = preg_replace($regexp_Post, "\n", $elem);
    $text_Post = text_convert($text_Post);
    file_put_contents($url_them[1], $text_Post);
//    require_once ('result_text.html');
//    require_once ('show_me_text.php');
    return $text_Post;
}

$lust_posts = read_them($chosen_url_them, $logCredits);

function arr_to_elem($arr_name)
{
    $text_from_arr = '';
    for ($i = 0; $i < count($arr_name); $i++) {
        $text_from_arr = $text_from_arr . $arr_name[$i];
    }
    return $text_from_arr;
}
// echo "\n" . read_them($url_them, $logCredits) . "\n";
// iconv('windows-1251','utf-8',$page_text);
// ========================================
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
// if ($page_decode_text===FALSE)
// {
// echo "error: " . curl_error($ch);
// }
// //заканчиваем cURL
// curl_close($ch);
?>