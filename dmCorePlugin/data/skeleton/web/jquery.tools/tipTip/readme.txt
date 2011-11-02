http://code.drewwilson.com/entry/tiptip-jquery-plugin

How It Works
TipTip uses the title attribute just like the native browser tooltip does. However, the title will be copied and then removed from the element when using TipTip so that the browser tooltip will not show up.
TipTip only generates one set of popup elements total, rather then generating one set of popup elements for each element with TipTip applied to it. This helps speed things up and reduces processing time. The elements generated are all div elements and are appended to the end of the body element. The structure looks like this:
<div id="tiptip_holder">
<div id="tiptip_content">
<div id="tiptip_arrow">
<div id="tiptip_arrow_inner"></div>
</div>
</div>
</div>
There are specific CSS class names added to the "tiptip_holder" div element when it appears depending on the orientation it appears in. Here is a list of the class names along with it's orientation:
tip_top - When the tooltip appears above the element.
tip_bottom - When the tooltip appears below the element.
tip_left - When the tooltip appears to the left the element.
tip_right - When the tooltip appears to the right the element.
tip_left_top - When the tooltip appears to the left and above the element.
tip_left_bottom - When the tooltip appears to the left and below the element.
tip_right_top - When the tooltip appears to the right and above the element.
tip_right_bottom - When the tooltip appears to the right and below the element.
TipTip has been tested (and works) in: IE7 & IE8, Firefox, Safari, Opera, and Chrome.
How To Use It
Obviously you need to make sure you have the latest jQuery library already loaded in your page. After that it's really simple, just add the following code to your page:
$(function(){
$(".someClass").tipTip();
});
Below is an example of using TipTip with some options:
$(function(){
$(".someClass").tipTip({maxWidth: "auto", edgeOffset: 10});
});
Below is an example of what your HTML would look like:
<p>
Cras sed ante. Phasellus in massa. <a href="" class="someClass" title="This will show up in the TipTip popup.">Curabitur dolor eros</a>, gravida et, hendrerit ac, cursus non, massa.
<span id="foo">
<img src="image.jpg" class="someClass" title="A picture of the World" />
</span>
</p>





Options
activation: string ("hover" by default) - jQuery method TipTip is activated with. Can be set to: "hover", "focus" or "click".
keepAlive: true of false (false by default) - When set to true the TipTip will only fadeout when you hover over the actual TipTip and then hover off of it.
maxWidth: string ("200px" by default) - CSS max-width property for the TipTip element. This is a string so you can apply a percentage rule or 'auto'.
edgeOffset: number (3 by default) - Distances the TipTip popup from the element with TipTip applied to it by the number of pixels specified.
defaultPosition: string ("bottom" by default) - Default orientation TipTip should show up as. You can set it to: "top", "bottom", "left" or "right".
delay: number (400 by default) - Number of milliseconds to delay before showing the TipTip popup after you mouseover an element with TipTip applied to it.
fadeIn: number (200 by default) - Speed at which the TipTip popup will fade into view.
fadeOut: number (200 by default) - Speed at which the TipTip popup will fade out of view.
attribute: string ("title" by default) - HTML attribute that TipTip should pull it's content from.
content: string (false by default) - HTML or String to use as the content for TipTip. Will overwrite content from any HTML attribute.
enter: callback function - Custom function that is run each time you mouseover an element with TipTip applied to it.
exit: callback function - Custom function that is run each time you mouseout of an element with TipTip applied to it.