#Mettre la liste des destinataires
Mettre "Destinataire" dans le nom du champ (l'ajout du champ est automatique, lié au modèle SidCabinetEquipe pour les objets actifs et ayant un email)
__Pour accèder à un destinataire précis (objet de SidCabinetEquipe)__ il faut appeler la page comme suit:
http://www.example.com/contact?dest=8 (où SidCabinetEquipe.id = 8)

#Do something when a contact request is saved.
Listen to the 'sid_contact_data.saved' event in apps/front/config/frontConfiguration.class.php

    require_once(dm::getDir().'/dmFrontPlugin/lib/config/dmFrontApplicationConfiguration.php');
     
    class frontConfiguration extends dmFrontApplicationConfiguration  
    {  
      public function configure()  
      {      
        $this->dispatcher->connect('sid_contact_data.saved', array('sidContactEnvent', 'listenToSidContactDataSavedEvent'));  
      }  
   
    }      

All the logic is in : sidContactPlugin/lib/tools/sidContactEnvent.class.php
