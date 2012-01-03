<?php
// vars : $titreBloc, $titreLien, $message

       
            echo _tag('h4.title', $titreBloc);
            echo _open('ul.elements');
                echo _open('li.element');
                    echo _tag('p',$message);
                    echo _link('main/contact')->text($titreLien);
                echo _close('li');
            echo _close('ul');
