<?php

echo _open('div.clearfix.mt10');

echo _tag('div.fleft style="width:33%"',
  _tag('div.dm_box',
    _tag('p.title', 'Find pages').
    _tag('div.dm_box_inner',
      $form->render('.dm_form.list method=get')
    )
  ).
  _tag('div.help_box', markdown('Notes:

  - Find some page with the form above, then run the bot to browse them
  - The time displayed for each page is not the page creation time, but the time required to send the request and receive the response'
  ))
);

if(isset($bot))
{
  echo _tag('div.dm_box.dm_bot style="margin-left:34%"',
    _tag('p.title', 'Browsing '.$bot->getNbPages().' pages').
    _tag('div.dm_box_inner.dm_data.dm_bot_urls',
      _tag('div.control_bar.clearfix',
        _tag('button.start_stop', 'Start').
        _tag('p', _tag('span.nb_completed', 0).'/'.$bot->getNbPages().' pages').
        _tag('p', _tag('span.nb_errors', 0).' errors').
        _tag('p', _tag('span.time', '-').'ms/page')
      ).
      _tag('div.progress.ui-corner-all', _tag('div.progress_bar')._tag('div.progress_text', '0%')).
      _tag('div.urls_container', $bot->render())
    )
  );
}

echo _close('div');