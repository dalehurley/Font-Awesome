/*
# CKEDITOR Edit-In Place jQuery Plugin.
# Created By Dave Earley.
# www.Dave-Earley.com
*/

$.fn.ckeip = function (options, callback) {

    var original_html = $(this);
    var defaults = {
        e_height: '10',
        data: {}, e_url: '',
        e_hover_color: '#F0E68C',
        e_outline_color: '#90EE90',
        e_outline_width: '1',
        ckeditor_config: '',
        e_width: '50'
    };
    var settings = $.extend({}, defaults, options);

    return this.each(function () {
        var eip_html = $(this).html();
        var u_id = Math.floor(Math.random() * 99999999);

        var ckeipButtonDiv = "<div id='button_ckeip_" + u_id + "' style='display:none;' class='ckeipButton'><a href='#' title='Sauvegarder' id='save_ckeip_" + u_id + "'><img style='border:none;' src='/dmCkEditorPersoPlugin/ckeip/images/save.png'></a> <a href='#' title='Annuler' id='cancel_ckeip_" + u_id + "'><img style='border:none;' src='/dmCkEditorPersoPlugin/ckeip/images/cancel.png'></a><img style='border:none;' id='wait_ckeip_" + u_id + "' src='/dmCkEditorPersoPlugin/ckeip/images/wait.gif'></div>";

        $(this).before("<div id='ckeip_" + u_id + "'  style='display:none;'>"+ckeipButtonDiv+"<textarea id ='ckeip_e_" + u_id + "' cols='" + settings.e_width + "' rows='" + settings.e_height + "'  >" + eip_html + "</textarea></div>");

        $('#ckeip_e_' + u_id + '').ckeditor(settings.ckeditor_config);

        $(this).bind("click", function () {

            $(this).hide();
            $('#ckeip_' + u_id + '').show();

            $("#button_ckeip_" + u_id + "").show();

            $("#save_ckeip_" + u_id + "").show();
            $("#cancel_ckeip_" + u_id + "").show();
            $("#wait_ckeip_" + u_id + "").hide();

        });


        $(this).css({
            //backgroundColor: ''
            "outline-color": settings.e_outline_color ,
            "outline-style": "solid"
        });

        $(this).hover(function () {
            $(this).css({
               // backgroundColor: settings.e_hover_color,
            "outline-color": settings.e_hover_color ,
            "outline-style": "solid"
            });
        }, function () {
            $(this).css({
                //backgroundColor: ''
                "outline-color": settings.e_outline_color ,
                "outline-style": "solid"
            });
        });


        $("#cancel_ckeip_" + u_id + "").click(function () {
            $('#ckeip_' + u_id + '').hide();
            $(original_html).fadeIn();
            return false;
        });

// bouton save
        $("#save_ckeip_" + u_id + "").click(function () {
            var ckeip_html = $('#ckeip_e_' + u_id + '').val();

            $("#save_ckeip_" + u_id + "").hide();
            $("#cancel_ckeip_" + u_id + "").hide();
            $("#wait_ckeip_" + u_id + "").show();

            $.post(settings.e_url, {
                content: ckeip_html,
                data: settings.data
            }, function (response) {
                if (typeof callback == "function") callback(response);

                $(original_html).html(ckeip_html);
                $('#ckeip_' + u_id + '').hide();
                $(original_html).fadeIn();

            });
            return false;

        });

// valid du formulaire
        $("#form_ckeip_" + u_id + "").submit(function () {
            var ckeip_html = $('#ckeip_e_' + u_id + '').val();

            $("#save_ckeip_" + u_id + "").hide();
            $("#cancel_ckeip_" + u_id + "").hide();
            $("#wait_ckeip_" + u_id + "").show();

            $.post(settings.e_url, {
                content: ckeip_html,
                data: settings.data
            }, function (response) {
                if (typeof callback == "function") callback(response);

                $("#wait_ckeip_" + u_id + "").hide();

                $(original_html).html(ckeip_html);
                $('#ckeip_' + u_id + '').hide();
                $(original_html).fadeIn();

            });
            return false;

        });

    });
};



