<?php
/**
* Template Name: Zasadź
*/
get_header();
?>
        <iframe src="about:blank" style="display: none" id="hiddenFrame" name="hiddenFrame" ></iframe>
        <form action="?legacyUpload" target="hiddenFrame" enctype="multipart/form-data" id="legacyForm"  method="post">
        <div id="map-wrapper" class="step-1">
            <div id="map"></div>
            
            <div class="layer">
                <div class="step step-1">
                    <div class="pager">
                        <span class="active">1</span>
                        <span>2</span>
                        <span>3</span>
                    </div>
                    <h3>Wybierz lokalizację</h3>
                    <p>Wpisz swój adres w formularzu poniżej, lub znajdź go na mapie.</p>
                    
                    <input type="text" name="ulica" class="medium" placeholder="Ulica" />
                    <input type="text" name="nr" class="short" placeholder="nr"/>
                    <input type="hidden" name="nrDzialki" />
                    <input type="hidden" name="lat" class="lat" value="" />
                    <input type="hidden" name="lng" class="lng" value="" />
                    <img src="<?= get_template_directory_uri(); ?>/images/icon.searchn.png" alt=""  data-js="geolocate"/><br />
                    
                    <a href="#" data-js="geolocate" class="hide-for-small"><img src="<?= get_template_directory_uri(); ?>/images/icon.pokazn.png" alt="" /></a>
                    <a href="#" data-js="maptype" class="satellite toggle ">Widok satelitarny</a>
                    
                    <textarea name="extraInfo" id="extraInfo" placeholder="Szczegółowe uwagi dotyczące lokalizacji" /></textarea>                    
                    
                    <p class="pozaMiastem hidden"><b>Drzewa możesz wybrać jedynie w Gdańsku.</b></p>
                    
                    <p class="wlasnosc"></p>
                    
                    <p class="working hidden"><b>Proszę czekać</b>. Trwa ustalanie właściciela wskazanej działki.</p>
                                        
                    
                    <div class="file">
                        <p class="hide-for-medium-up">
                            Właścicielem wskazanej działki nie jest Gmina. W procesie ewaluacji możliwości zasadzenia skontaktujemy się w celu uzyskania stosownych zezwoleń.                            
                        </p>
                        <div class="hide-for-small">
                            <p>
                                Właścicielem wskazanej działki nie jest Gmina. Prosimy o załączenie wypełnionego <a href="/wp-content/uploads/2015/05/formularz-zobowiązania-BAND.doc" target="_blank">formularza wyrażenia zgody na nasadzenie</a>.<br />
                                <br />
                                <!--
                                    Załączenie pliku nie jest obowiązkowe. Jeśli nie masz jeszcze zgody właściciela na nasadzenie, to nie załączaj pliku, tylko kliknij 'dalej' i dokończ proces nasadzenia, a my skontaktujemy się z Tobą.
                                -->
                            </p>
                            <input type="file" name="zgoda" />
                        </div>
                    </div>
                    
                    
                    <a href="#" class="button continue hidden">Dalej</a>
                </div>
                <div class="step step-2">
                    <div class="pager">
                        <span class="active">1</span>
                        <span class="active">2</span>
                        <span>3</span>
                    </div>
                    <h3>Wybierz drzewa i&nbsp;krzewy</h3>
                    
                    <p>Twoje propozycje zostaną przez nas uwzględnione i&nbsp;sprawdzimy możliwość ich nasadzenia w danym miejscu, natomiast ostatecznie może zostać wybrany inny gatunek, o czym Cię poinformujemy.</p>
                    
                    
                    
                    <div class="row collapse">
                        <div class="columns small-7 medium-7">
                        <big>Drzewa</big>
                        <?php
                            foreach(typyDrzew() as $drzewo => $opis)
                            {
                                ?>
                                <div class="tree">
                                    <input type="checkbox" name="drzewo[]" value="<?= $opis[1]; ?>" /> <span></span> <?= $drzewo; ?> <img src="<?= get_template_directory_uri(); ?>/images/icon.help.png" alt="" class="help" /> 
                                    <div class="desc"><img src="<?= get_template_directory_uri(); ?>/images/drzewa/<?= $opis[0]; ?>" alt="<?= $drzewo; ?>" /><p><?= $opis[2]; ?></p></div>
                                </div>
                                <?php    
                            }
                        ?>
                        </div>
                        <div class="columns small-5 medium-5">
                        <big>Krzewy</big>
                        <?php
                            foreach(typyKrzewow() as $drzewo => $opis)
                            {
                                ?>
                                <div class="tree">
                                    <input type="checkbox" name="drzewo[]" value="<?= $opis[1]; ?>" /> <span></span> <?= $drzewo; ?> <img src="<?= get_template_directory_uri(); ?>/images/icon.help.png" alt="" class="help" /> 
                                    <div class="desc"><img src="<?= get_template_directory_uri(); ?>/images/drzewa/<?= $opis[0]; ?>" alt="<?= $drzewo; ?>" /><p><?= $opis[2]; ?></p></div>
                                </div>
                                <?php    
                            }
                        ?>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="columns small-12 text-right">
                            Liczba drzew do nasadzenia
                            <select name="liczba">
                                <?php
                                    for($p = 1 ; $p <= 10 ; $p++)
                                    {
                                        echo '<option value="'.$p.'">'.$p.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                        
                    <a href="#" class="button back">Wstecz</a>
                    <a href="#" class="button continue">Dalej</a>
                </div>
                
                <div class="step step-3">
                    <div class="pager">
                        <span class="active">1</span>
                        <span class="active">2</span>
                        <span class="active">3</span>
                    </div>
                    <h3>Dane kontaktowe</h3>                
                    
                    <p>Podaj swoje dane kontaktowe. Urząd po weryfikacji zgłoszenia skontaktuje się z Tobą aby powiadomić Cię o decyzji jak i umówić termin nasadzenia.</p>
                    
                    <input type="text" name="imie" placeholder="Imię i nazwisko *" />
                    <input type="text" name="email" placeholder="Adres e-mail *" />
                    <input type="text" name="telefon" placeholder="Numer telefonu" />
                    
                    <br />
                    <br />
                    
                    <p>
                        <input type="checkbox" name="regulamin" /> <span></span> Akceptuję <a href="/faq/" target="_blank">zasady korzystania</a> z serwisu *<br />
                    </p>
                    <p>
                        <input type="checkbox" name="zgoda" /> <span></span> Wyrażam zgodę na przetwarzanie danych osobowych *<br />
                    </p>
                    
                    <p>
                        * pole wymagane
                    </p>
                
                    <a href="#" class="button back">Wstecz</a>
                    <a href="#" class="button continue finish">Wyślij zgłoszenie</a>
                </div>
                
            </div>
        </div>
        </form>
        
        <script src="<?= get_template_directory_uri(); ?>/js/ulice.js"></script>
        <script src="<?= get_template_directory_uri(); ?>/js/infobox.js"></script>
        <script>
        
        var marker = null;
        var GEvent = google.maps.event;
        var infoWindow = new google.maps.InfoWindow();
        var nrDzialki = null;
        
        $( "[name=ulica]" ).autocomplete({
            source: _ulice,
            minLength: 3
        });        
        

        if(jQuery('#map').length > 0)
        {
            var mapOptions = {
                center: { lat: 54.3704551, lng: 18.6173698},                                          
                //scrollwheel: false,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE]    
                },
                zoom: 14,
            };
            var map = new google.maps.Map(document.getElementById('map'), mapOptions);                     
            var geocoder = new google.maps.Geocoder();
            
            point = new google.maps.LatLng(54.3704551, 18.6173698);              
            marker = new google.maps.Marker({
                position: point,
                draggable: true,
                icon: new google.maps.MarkerImage('<?= get_template_directory_uri(); ?>/images/icon.pin.png')
            }); 
            
            
            google.maps.event.addListener(marker, "dragend", function(event) {  
                getGeocodeInfo();
            });      
            
            google.maps.event.addListener(map, 'click', function(event) {
                if(step != 1) return;
                if(window.isClosing)
                {
                    window.isClosing = false;
                    return;
                }
                event.stop();
                //placeMarker(event.latLng);
                marker.setPosition(event.latLng);
                getGeocodeInfo();
            });
            marker.setMap(map);                                
            addSavedMarkers();
        }   
        
        
        function getGeocodeInfo()
        {
            
            
            $('.step-1 .continue').css('display', 'none');                   
            $('.step-1 .file').css('display', 'none');                   
            $('.step-1 .wlasnosc').html('');
            $('.step-1 .working').css('display', 'block')
            
            
            
                        
            geocoder.geocode({latLng: marker.getPosition()}, function (results, status) {            
                $('.step-1 .pozaMiastem').css('display', 'block');
                for(var i = 0 ; i < results.length ; i++)
                {                    
                    if(results[i].types[0] == 'street_address' || results[i].types[0] == 'route')
                    {
                                            
                        $('.layer .lat').val(results[i].geometry.location.lat());
                        $('.layer .lng').val(results[i].geometry.location.lng());                                                
                        for(var j = 0 ; j < results[i].address_components.length ; j++)
                        {
                            if(results[i].address_components[j].types[0] == 'street_number')
                            {
                                $('input[name=nr]').val(results[i].address_components[j].long_name);
                            }
                            if(results[i].address_components[j].types[0] == 'route')
                            {
                                $('input[name=ulica]').val(results[i].address_components[j].long_name);
                            }
                            //if(results[i].address_components[j].types[0] == 'locality')
                            //{ 
                            
                                if(results[i].address_components[j].long_name.indexOf('Gdańsk') > -1)
                                {
                                    nrDzialki = null;
                                    $.ajax({
                                        url: '?',
                                        method: 'GET',
                                        data: {
                                            ajaxLat: results[i].geometry.location.lat(),
                                            ajaxLng: results[i].geometry.location.lng()
                                        },
                                        dataType: 'json',
                                    }).success(function(data) 
                                    {
                                        $('.step-1 .working').css('display', 'none');
                                        if(typeof data.results == 'object')
                                        {
                                            for(var i = 0 ; i < data.results.length ; i++) {
                                                if(data.results[i].layerId == 3) 
                                                {
                                                    //alert(data.results[i].attributes.gr_rej_max);
                                                    //$('.step-1 .wlasnosc').html('Typ działki: gr_rej_max = ' + data.results[i].attributes.gr_rej_max);
                                                    
                                                    nrDzialki = data.results[i].attributes.numer_dz;
                                                    $('[name=nrDzialki]').val(nrDzialki);
                                                    if(parseInt(data.results[i].attributes.gr_rej_max) == 4)
                                                    {
                                                        
                                                    }
                                                    else
                                                    {
                                                        $('.step-1 .file').css('display', 'block');
                                                    }
                                                    
                                                    $('.step-1 .continue').css('display', 'inline-block');    
                                                    
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $('.step-1 .wlasnosc').html('Przepraszamy, ale nie jesteśmy w stanie określić właściciela wskazanej działki.')
                                        }
                                    });
                                    
                                    $('.step-1 .pozaMiastem').css('display', 'none');
                                    //$('.step-1 .continue').css('display', 'block');                                                                                        

                                }  
                            //}
                            
                        }
                        break;
                    }
                }
            });            
        }
        
        getGeocodeInfo();
        
        jQuery('[data-js=maptype]').click(function(e) {
            e.preventDefault();
            if(map.getMapTypeId() == 'roadmap') {
                map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
                $(this).html('Widok mapy');
            }
            else {
                map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
                $(this).html('Widok satelitarny');
            }
        });
        
        jQuery('[data-js=geolocate]').click(function(e)
        {
            e.preventDefault();
            var address = 'Polska, Gdańsk, ' + jQuery('[name=ulica]').val() + ' ' + jQuery('[name=nr]').val()
            if(address)
            {                        
                geocoder.geocode({ 'address': address}, function (results, status) { 
                    if(status == google.maps.GeocoderStatus.OK)
                    {
                        map.setZoom(16);
                        map.setCenter(results[0].geometry.location);                        
                        //addMarker(results[0].geometry.location);                            
                        marker.setPosition(results[0].geometry.location);                            
                    }
                    else
                    {

                    }
                });
            }
        })
        
        
        function addMarker(point, lng)
        {   
            
            if(typeof point != 'object') point = new google.maps.LatLng(point, lng);

            /*
            GEvent.addListener(pMarker, "click", function() { 
                    infoWindow.close();
                    infoWindow.setContent(':)');
                    infoWindow.setContent(infoWindow.getContent());
                    infoWindow.open(map, pMarker);  
                                                      
            });  
            */
                  
            map.setCenter(point);
        }
        
        var step = 1;
        
        function shareToFB(url)
        {
            window.open(url, '', 'width=640,height=480');
            return false;
        }
        
        $('#map-wrapper .back').click(function(e)
        {
            step--;
            
            if(step == 1) marker.setOptions({draggable: true});
            else marker.setOptions({draggable: false});            
            
            $('#map-wrapper')[0].className = 'step-'+step;
            $('#map-wrapper .step').css('display', 'none');
            $('#map-wrapper .step-' + step).css('display', 'block');            
        });
        $('#map-wrapper .continue').click(function(e)
        {
            e.preventDefault();
            var error = false;
            $('#map-wrapper .error').removeClass('error');
            if(step == 1)
            {
                if($('input[type=file]:visible').length > 0)
                {
                    if($('input[type=file]:visible').val() == '')
                    {
                        error = true;
                        sweetAlert({
                            title: "Błąd", 
                            text: "<span class='h'>Prosimy o załączenie kopii formularza wyrażenia zgody na nasadzenie.</span>", 
                            type: "error",
                            html: true
                        });                        
                        return;
                    }
                }
            }
            if(step > 1)
            {                          
                var text = $('#map-wrapper .step-' + step + ' input[type=text]');
                for(var i = 0; i < text.length ; i++)
                {
                    if(text.eq(i).val().length == 0 && text.eq(i).attr('name') != 'telefon') {
                        text.eq(i).addClass('error');
                        error = true;
                    }
                }
                
                var cb = $('#map-wrapper .step-' + step + ' input[type=radio]');
                for(var i = 0; i < cb.length ; i++)
                {
                    if($('input[type=radio][name='+cb[i].name.replace('[','\\[').replace(']','\\]')+']:checked').length == 0) {
                        cb.eq(i).parent().addClass('error');
                        error = true;
                    }
                }
                cb = $('#map-wrapper .step-' + step + ' input[type=checkbox]');
                for(var i = 0; i < cb.length ; i++)
                {
                    if($('input[type=checkbox][name='+cb[i].name.replace('[','\\[').replace(']','\\]')+']:checked').length == 0) {
                        cb.eq(i).parent().addClass('error');
                        error = true;
                    }
                }                
                
                if(error)
                {
                    sweetAlert({
                        title: "Błąd", 
                        text: "<span class='h'>Prosimy wypełnić wszystkie pola formularza!</span>", 
                        type: "error",
                        html: true
                    });
                    return;
                }
            }
            step++;
            if(step == 1) marker.setOptions({draggable: true});
            else marker.setOptions({draggable: false});
            
            $('#map-wrapper')[0].className = 'step-'+step;
            if(step > 1 && $(window).width() <= 640) {
                $('body, html').animate({scrollTop: 0});
            }
            
            
            
            if(step == 4)
            {
                /*
                sweetAlert({
                    title: "Dziękujemy!", 
                    text: "<span class='h'>Twoje zgłoszenie zostało wysłane!</span><br />W ciągu najbliższych 10 dni roboczych skontaktujemy się z Tobą.", 
                    type: "success",
                    html: true,
                    confirmButtonText: 'Dalej'
                }, function(isConfirm) 
                {  
                    document.location = './'; 
                });
                */
                
                var legacyUpload = false;
                legacyUpload = true;
                /*
                try {
                    var xhr = new XMLHttpRequest();
                    var fo = new FormData;
                    fo.append('file', $('input[type=file]')[0].files[0]);
                    xhr.open('post', '/?ajaxUpload');
                    xhr.send(fo);
                }
                catch(ex) {
                    
                }
                */
                
                sweetAlert({
                    title: "Dziękujemy!", 
                    text: "<span class='h'>Twoje zgłoszenie zostało wysłane!</span><br />Na podany adres e-mail wysłaliśmy link weryfikacyjny.<br /><div style='text-align: center; padding-top: 5px'><button class='confirm'>Dalej</button><hr />Pochwal się znajomym na Facebooku <a href='https://www.facebook.com/sharer/sharer.php?s=100&p%5Burl%5D=http%3A%2F%2Fbandgdansk.com' onclick='return shareToFB(this.href)' target='_blank'><img src='/wp-content/themes/band/images/fbshare.jpg' alt='' /></a></div>", 
                    type: "success",
                    html: true,
                    showConfirmButton: false
                }, function(isConfirm) 
                {  
                    document.location = './'; 
                });                   
                
                $('#map-wrapper .layer').hide();
                
                var _fields = {};
                _fields['nrDzialki'] = nrDzialki;
                
                $('#map-wrapper .layer input, #map-wrapper .layer select, #map-wrapper .layer textarea').each(function(it, el) {
                    var name;
                    if(el.type == 'checkbox' || el.type == 'radio')
                    {
                        if(el.name.indexOf('[') > -1)
                        {
                            name = el.name.replace('[','').replace(']', '');
                            if(typeof _fields[name] == 'undefined') _fields[name] = [];
                            if(el.checked) _fields[name].push($(el).val())
                        }
                        else
                        {
                            if(el.checked) _fields[el.name] = $(el).val();
                        }
                    }
                    else
                    {
                        _fields[el.name] = $(el).val();
                    }
                    

                });
                
                if(legacyUpload)
                {
                    document.getElementById('legacyForm').submit();    
                }
                else
                {
                    $.ajax({
                        url: '?ajaxSubmit=true',
                        method: 'POST',
                        data: _fields
                    });
                } 
                
                
                var nm  = new google.maps.Marker({
                    position: marker.getPosition(),
                    title : 'New point',
                    icon: new google.maps.MarkerImage('<?= get_template_directory_uri(); ?>/images/icon.pin.blue.png')
                }); 
                GEvent.addListener(nm, "click", function() { 
                    if(ib) {
                        ib.remove();
                        ib = null;
                    }
                    ib = new InfoBox({latlng: this.getPosition(), map: map, type: '', html: '<img src="<?= get_template_directory_uri(); ?>/images/drzewa/'+$('.step-2 input[type=checkbox]:checked').eq(0).val()+'.jpg" alt="" /><p><b>'+$('.step-2 input[type=checkbox]:checked').eq(0).parent().text().split("\n")[1].trim()+'</b><br />'+($('[name=ulica]').val() + ' ' + $('[name=nr]').val())+'<br /><b style="color: #00adef">w trakcie rozpatrywania</b></p>'});
                                                        
                });  
                nm .setMap(map);                
                
                step = 1;
                $('#map-wrapper')[0].className = 'step-'+step;               
                
                
            }
            else
            {
                $('#map-wrapper .step').css('display', 'none');
                $('#map-wrapper .step-' + step).css('display', 'block');
            }
        })
        
        
        var ib;
        function addSavedMarkers() {
            var point, marker;
            <?php
                $query = new WP_Query(array('post_type'=>'zgloszenia', 'posts_per_page'=>9999));
                $drzewa = typyObiektow();
                foreach($query->posts as $post)
                {
                    $meta = get_post_meta($post->ID);
                    if($meta['_status'][0] == 'planted' || $meta['_status'][0] == 'accepted.done') $icon = get_template_directory_uri().'/images/icon.pin.green.png';
                    else if(strstr($meta['_status'][0], 'rejected')) $icon = get_template_directory_uri().'/images/icon.pin.red.png';
                    else $icon = get_template_directory_uri().'/images/icon.pin.blue.png';
                    
                    ?>
                        point = new google.maps.LatLng(<?= $meta['_lat'][0].', '.$meta['_lng'][0]; ?>);
                        marker = new google.maps.Marker({
                            position: point,
                            title : 'Point #<?= $post->ID; ?>',
                            icon: new google.maps.MarkerImage('<?= $icon; ?>')
                        }); 
                        GEvent.addListener(marker, "click", function() { 
                            if(ib) {
                                ib.remove();
                                ib = null;
                            }
                            <?php
                                $icon = 'drzewa/icon.drzewo.tmp.png';
                                $wybrane = json_decode($meta['_drzewa'][0]);
                                
                                foreach($drzewa as $key => $drzewo)
                                {
                                    if($drzewo[1] == $wybrane[0])
                                    {
                                        $icon = 'drzewa/'.$drzewo[0];
                                        $wybraneDrzewo = $key;
                                        break;
                                    }    
                                }
                                if(strstr($meta['_status'][0], 'rejected')) {
                                    $icon = 'icon.drzewo.nope.png';
                                    // Nie można nasadzić żadnego drzewa ze względu na linię energetyczną.
                                    ?>
                                    ib = new InfoBox({latlng: this.getPosition(), map: map, type: '<?= $meta['_status'][0]; ?>', html: '<img src="<?= get_template_directory_uri(); ?>/images/<?= $icon; ?>" alt="" /><p><b><?= str_replace("'", '&#8217;', $meta['_reason'][0]); ?></p>'});
                                    <?php
                                }
                                else {
                                    $comment = '';
                                    if(strstr($meta['_status'][0], 'new') || !$meta['_status'][0]) $comment = '<br /><b style="color: #00adef">w trakcie rozpatrywania</b>';
                                    ?>
                                    ib = new InfoBox({latlng: this.getPosition(), map: map, type: '<?= $meta['_status'][0]; ?>', html: '<img src="<?= get_template_directory_uri(); ?>/images/<?= $icon; ?>" alt="" /><p><b><?= $wybraneDrzewo; ?></b><br /><?= str_replace("'", '&#8217;', $meta['_ulica'][0] . $comment); ?></p>'});
                                    <?php
                                }
                            ?>
                            
                        });  
                        marker.setMap(map);                       
                    <?
                }
            ?>
        }

    </script>
    
           
<?php get_footer(); ?>        