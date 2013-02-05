jQuery(function($){

	//Tabs
	$('.tabs_links').each(function(){

	  var el       = $(this);
	  var anchor   = window.location.hash; //ancre dans l'url
	  var linkCurr = null;
	  var ident    = el.data('identifier');
	  var tabs_ct  = $('.tabs_content[data-identifier="'+ident+'"]');

	  //---

	  if(anchor != '' && el.find('a[href="'+anchor+'"]').length > 0){
	    linkCurr = anchor;
	  }else{
	    linkCurr = el.find('a:first').attr('href');
	  }

	  //Gère l'affichage des tabs
	  if(el.data('hide') && el.data('hide') == 'all'){
	    $(tabs_ct).find('.tabs_tab').hide();
	    linkCurr = null; //Permet d'afficher la première tab
	  }else{
	    $(linkCurr).siblings().hide();
	    $('a[href='+linkCurr+']').addClass('active');
	  }

	  //---

	  el.find('a').click(function(){
	    var a      = $(this);
	    var link   = a.attr('href');
	    var effect = a.data('effect');

	    //---

	    if(link == linkCurr){
	      return false;
	    }else{

	      //Gestion des actives
	      $(this).parent('li').addClass('active').siblings().removeClass('active');
	      $(this).addClass('active').siblings().removeClass('active');

	      el.find('li a.active').removeClass('active');
	      $(this).addClass('active');

	      //Affichage
	      if(effect && effect == 'slide'){
	        $(link).slideToggle(400);
	      }
	      else{
	        $(link).show();
	      }
	      $(link).siblings().hide();
	      //---
	      linkCurr = link;
	      window.location.hash = linkCurr;
	      return false;
	    }
	  });
	});


});