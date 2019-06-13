<?php
error_reporting(0);
session_start();

require_once 'phpQuery.php';

$_SESSION['name'] =  $_POST['login'];
$_SESSION['secoundName'] = $_POST['password'];
$_SESSION['them_url'] = $_GET['action'];
$_SESSION['them_name'] = $_GET['gman123'];
$_SESSION['gname'] =  $_POST['gname'];
$_SESSION['glink'] = $_POST['glink'];

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

//начинаем cURL
function take_text_by_curl($used_url, $logCredits)
{
    $kook = $_SESSION['name'] . '_rp.txt';
    $ch = curl_init();
    $url = $used_url;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $kook);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $kook);
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
      //  echo 'Ошибка логина';
        return $page_text;
    } else {
        return $page_text; //в случае ошибки логина переменная равна NULL
    }
}

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
    //    echo "\nОткрыть тему: ";
    //$linkNumber = readline(': ');
    // ссылка на тему
    $url_them[0] = 'http://roleplay.lainurol.com/' . $text_elementsLG[$linkNumber * 4 - 4];
    $regexp = '!amp;!si';
    $url_them[0] = preg_replace($regexp, '', $url_them[0]);
    // название темы для именования файла
    $url_them[1] = $text_elementsLG[$linkNumber * 4 - 3]. " (" . $text_elementsLG[$linkNumber * 4 - 1]  . ")" . ".txt";
    // echo $url_them[1];
    $regexp_for_delete = '!\?!si';
    $url_them[1] = preg_replace($regexp_for_delete, '', $url_them[1]);
    return $url_them;
}

//функция парса выбранной темы
function read_them($url_them, $logCredits)
{
    $page_text = take_text_by_curl($url_them[0], $logCredits);
    $text_for_parce = phpQuery::newDocument($page_text);
    $elem = $text_for_parce->find('.postbody');
    //$elem=iconv('windows-1251','utf-8',$elem); // содержит исходный текст поста из тега postbody
    //пишем регулярное выражение чтобы удалить лишние элементы и подписи из поста
    $regexp_Post = '!(<br>_________________<br>)(.*?)(</span>)|(<)(.*?)(>)!si';
    $text_Post = preg_replace($regexp_Post, "\n", $elem);
    //    $text_Post = text_convert($text_Post);
    $regexp_for_delete = '!(\?)|(\,)!si';
    //    $url_them[1] = preg_replace($regexp_for_delete, '', $url_them[1]);

    $filePath = './files/' . $url_them[1] . '.txt';

    file_put_contents($filePath, $text_Post);
    //    require_once ('result_text.html');
    //    require_once ('show_me_text.php');
    return $text_Post;
}

function arr_to_elem($arr_name)
{
    $text_from_arr = '';
    for ($i = 0; $i < count($arr_name); $i++) {
        $text_from_arr = $text_from_arr . $arr_name[$i];
    }
    return $text_from_arr;
}

function get_full_them ($url_them, $logCredits)
{
    //создаем имя для файла
    //$url_them[1] = text_convert($url_them[1]);
    $fileName = './files/' . $_SESSION['name'] . $url_them[1] . '.txt';
    //вызываем простой read_them, на случай если в теме всего 1 страница
    $text_Post_all = read_them($url_them, $logCredits);
    //$result_text = "";
    //получаем текст всей страницы и выбираем из него ссылка на страницы с номерами
    //создаем массив сыслок $tt
    $full_page_text = take_text_by_curl($url_them[0], $logCredits);
    $regexp_more_pages = '!(viewtopic\.php\?t=([0-9]*)&amp;postdays=([0-9]*)&amp;postorder=([a-zA-Z]*)&amp;start=([0-9]*))!si';
    //$first_page_flag = preg_match_all($regexp_more_pages,$full_page_text, $tt);
    preg_match_all($regexp_more_pages,$full_page_text, $tt);
    $arr_links1 = array_unique($tt[0]);
    //vardump ($arr_links1);
    file_put_contents($fileName, $text_Post_all);
    //если массив пустой то читаем тему read_them вызванным выше
    if ($arr_links1!=NULL) {
        //для отладки
        //echo "\n"."есть несколько страниц!/n";
        //для отладки
       // var_dump($arr_links1);
        //делаем ссылку на первую страницу
        $first_page_link = $arr_links1[0];
        $regexp_for_1p = '!start=([0-9]*)!si';
        $first_page_link = preg_replace($regexp_for_1p, "start=0", $first_page_link);
        $first_page_link = 'http://roleplay.lainurol.com/' . $first_page_link;
        //для отладки
        //echo "\n"."$first_page_link";
        //парсим получнную первую страницу
        $page_text = take_text_by_curl($first_page_link, $logCredits);
        $text_for_parce = phpQuery::newDocument($page_text);
        $elem = $text_for_parce->find('.postbody');
        //$elem=iconv('windows-1251','utf-8',$elem); // содержит исходный текст поста из тега postbody
        $regexp_Post = '!(<br>_________________<br>)(.*?)(</span>)|(<)(.*?)(>)!si';
        $text_Post_all = preg_replace($regexp_Post, "\n", $elem);
        $q = 0;

        $first_page_fl = 0;
        //перебираем массив ссылок, парся каждую ссылку и складывая получившийся текст в массив $text_post[]
        foreach ($arr_links1 as $link) {
            //для отладки
       // echo "\n"."зашли в цикл ссылок";
            $url1 = 'http://roleplay.lainurol.com/' . $link;
            $regexp = '!amp;!si';
            $url1 = preg_replace($regexp, '', $url1);

            $page_text = take_text_by_curl($url1, $logCredits);
            $first_page_fl = 1;
            $text_for_parce = phpQuery::newDocument($page_text);
            $elem = $text_for_parce->find('.postbody');
            //$elem=iconv('windows-1251','utf-8',$elem); // содержит исходный текст поста из тега postbody
            //пишем регулярное выражение~
            $regexp_Post = '!(<br>_________________<br>)(.*?)(</span>)|(<)(.*?)(>)!si';
            $text_Post[$q] = preg_replace($regexp_Post, "\n", $elem);
            //$text_Post_all = "$text_Post_all" . "$text_Post[$q]";
            file_put_contents($fileName, $text_Post[$q], FILE_APPEND);
            $q++;
        }
        //для отладки
       // echo "\n"."массив с текстом";
        //var_dump ($text_Post);
        
        
            //    $url_them[1] =  $url_them[1] . ".txt";
            //    $regexp_for_delete = '!\?!si';
            //$url_them[1] = preg_replace($regexp_for_delete, '', $url_them[1]);
            //продублированно в начале строки
            // $url_them[1] = text_convert($url_them[1]);
            // $fileName = './files/' . $url_them[1] . '.txt';

          //  file_put_contents($fileName, $text_Post_all);
            
           // foreach ($text_Post as $text) {
               // $text_Post_all = $text_Post_all . $text;
                //file_put_contents($fileName, $text, FILE_APPEND);
            //}
    }
   // file_put_contents($fileName, $text_Post_all);
        return $text_Post_all;
}

    //=====================================
    //проверка на ошибки
    // if ($page_decode_text===FALSE)
    // {
    // echo "error: " . curl_error($ch);
    // }
    // //заканчиваем cURL
    // curl_close($ch);
    
//    $logCredits = ForumLogIn();
//    $page_text = take_text_by_curl('http://roleplay.lainurol.com/login.php', $logCredits);
//    $text_elementsLG = main_page_parce($page_text);
//    $chosen_url_them = them_selsect($text_elementsLG);
//    $lust_posts = read_them($chosen_url_them, $logCredits);
    ?>


