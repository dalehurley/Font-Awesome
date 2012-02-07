# sfLESSPlugin for Diem users #

## Installing and setup ##

1 - In your command prompt from myproject/plugins directory

	git clone git://github.com/diem-project/sfLESSPlugin.git --recursive

2 - In your app conf file `config/ProjectConfiguration.php`

	public function setup()
	{
	  parent::setup();
	  $this->enablePlugins(array(
	    // add plugins you want to enable here
	    'sfLESSPlugin'
	  ));
	  $this->setWebDir(sfConfig::get('sf_root_dir').'/web');
	}

3 - Add config in `apps/front/config/app.yml`

	all:
	  sf_less_plugin:
	    compile:  true
	    use_js:   false
	    check_dates: true
	    # IMPORTANT : allow use of lessphp parser
	    use_lessphp_compiler: true
	    css_path: /theme/css/
	    less_path: /theme/less/

4 - Run

	./symfony dm:setup && ./symfony cc

5 - Make sure that your `web/theme/css` folder is writable

	chmod -R 777 web/theme/css/

6 - Create a web/theme/less folder

Any `web/theme/less/mystyle.less` file will me parsed 
and a `web/theme/css/mystyle.css` file is created/updated
if any modification is done.

7 - Now you can use less in Diem

Read doc at : [leafo lessphp parser doc](http://leafo.net/lessphp/docs/)
