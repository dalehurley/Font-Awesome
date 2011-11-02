dmContactMePlugin allows to display a typical contact form.
By default, contact requests sent with the form are stored in database.
The plugin packages a Diem front widget and an admin interface to manage contact requests.
Integrates recaptcha validation.
The plugin is fully extensible. Only works with [Diem 5.0](http://diem-project.org/) installed.

Documentation
-------------

See the online documentation : [Diem Contact plugin documentation](http://diem-project.org/plugins/dmcontactplugin)

Installation
------------

In config/ProjectConfiguration.class.php, add dmContactMePlugin to the list of enabled plugins:

class ProjectConfiguration extends dmProjectConfiguration
{
  public function setup()
  {
    parent::setup();

    $this->enablePlugins(array(
      // your enabled plugins
      'dmContactMePlugin'
    ));
In a console, from your project root dir, run:

php symfony doctrine:generate-migrations-diff
php symfony doctrine:migrate
php symfony dm:setup



Paramètres
----------

In app.yml add this lines.
Recaptcha : http://www.google.com/recaptcha
Qaptcha: http://www.myjqueryplugins.com/QapTcha

all:
  recaptcha:
    # get your recaptcha keys on http://recaptcha.net/api/getkey
    public_key:   6LfpJ8ISAAAAAHx_gUVtepnvcXq5EuXGplwY-B5H
    private_key:  6LfpJ8ISAAAAABHKXS71Q2mmrjgizL9REOkNXZ_S
    # activation du recaptcha sur le formulaire de contact
    enabled:      true
  qaptcha:
    # activation du qaptcha sur le formulaire de contact
    enabled:      true
