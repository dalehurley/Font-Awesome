<?php // var $photos
if(count($photos) > 0){
	echo _tag('h4.title', __('Our team, your advice'));

	//on ajoute la classe imageGallery car c'est un container de plusieurs miniatures
	echo _open('div.imageGallery');

		$maxCount = count($photos);
		$count = 1;

		foreach ($photos as $photo){
			$count++;

			$html = _link('pageCabinet/equipe')
				->text(
					_media($photo->getImage())
					->set('.image')
					->alt($photo->getTitle())
					->width(myUser::gridGetWidth(myUser::getLessParam('thumbS_col')))
					->height(myUser::gridGetHeight(myUser::getLessParam('thumbS_bl')))
				)
				->anchor('equipe_'.$photo->id)
				->title($photo->getTitle());

			//ajout classes de placement
			if($count == 2) {
				$html->set('.first');
			}
			if($count > $maxCount) {
				$html->set('.last');
			}
			
			//$html.='<br/> test : '.$photo->getImage();
			
			echo $html;
		}
	echo _close('div');

	echo _open('div.navigation.navigationBottom');
		echo _open('ul.elements');
			echo _open('li.element');
				echo _link('pageCabinet/equipe')->text(__('The whole team'));
			echo _close('li');
		echo _close('ul');
	echo _close('div');
}