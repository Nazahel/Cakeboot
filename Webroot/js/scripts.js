jQuery(function($){

	//TABS
	$('.tabs_triggers').each(function(){
		
		var triggers   = $(this);
		var anchor     = window.location.hash; //ancre dans l'url
		var tabcurrent = null;
		var target     = triggers.attr('href');
		var tabs       = $('.tabs_content[data-target="'+target+'"]');
	    //---

	    if(anchor != '' && triggers.find('a[href="'+anchor+'"]').length > 0){
	        tabcurrent = anchor;
	    }else{
	        tabcurrent = triggers.find('a:first').attr('href');
	    }

	    console.log(tabcurrent);
	    
	      //Gère l'affichage des tabs
	      if(triggers.data('hide') && triggers.data('hide') == 'all'){
	        $(tabs).find('.tabs_tab').hide();
	        tabcurrent = null; //Permet d'afficher la première tab
	      }else{
	        $(tabcurrent).siblings().hide();
	      }

	      //---

	      triggers.find('a').click(function(){
	        var a      = $(this);
	        var link   = a.attr('href');
	        var effect = a.data('effect');

	        //---

	        if(link == tabcurrent){
	          return false;
	        }else{

	          //Gestion des actives
	          //$(this).parent('li').addClass('active').siblings().removeClass('active');
	          $(this).addClass('active').siblings().removeClass('active');

	          triggers.find('a.active').removeClass('active');
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
	          tabcurrent = link;
	          return false;
	        }
	      });
	});
	

});