<?php // Vars: $sidAddedPagesPager
$i = 1;
$i_max = count($sidAddedPagesPager);
$class = '';
echo $sidAddedPagesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sidAddedPagesPager as $sidAddedPages)
{
  // class first ou last pour listing
            if ($i == 1) {
                $class = 'first';
                if ($i == $i_max)
                    $class = 'first last';
            }
            elseif ($i == $i_max)
                $class = 'last';
            else
                $class = '';  
  echo _open('li.element '.$class);

    echo _link($sidAddedPages);

  echo _close('li');
}

echo _close('ul');

echo $sidAddedPagesPager->renderNavigationBottom();