// *** TO BE CUSTOMISED ***

var style_cookie_name = "glpi_skin" ;
var style_cookie_duration = 30 ;

// *** END OF CUSTOMISABLE SECTION ***
// You do not need to customise anything below this line

function switch_style ( css_title )
{
// You may use this script on your site free of charge provided
// you do not remove this notice or the URL below. Script from
// http://www.thesitewizard.com/javascripts/change-style-sheets.shtml
  var i, link_tag ;
  
  for (i = 0, link_tag = document.getElementsByTagName("link") ; i < link_tag.length ; i++ ) 
  {
    if ((link_tag[i].rel.indexOf( "stylesheet" ) != -1) && link_tag[i].title) {
      link_tag[i].disabled = true ;
      if (link_tag[i].title == css_title) {
        link_tag[i].disabled = false ;
      }
    }
    set_cookie( style_cookie_name, css_title, style_cookie_duration );
  }
}

function set_style_from_cookie()
{
  var css_title = get_cookie( style_cookie_name );
    
//  if (css_title == 'undefined' || css_title.length > 13) {
	if (css_title.substr(0,5) !== 'skin-' ) {
    //alert(css_title);
    switch_style( 'skin-default' );
  } 
    
  else {
	  if (css_title.length) {
	    switch_style( css_title );
	  } 
  }
}

function set_cookie ( cookie_name, cookie_value, lifespan_in_days, valid_domain )
{
    // http://www.thesitewizard.com/javascripts/cookies.shtml
    var domain_string = valid_domain ? ("; domain=" + valid_domain) : '' ;
    document.cookie = cookie_name +
                       "=" + encodeURIComponent( cookie_value ) +
                       "; max-age=" + 60 * 60 *
                       24 * lifespan_in_days +
                       "; path=/" + domain_string ;
}

function get_cookie ( cookie_name )
{
    // http://www.thesitewizard.com/javascripts/cookies.shtml
 var cookie_string = document.cookie; 
 var quebra_de_linha = cookie_string.split("="); 
 
 if (cookie_string.length != 0) {
	 var cookie_value = quebra_de_linha[2]; 
	 var cookie_value = unescape(cookie_value); 
	 return ( cookie_value ) ;
  }  
    return '' ;
}


function get_skin()
{
var setskin = document.getElementById("skin").value;
//alert(setskin);
switch_style(setskin);
return false;

}

/*

function ler_cookie()
{
 var o_cookie = document.cookie; 
 var quebra_de_linha = o_cookie.split("="); 
 var cookie_value = quebra_de_linha[2]; 
 var cookie_value  = unescape(cookie_value ); 
alert("Seu nome é: "+ cookie_value );
} 



function get_cookie ( cookie_name )
{
    // http://www.thesitewizard.com/javascripts/cookies.shtml
    var cookie_string = document.cookie ;
    if (cookie_string.length != 0) {
        var cookie_value = cookie_string.match ('(^|;)[\s]*' + cookie_name + '=([^;]*)' );
        return decodeURIComponent ( cookie_value[2] ) ;
    }
    return '' ;
}
*/