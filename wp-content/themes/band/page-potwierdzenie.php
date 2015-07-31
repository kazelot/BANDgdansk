<?php 
/**
* Template Name: Potwierdzenie
*/
if($_GET['hash'] == md5($_GET['id'] . 'SALT')) {
    update_post_meta($_GET['id'], '_status', 'confirmed');
    
    $text = 'Dziękujemy za przesłanie zgłoszenia do Banku Nasadzeń Drzew!

Obecnie prowadzimy analizę zgłoszenia pod kątem kryteriów opisanych na stronie: http://bandgdansk.com/faq/
O kolejnych działaniach będziemy informować Państwa drogą mailową. Informacja o rozstrzygnięciu wniosku pojawi się także na stronie internetowej http://bandgdansk.com

W przypadku dodatkowych pytań prosimy o kontakt z Wydziałem Środowiska Urzędu Miejskiego w Gdańsku, tel. 58 323-68-82 lub e-mail wosr@gdansk.gda.pl';
    $email = get_post_meta($_GET['id'], '_email');

    if(!empty($email[0])) wp_mail($email[0], 'Potwierdzenie zgłoszenia', $text, 'From: Zespół BAND <no-replay@bandgdansk.com>' . "\r\n");
}
require_once(dirname(__FILE__) . '/page.php');