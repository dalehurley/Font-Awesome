/************************************************************************
*************************************************************************
@Name :       	QapTcha - jQuery Plugin
@Revison :    	1.0
@Date : 		26/01/2011
@Author:     	 Surrel Mickael (www.myjqueryplugins.com - www.msconcept.fr) 
@License :		 Open Source - MIT License : http://www.opensource.org/licenses/mit-license.php
 
**************************************************************************
*************************************************************************/
jQuery.QapTcha = {
	build : function(options)
	{
        var defaults = {
			txtLock : 'Locked : form can\'t be submited',
			txtUnlock : 'Unlocked : form can be submited',
            txtLabel : 'Slide please'
        };   
		
		if(this.length>0)
		return jQuery(this).each(function(i) {
			/** Vars **/
			var 
				opts = $.extend(defaults, options),      
				$this = $(this),
				form = $('form').has($this),
                                
				Clr = jQuery('<div>',{'class':'clr'}),
				bgSlider = jQuery('<div>',{id:'bgSlider'}),
                labelSlider = jQuery('<div>',{id:'labelSlider',text:opts.txtLabel}),
                labelHelp = jQuery('<div>',{id:'labelHelp',title:opts.txtHelp}),
                Slider = jQuery('<div>',{id:'Slider'}),
				Icons = jQuery('<div>',{id:'Icons'}),
				//TxtStatus = jQuery('<div>',{id:'TxtStatus',text:opts.txtLock}),
				inputQapTcha = jQuery('<input>',{name:'iQapTcha',value:generatePass(),type:'hidden'});
			
			/** Disabled submit button **/
			$('form.contactForm').find('input.submit').attr('disabled','disabled');
                        $('form.contactForm').find('input.submit').hide();
 
			
			/** Construct DOM **/
			labelSlider.appendTo($this);
            labelHelp.appendTo(labelSlider);
            bgSlider.insertAfter(labelSlider);
			Icons.insertAfter(bgSlider);
			Clr.insertAfter(Icons);
			//TxtStatus.insertAfter(Clr);
			inputQapTcha.appendTo($this);
			Slider.appendTo(bgSlider);
			$this.show();
			
			Slider.draggable({ 
				containment: bgSlider,
				axis:'x',
				stop: function(event,ui){
					if(ui.position.left > 150)
					{
						// set the SESSION iQaptcha in PHP file
						$.post("/qaptchaAjax",{
							action : 'qaptcha'
						},
						function(data) {
							if(!data.error)
							{
								Slider.draggable('disable').css('cursor','default');
								inputQapTcha.val("");
//TxtStatus.css({color:'#307F1F'}).text(opts.txtUnlock);
								Icons.css('background-position', '-16px 0');
								//form.find('input[type=\'submit\']').removeAttr('disabled');

//alert('a tester');
// form.find("div#QapTcha").fadeOut();
//alert('a tester 2');

                                $('body').find("div#QapTcha").fadeOut(function(){

                                    //$('body').find("div.submit_wrap").fadeIn(); // apparition d'un bouton d'envoi
                                    $('form.contactForm').find('input.submit').attr('disabled',false);
                                    $('form.contactForm').find('input.submit').fadeIn();
  
                                  });
							}
						},'json');
					}
				}
			});
			
			function generatePass() {
		        var chars = 'azertyupqsdfghjkmwxcvbn23456789AZERTYUPQSDFGHJKMWXCVBN';
		        var pass = '';
		        for(i=0;i<10;i++){
		            var wpos = Math.round(Math.random()*chars.length);
		            pass += chars.substring(wpos,wpos+1);
		        }
		        return pass;
		    }
			
		});
	}
}; jQuery.fn.QapTcha = jQuery.QapTcha.build;