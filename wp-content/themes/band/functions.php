<?php

if(!session_id()) session_start();

if(isset($_GET['downloadCSV']) && $_GET['downloadCSV'] == '6cd268ada554703e10f1d02103af273a')
{

    $query = new WP_Query(array('post_type'=>'zgloszenia', 'posts_per_page'=>9999));
    $payload = array(
        'ID',
        'data',
        'status',
        'imię',
        'email',
        'telefon',
        'ulica',
        'numer działki',
        'dodatkowe informacje',
        'szerokość geograficzna',
        'długość geograficzna',
        'ilość obiektów',
        'obiekty',
        
    );
    
    header('Content-type: text/csv');
    header( 'Content-Disposition: attachment;filename=zgloszenia.csv');
            
    $file = fopen('php://output', 'w');
    fputcsv($file, $payload, ',', '"');
    foreach($query->posts as $post)
    {
        $meta = get_post_meta($post->ID);
        $payload = array(
            $post->ID,
            $post->post_date,
            typyZgloszen($meta['_status'][0]),
            $meta['_imie'][0],
            $meta['_email'][0],
            $meta['_telefon'][0],
            $meta['_ulica'][0],
            $meta['_nrDzialki'][0],
            $meta['_extraInfo'][0],
            $meta['_lat'][0],
            $meta['_lng'][0],
            $meta['_ilosc'][0] ? $meta['_ilosc'][0] : 1,
            join(',', json_decode($meta['_drzewa'][0])),                        
            
        );
        
        fputcsv($file, $payload, ',', '"');

    }   
    fclose($file);        
    

    die();
}

if(isset($_GET['legacyUpload']))
{

    if(isset($_FILES['zgoda']) && !empty($_FILES['zgoda']['name'])) {
        $name = time().'_'.$_FILES['zgoda']['name'];
        move_uploaded_file($_FILES['zgoda']['tmp_name'], dirname(__FILE__) . '/oswiadczenia/' .  $name);
        $_SESSION['oswiadczenieFile'] = time().'_'.$_FILES['zgoda']['name'];
    }
    
    $_GET['ajaxSubmit'] = true;
    $_POST['ilosc'] = $_POST['liczba'];
        
    
}

if(isset($_GET['ajaxUpload']))
{
    
    if(isset($_FILES['file'])) {
        $name = time().'_'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], dirname(__FILE__) . '/oswiadczenia/' .  $name);
        $_SESSION['oswiadczenieFile'] = time().'_'.$_FILES['file']['name'];
    }
    
    die();
}

function getLocation($lat, $lng, $posOnly = false) {            
        $cacheKey = md5($lat.$lng);
        if($posOnly)
        {
            if(file_exists(dirname(__FILE__) . '/cache/pos.'.$cacheKey))
            {
                $data = file_get_contents(dirname(__FILE__) . '/cache/pos.'.$cacheKey);
                $data = unserialize($data);
            }
            else
            {
                $data = file_get_contents('http://anfo.pl:8080/c/'.$lng.'/'.$lat);
                $data = explode(', ', substr($data, 1, -1));
                if(empty($data[1]))
                {
                    return json_encode(array('error'=>true));
                }
                file_put_contents(dirname(__FILE__) . '/cache/pos.'.$cacheKey, serialize($data));
            } 
            
            return $data;           
        }
        if(file_exists(dirname(__FILE__) . '/cache/'.$cacheKey))
        {
            $response = file_get_contents(dirname(__FILE__) . '/cache/'.$cacheKey);
        }
        else
        {
            $data = file_get_contents('http://anfo.pl:8080/c/'.$lng.'/'.$lat);
            $data = explode(', ', substr($data, 1, -1));
            if(empty($data[1]))
            {
                return json_encode(array('error'=>true));
            }
            
            
            

            $url = 'http://mapa.gdansk.gda.pl/Plan/proxy.ashx?http://gdansk.gda.pl/ArcGIS/rest/services/gdaSearch/MapServer/identify?f=json&geometry=%7B%22x%22%3A'.$data[0].'%2C%22y%22%3A'.$data[1].'%2C%22spatialReference%22%3A%7B%22wkid%22%3A102175%7D%7D&tolerance=0&returnGeometry=false&mapExtent=%7B%22xmin%22%3A6539627.829787757%2C%22ymin%22%3A6030757.160772843%2C%22xmax%22%3A6540389.8313117605%2C%22ymax%22%3A6031096.886452295%2C%22spatialReference%22%3A%7B%22wkid%22%3A102175%7D%7D&imageDisplay=400%2C400%2C96&geometryType=esriGeometryPoint&sr=102175&layers=all%3A4%2C2%2C3&callback=dojo.io.script.jsonp_dojoIoScript44._jsonpCallback';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: http://mapa.gdansk.gda.pl/Plan/Mapa.aspx'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);    
            if(empty($response))
            {
                
            }
            curl_close($ch);
            file_put_contents(dirname(__FILE__) . '/cache/'.$cacheKey, $response);
        }
        //return $response;
        return substr($response, strlen('dojo.io.script.jsonp_dojoIoScript44._jsonpCallback') + 1, -2);
        
    }

if(isset($_GET['ajaxLat']))
{
        
    $response = getLocation($_GET['ajaxLat'], $_GET['ajaxLng']);    
    header('Content-type: application/json');
    echo $response;
    die();
}

if(isset($_GET['ajaxSubmit']))
{

    $id = wp_insert_post(array(
        'post_type' =>  'zgloszenia',
        'post_title'    =>  'Zgłoszenie '.date('d-m-Y H:i'),
        'post_status'   =>  'publish'        
    ));
    
    add_post_meta($id, '_drzewa', json_encode($_POST['drzewo']));
    //add_post_meta($id, '_krzewy', json_encode($_POST['krzewy']));
    add_post_meta($id, '_ilosc', $_POST['ilosc']);
    
    add_post_meta($id, '_ulica', $_POST['ulica'].' '.$_POST['nr']);
    add_post_meta($id, '_lat', $_POST['lat']);
    add_post_meta($id, '_lng', $_POST['lng']);
    
    add_post_meta($id, '_imie', $_POST['imie']);
    add_post_meta($id, '_email', $_POST['email']);
    add_post_meta($id, '_telefon', $_POST['telefon']);
    add_post_meta($id, '_nrDzialki', $_POST['nrDzialki']);
    add_post_meta($id, '_status', 'new');
    add_post_meta($id, '_extraInfo', $_POST['extraInfo']);
    add_post_meta($id, '_file', isset($_SESSION['oswiadczenieFile']) ? $_SESSION['oswiadczenieFile'] : null);
    
    
    
    $_SESSION['oswiadczenieFile'] = null;
    
    $drzewa = array();
    foreach($_POST['drzewo'] as $d)
    {
        foreach(typyObiektow() as $k => $drzewo)
        {
            if($d == $drzewo[1]) 
            {
                $drzewa[] = $k;
                break;
            }
        }       
    }
    
    $text = 'Dziękujemy za zarejestrowanie zgłoszenia na stronie www.bandgdansk.com
    
Proszę kliknąć na poniższy link w celu potwierdzenia zgłoszenia.

http://bandgdansk.com/potwierdzenie?id='.$id.'&hash='.md5($id . 'SALT').'

Jeżeli powyższy link nie działa, prosimy skopiować go do nowego okna przeglądarki i upewnić się, że nie zawiera on spacji.
 
Zamierzasz posadzić:
Drzewo: '.join(', ', $drzewa).'
Sztuk: '.(int)$_POST['ilosc'].'
Miejsce: '.$_POST['ulica'].' '.$_POST['nr'].'
 
Numer Twojego zgłoszenia: '.$id.'
 
Skontaktujemy się z Tobą w celu ustalenia szczegółów.
 
Pozdrawiamy,
 
Zespół BAND';

    wp_mail($_POST['email'], 'Prośba o potwierdzenie zgłoszenia', $text, 'From: Zespół BAND <no-replay@bandgdansk.com>' . "\r\n");

    die();
        
}
add_theme_support( 'thumbnail' );


/*-----------------------------------------------------------------------------------*/
/* Register main menu for Wordpress use
/*-----------------------------------------------------------------------------------*/
register_nav_menus( 
	array(
		'primary'	=>	__( 'Primary Menu', 'naked' ), // Register the Primary menu
		// Copy and paste the line above right here if you want to make another menu, 
		// just change the 'primary' to another name
	)
);


/*-----------------------------------------------------------------------------------*/
/* Enqueue Styles and Scripts
/*-----------------------------------------------------------------------------------*/

function naked_scripts()  { 

}
add_action( 'wp_enqueue_scripts', 'naked_scripts' ); // Register this fxn and allow Wordpress to call it automatcally in the header


add_action('init', function()
{
    register_post_type('zgloszenia', array(
        'public'    =>  true,
        'label' =>  'Zgłoszenia',
        'supports'  =>  array('title', 'editor', 'meta'),
    )); 
    
    register_post_type('czywieszze', array(
        'public'    =>  true,
        'label' =>  'Czy wiesz, że?',
        'supports'  =>  array('title', 'editor'),
    ));    
});

add_action( 'add_meta_boxes', function($post_type, $post ) {
    add_meta_box( 
        'meta',
        __( 'Dodatkowe prametry' ),
        function($post) {  
            $meta = get_post_meta($post->ID);
            
            $lat = get_post_meta( $post->ID, '_lat', true );            
            $lng = get_post_meta( $post->ID, '_lng', true );                        
            
             
            $value = get_post_meta( $post->ID, '_status', true );
            echo '<label>Aktualny status:<br /><select name="_status" style="width: 100%">';
            
            foreach(typyZgloszen() as $typ => $nazwa)
            {
                echo '<option value="'.$typ.'" '.(($typ == $value) ? 'selected="selected"':'').'">'.$nazwa.'</option>';
            }
            echo '</select></label><br />';
            
            $value = get_post_meta( $post->ID, '_reason', true );            
            echo '<label>Jeżeli odrzucono, powód<br /><input type="text" name="_reason" value="'.$value.'" maxlength="256" style="width: 100%" /></label>';                

            $value = get_post_meta( $post->ID, '_ulica', true );            
            echo '<label>Ulica<br /><input type="text" name="_ulica" value="'.$value.'" maxlength="128" style="width: 100%" /></label>';    
            
            $pos = get_post_meta($post->ID, '_gis_pos');
            if(empty($pos))
            {
                $pos = getLocation($lat, $lng, true);                
                update_post_meta( $post->ID, '_gis_pos', serialize($pos) );
            }
            else
            {
                $pos = unserialize($pos[0]);
            }
            
            
            $fullData = json_decode(getLocation($lat, $lng));
            foreach($fullData->results as $data)
            {
                if($data->layerName == 'obreby')
                {
                    echo '<label>Obręb: '.$data->value.'</label><br />';
                }
            }            

            $value = get_post_meta( $post->ID, '_nrDzialki', true );            
            echo '<label>Numer działki<br /><input type="text" name="_nrDzialki" value="'.$value.'" maxlength="128" style="width: 100%" /></label>';    
            

            echo '<label>Szerokość (LAT)<br /><input type="text" name="_lat" value="'.$lat.'" maxlength="128" style="width: 100%" /></label>';    
            
            echo '<label>Wysokość (LNG)<br /><input type="text" name="_lng" value="'.$lng.'" maxlength="128" style="width: 100%" /></label>';    
            
            echo '<label><a href="https://www.google.pl/maps/@'.$lat.','.$lng.',10399m/data=!3m1!1e3?hl=en" target="_blank">Podejrzyj na mapie Google</a> (otworzy w nowym oknie)</label><br />';
            

            
            echo '<label>Współrzędne mapy GIS: '.$pos[0].' / '.$pos[1].'</label>';
            
            
            echo '<br />';
            echo '<br />';

            $value = get_post_meta( $post->ID, '_drzewa', true );
            $value = (array)json_decode($value);
            
            echo '<label>Planowane gatunki drzew<br />';
            foreach(typyDrzew() as $drzewo => $opis)
            {
                echo '<input type="checkbox" name="_drzewa[]" value="'.$opis[1].'" '.((in_array($opis[1], $value)) ? 'checked="checked"':'').' /> '.$drzewo.', ';
            }
            echo '</label><br />';    
            
            //$value = get_post_meta( $post->ID, '_krzewy', true );
            //$value = (array)json_decode($value);
            echo '<label>Planowane gatunki krzewów<br />';
            foreach(typyKrzewow() as $drzewo => $opis)
            {
                echo '<input type="checkbox" name="_drzewa[]" value="'.$opis[1].'" '.((in_array($opis[1], $value)) ? 'checked="checked"':'').' /> '.$drzewo.', ';
            }
            echo '</label><br />';    
            
            echo '<br />';
            
            $value = get_post_meta( $post->ID, '_imie', true );
            echo '<label>Imię i nazwisko<br /><input type="text" name="_imie" value="'.$value.'" maxlength="128" style="width: 100%" /></label>';                
            $value = get_post_meta( $post->ID, '_email', true );
            echo '<label>Adres e-mail<br /><input type="text" name="_email" value="'.$value.'" maxlength="128" style="width: 100%" /></label>';                
            $value = get_post_meta( $post->ID, '_telefon', true );
            echo '<label>Telefon<br /><input type="text" name="_telefon" value="'.$value.'" maxlength="128" style="width: 100%" /></label>';                
            
            echo '<label>Dodatkowe informacje</label><br /><p style="padding: 5px; background: #d3d3d3">'.nl2br(htmlspecialchars($meta['_extraInfo'][0])).'</p>';
            
            $value = get_post_meta($post->ID, '_file', true);
            if($value) echo '<a href="../wp-content/themes/band/oswiadczenia/'.$value.'" target="_blank">wgrano oświadczenie, pobierz</a>';
            
            global $current_user;
            
            
            $file = $meta['file_mapa'][0];
            
            foreach($current_user->roles as $role) 
            {            
                if($role == 'aamrole_5563a0781c80a' || $role == 'administrator')
                {
                    echo '<label>Dodaj / zastąp plik z mapą:<br /><input type="file" name="file_mapa" /></label><br />';
                    echo "<script>jQuery( document ).ready( function($) { $( 'form#post' ).attr( 'enctype', 'multipart/form-data' ); } );</script>";
                    
                    if($file)
                    {
                        echo '<label>Usuń wgrany plik: <input type="checkbox" name="delete_file_mapa" value="1" /> tak</label><br />';
                    }
                    
                }   
            }
            
            if($file)
            {
                echo '<label>Plik załączony przez wydział geodezji: <a href="../wp-content/themes/band/mapy/'.$file.'" target="_blank">'.substr($file, strpos($file,'_') + 1).'</a></label>';
            }
        
        },
        'zgloszenia',
        'normal',
        'default'
    );      
}, 10, 2 );

add_action( 'save_post', function($post_id)
{
    foreach(array('_status', '_ulica', '_nrDzialki', '_drzewa', '_lat', '_lng', '_imie', '_telefon', '_email', '_ilosc', '_reason') as $key)
    {
        if(is_array($_POST[$key])) $_POST[$key] = json_encode($_POST[$key]);        
        update_post_meta( $post_id, $key, $_POST[$key] );
    } 
    
    if(!empty($_POST['delete_file_mapa']))
    {
        update_post_meta($post_id, 'file_mapa', '');    
    }
    else if(!empty($_FILES['file_mapa']) && !empty($_FILES['file_mapa']['tmp_name']))
    {
        $fileName = dirname(__FILE__) . '/mapy/'.microtime(true).'_'.$_FILES['file_mapa']['name'];
        move_uploaded_file($_FILES['file_mapa']['tmp_name'], $fileName);
        update_post_meta($post_id, 'file_mapa', basename($fileName));
    }   
} );

function typyZgloszen($return = null)
{
    $set = array(
        'new'   =>  'nowe zgłoszenie',
        'rejected.denied'  =>  'odrzucone',
        'accepted.step1'  =>  'zaakceptowane przez I Środowisko',
        'accepted.step3'  =>  'zaakceptowane przez II Geodezja',
        'accepted.step4'  =>  'zaakceptowane przez III Architektura',
        'accepted.step4-mpzp'  =>  'zaakceptowane przez III Architektura - brak MPZP',
        'accepted.step5'  =>  'zaakceptowane przez IV Skarb',
        'accepted.done'  =>  'zaakceptowane przez V Środowisko - przeznaczone do zasadzenia',
        'confirmed' =>  'potwierdzone przez użytkownika',
        'planted'  =>  'zasadzone',
    );    
    
    if($return) return $set[$return];
    
    return $set;
}

function typyDrzew() 
{
    return array(
        'Lipa drobnolistna' =>  array('lipa.jpg','lipa','<b>Lipa drobnolistna</b><br />Drzewo o bardzo regularnej, szerokojajowatej lub kulistej koronie. W młodości powolny do 18-20 m wys. i 10-15 m szer.'),
        'Klon' =>  array('klon.jpg','klon', '<b>Klon</b><br />Drzewo dorastające do 30 m wys., o charakterystycznej popielatej korze. Drzewo pospolite w Polsce w naturalnych zbiorowiskach oraz często sadzone przy drogach.'),
        'Brzoza brodawkowata' =>  array('brzoza.jpg','brzoza', '<b>Brzoza brodawkowata</b><br />Potrzebuje dużo światła. Bardzo dobrze znosi zanieczyszczenie powietrza. Rośnie szybko, dorasta do 20-25 m wys. i 7-9 m szer. Drzewo o malowniczej koronie.'),
        'Głóg' =>  array('glog.jpg','glog', '<b>Głóg</b><br />Małe  drzewo o kulistej koronie, ozdobne z kwiatami. Dorasta do 4-6 m wysokości. Liście małe, ciemnozielone, błyszczące. Kwiaty pełne, ciemnoczerwone, bardzo efektowne.'),
        'Dąb' =>  array('dab.jpg','jesion', '<b>Dąb</b><br />Okazałe drzewo z prostym pniem, szerokostożkowatą koroną i charakterystycznymi zaschniętymi i obwisającymi dolnymi gałęziami. Rośnie szybko. Osiąga do 25 m wysokości.'),
        'Kasztanowiec' =>  array('kasztanowiec.jpg','kasztanowiec','<b>Kasztanowiec</b><br />Drzewo do 25 m wys. i 20 m szer., o szerokiej, malowniczej koronie. Starsze egzemplarze mają gałęzie przewieszające się do samej ziemi.'),        
        'Jarząb' =>  array('jarzab.jpg','jarzab', '<b>Jarząb</b><br />Małe lub średniej wielkości drzewo o owalnej koronie. Wzrost młodych drzew stosunkowo szybki. Dorasta do 8-12 m wys. i 4-6 m szer. Liście pierzaste, jesienią żółte lub pomarańczowe.'),
    );
}

function typyKrzewow() 
{
    return array(
        'Dereń' =>  array('deren.png', 'deren', '<b>Dereń</b><br />Duży krzew osiągający 3 m wys. Młode, czerwone pędy są efektowne zimą. Liście żywozielone. Białożółte kwiaty zebrane są w płaskie kwiatostany.'),
        'Berberys' =>  array('berberys.png','berberys', '<b>Berberys</b><br />Ciernisty krzew. Dość szybko rośnie, dorastając do 2 m wysokości. W maju pojawiają się drobne, żółte kwiaty. Lubi stanowiska słoneczne. Ma małe wymagania glebowe.'),
        'Lilak' =>  array('lilak.png','lilak','<b>Lilak</b><br />Duży, wyprostowany krzew z licznymi odrostami korzeniowymi, tworzący zarośla. Dorasta do 4 m wys. Liście sercowate, żywozielone. Kwiaty zebrane w luźne wiechy.'),
        'Forsycja' =>  array('forsycja.png','forsycja', '<b>Forsycja</b><br />Wymagania uprawowe niewielkie, dobra mrozoodporność. Atrakcyjna odmiana przeznaczona do tworzenia zestawień kolorystycznych z krzewami o liściach czerwonych lub ciemnozielonych.'),
        'Tawuła' =>  array('tawula.png','tawula',  '<b>Tawuła</b><br />Krzew wytrzymały na mrozy i dość wytrzymały na suszę. Wymaga częstego przycinania, bo szeroko rozrasta się i obficie kwitnie. Doskonale nadaje się do przestrzeni miejskiej.'), 
        'Irga' =>  array('irga.png','irga', '<b>Irga</b><br />Niski rozłożysty krzew, z prawie poziomymi pędami i charakterystycznymi, podobnymi do ości ryby, rozgałęzieniami. Osiąga 1 m wys. i 2 m szerokości.'), 
        'Pęcherznica' =>  array('pecherzyca.png','pecherznica', '<b>Pęcherznica</b><br />Krzew o szybkim wzroście. Niewymagająca roślina. Rośnie na stanowiskach od słonecznego do  cienistego, równie dobrze na suchych, jak i na wilgotnych glebach.'), 
        
        //'Tawuła' =>  '',
        //'Irga' =>  '',
        //'Lilak' =>  ''
    );
}

function typyObiektow() {
    return array_merge(typyDrzew(), typyKrzewow());
}
add_editor_style( 'editor-style.css' );

     
add_filter('manage_zgloszenia_posts_columns', function($defaults) {
    $defaults = array(
        'cb'    =>  '<input type="checkbox" />',
        'id'=>'ID',
        'data'=>'Data zgłoszenia',
        'street'=>'Ulica',
        'name'=>'Imię i nazwisko',
        'status'=>'Status',
    );
    return $defaults;
});

add_action( 'manage_zgloszenia_posts_custom_column', function($column_name, $post_id) {
    if($column_name == 'id') 
    {
        echo '<a href="post.php?action=edit&post='.$post_id.'">'.$post_id.'</a>';
    }
    else if($column_name == 'name')
    {
        $meta = get_post_meta($post_id, '_imie');    
        echo $meta[0];    
    }
    else if($column_name == 'street')
    {
        $meta = get_post_meta($post_id, '_ulica');    
        echo $meta[0];    
    }
    else if($column_name == 'status')
    {
        
        $meta = get_post_meta($post_id, '_status');    
        $meta[0] = $meta[0] ? $meta[0] : 'new';
        echo typyZgloszen($meta[0]);
    }
    else 
    {
        $post = get_post($post_id);    
        echo $post->post_date;
    }
}, 10, 2 );

add_action('admin_head', function() {
    global $current_user;
    
    foreach($current_user->roles as $role) 
    {
        if(strstr($role, 'aamrole_'))
        {
            echo '<style>.toplevel_page_wpcf7 { display: none; }</style>';        
        }
    }
    
});

add_filter('posts_join', 'zgloszenia_search_join' );
function zgloszenia_search_join ($join){
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni"
    
    
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='zgloszenia' ) {    
        $join .='LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

add_filter( 'posts_where', 'zgloszenia_search_where' );
function zgloszenia_search_where( $where ){
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni"
    
    
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='zgloszenia' && $_GET['s'] != '') {
        $where = preg_replace(
       "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
       "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    
    
    global $current_user;
    
    
    foreach($current_user->roles as $role) 
    {
        
        if(strstr($role, 'aamrole_55639aae15af2')) // moderator 1 - srodowisko
        {
            $where .= " AND (band_postmeta.meta_key = '_status' AND band_postmeta.meta_value IN('new','confirmed') )";    
        }        
        else if(strstr($role, 'aamrole_5563a0781c80a')) // moderator 2 - geodezja
        {
            //echo '<style>.toplevel_page_wpcf7 { display: none; }</style>';        
            $where .= " AND (band_postmeta.meta_key = '_status' AND band_postmeta.meta_value IN('accepted.step1') )";
        }
        else if(strstr($role, 'aamrole_55790a958db2b')) // moderator 3 - architektura
        {
            $where .= " AND (band_postmeta.meta_key = '_status' AND band_postmeta.meta_value IN('accepted.step3') )";    
        }
        else if(strstr($role, 'aamrole_55790aa5a8bdd')) // moderator 4 - skarb
        {
            $where .= " AND (band_postmeta.meta_key = '_status' AND band_postmeta.meta_value IN('accepted.step4', 'accepted.step4-mpzp') )";    
        }
        else if(strstr($role, 'aamrole_55790aca41a92')) // moderator 5 - srodowisko
        {
            $where .= " AND (band_postmeta.meta_key = '_status' AND band_postmeta.meta_value IN('accepted.step5') )";    
        }
    } 
    
    $where .= ' GROUP BY '.$wpdb->posts.'.ID ';   
    
    
    //echo $where;die();
    return $where;
}

add_filter( 'views_edit-zgloszenia', 'custom_list_link_wpse_79975' );

function custom_list_link_wpse_79975( $views ) 
{
    $views['dashboard'] = '<a href="?downloadCSV='.md5('5fds45c34c433ss').'">Pobierz CSV</a>';
    // $views['another-view'] = '<a href="#">Contact</a>';
    return $views;
}

/*
add_action( 'restrict_manage_posts', 'wpse45436_admin_posts_filter_restrict_manage_posts' );

function wpse45436_admin_posts_filter_restrict_manage_posts(){
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if($type != 'zgloszenia') return;
}
*/