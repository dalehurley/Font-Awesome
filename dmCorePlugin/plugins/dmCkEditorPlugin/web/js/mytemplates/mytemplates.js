// Register a template definition set named "default".
CKEDITOR.addTemplates( 'default',
{
	// The name of the subfolder that contains the preview images of the templates.
	//imagesPath : CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),
 	imagesPath : '/dmCkEditorPlugin/js/mytemplates/images/',

	// Template definitions.
	templates :
		[
			{
				title: 'Image et titre',
				image: 'template1.gif',
				description: 'Une image principale avec un titre et un texte.',
				html:
					'<h3>' +
						'<img style="margin-right: 10px" height="100" width="100" align="left"/>' +
						'Type the title here'+
					'</h3>' +
					'<p>' +
						'Type the text here' +
					'</p>'
			},
			{
				title: 'Deux colonnes et du texte',
				image: 'template2.gif',
				description: 'Deux colonnes puis du texte.',
				html:
					'<table cellspacing="0" cellpadding="0" style="width:100%" border="0">' +
						'<tr>' +
							'<td style="width:50%">' +
								'<h3>Titre 1</h3>' +
							'</td>' +
							'<td></td>' +
							'<td style="width:50%">' +
								'<h3>Titre 2</h3>' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td>' +
								'Texte 1' +
							'</td>' +
							'<td></td>' +
							'<td>' +
								'Texte 2' +
							'</td>' +
						'</tr>' +
					'</table>' +
					'<p>' +
						'Encore du texte.' +
					'</p>'
			},
			{
				title: 'Texte et tableau',
				image: 'template3.gif',
				description: 'Un titre, du texte et un tableau.',
				html:
					'<div style="width: 80%">' +
						'<h3>' +
							'Le titre ici' +
						'</h3>' +
						'<table style="width:150px;float: right" cellspacing="0" cellpadding="0" border="1">' +
							'<caption style="border:solid 1px black">' +
								'<strong>Titre du tableau</strong>' +
							'</caption>' +
							'</tr>' +
							'<tr>' +
								'<td>&nbsp;</td>' +
								'<td>&nbsp;</td>' +
								'<td>&nbsp;</td>' +
							'</tr>' +
							'<tr>' +
								'<td>&nbsp;</td>' +
								'<td>&nbsp;</td>' +
								'<td>&nbsp;</td>' +
							'</tr>' +
							'<tr>' +
								'<td>&nbsp;</td>' +
								'<td>&nbsp;</td>' +
								'<td>&nbsp;</td>' +
							'</tr>' +
						'</table>' +
						'<p>' +
							'Le texte ici.' +
						'</p>' +
					'</div>'
			},
			{
				title: 'Image et texte',
				image: 'template4.gif',
				description: 'Une image et du texte autour.',
				html:
					'<h2>Le titre</h2>' +
					'<p><img src="/uploads/assets/favicon.ico" style="float:left" />Le texte.</p>'
			}			
		]






});
