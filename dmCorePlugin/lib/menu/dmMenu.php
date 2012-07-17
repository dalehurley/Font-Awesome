<?php

class dmMenu extends dmConfigurable implements ArrayAccess, Countable, IteratorAggregate {

    protected $serviceContainer, $helper, $user, $i18n, $name, $label, $link, $level, $num, $parent, $secure, $credentials = array(), $children = array(), $moduleManager;

    public function __construct(dmBaseServiceContainer $serviceContainer, $options = array()) {
        $this->serviceContainer = $serviceContainer;
        $this->helper = $serviceContainer->getService('helper');
        $this->user = $serviceContainer->getService('user');
        $this->i18n = $serviceContainer->getService('i18n');
        $this->moduleManager = $serviceContainer->getService('module_manager');
        $this->initialize($options);
    }

    protected function initialize(array $options) {
        $this->configure($options);
    }

    public function getDefaultOptions() {

        return array(
            'ul_class' => null,
            'li_class' => null,
            'show_id' => false,
            'show_children' => true,
            'translate' => true
        );
    }

    /**
     * Setters
     */
    public function name($name = null) {
        $this->name = $name;

        return $this;
    }

    /**
     * Setter for the label.
     *
     * You can use this setter to override the default label generated
     * A label is generated when there is no link passed to the addChild method
     * or when link is set to false with link(false)
     * For example, it's easy to add a span around the label with
     * label('<span class="you_class">Menu label</span>')
     *
     * @see link()
     * @see addChild()
     *
     * @param  string $label The label to render
     *
     * @return object Return $this (fluent interface)
     */
    public function label($label) {
        $this->label = $label;

        return $this;
    }

    public function link($link) {
        $this->link = $link;

        return $this;
    }

    public function groupdisplayed($group) {

        return $this->setOption('groupdisplayed', $group);
    }

    public function secure($bool) {

        return $this->setOption('secure', (bool) $bool);
    }

    public function notAuthenticated($bool) {

        return $this->setOption('not_authenticated', (bool) $bool);
    }

    public function translate($bool) {

        return $this->setOption('translate', (bool) $bool);
    }

    public function credentials($credentials) {

        return $this->setOption('credentials', $credentials);
    }

    public function ulClass($class) {

        return $this->setOption('ul_class', $class);
    }

    public function liClass($class) {

        return $this->setOption('li_class', $class);
    }

    /**
     * Display ID html attributes ?
     */
    public function showId($bool) {

        return $this->setOption('show_id', $bool);
    }

    public function showChildren($bool) {

        return $this->setOption('show_children', $bool);
    }

    public function level($level = null) {
        $this->level = $level;

        return $this;
    }

    public function parent(dmMenu $parent) {
        $this->parent = $parent;

        return $this;
    }

    public function children(array $children = null) {
        $this->children = $children;

        return $this;
    }

    public function num($num = null) {
        $this->num = $num;

        return $this;
    }

    /**
     * Getters
     */
    public function getName() {

        return $this->name;
    }

    public function getLabel() {

        return null === $this->label ? $this->getName() : $this->label;
    }

    /**
     * @return dmBaseLinkTag the link tag
     */
    public function getLink() {
        if (!$this->link instanceof dmBaseLinkTag && !empty($this->link)) {
            $this->link = $this->helper->link($this->link);
        }

        return $this->link;
    }

    /**
     * @return dmMenu the parent menu
     */
    public function end() {

        return $this->parent;
    }

    /**
     * Returns the nesting level
     *
     * Use it when you want to override some of the default behavior
     * depending on the nesting level of your menu.
     * You could easily add some html to the link or the label
     * depending of the level by overriding renderLabel or renderLink
     *
     * @see renderLabel()
     * @see renderLink()
     *
     * @return int true Nesting level of the menu item, based on the nested set
     */
    public function getLevel() {
        if (null === $this->level) {
            $this->level = $this->parent ? $this->parent->getLevel() + 1 : -1;
        }

        return $this->level;
    }

    /**
     * @return dmMenu the root menu
     */
    public function getRoot() {

        return $this->parent ? $this->parent->getRoot() : $this;
    }

    /**
     * @return dmMenu the parent menu with level=0
     */
    public function getParentLevel0() {

        if ($this->getLevel() == 0) {
            return $this;
        }
        if ($this->parent->getLevel() > 0) {
            return $this->parent->getParentLevel0();
        }
        if ($this->parent->getLevel() == 0) {
            return $this->parent;
        }
    }

    /**
     * @return dmMenu the parent menu
     */
    public function getParent() {

        return $this->parent;
    }

    public function getChildren() {

        return $this->children;
    }

    public function getNbChildren() {

        return count($this->getChildren());
    }

    public function getNum($num = null) {

        return $this->num;
    }

    public function getFirstChild() {

        return dmArray::first($this->children);
    }

    public function getLastChild() {

        return dmArray::last($this->children);
    }

    public function getChild($name) {
        if (!$this->hasChild($name)) {
            $this->addChild($name);
        }

        return $this->children[$name];
    }

    public function getSiblings($includeMe = false) {
        if (!$this->getParent()) {
            throw new dmException('Root menu has no siblings');
        }
        $siblings = array();

        foreach ($this->getParent()->getChildren() as $key => $child) {
            if ($includeMe || $child != $this) {
                $siblings[$key] = $child;
            }
        }

        return $siblings;
    }

    /**
     * Returns true if the user has access to this menu item.
     *
     * false if the user is authenticated and the menu is only for
     * not authenticated users
     *
     * true is the menu is not secure and there is no credential on it
     *
     * false if the module is secure and the user not authenticated
     *
     * false if the user has not the correct credentials
     *
     *
     * @return boolean true if the user has access to this menu item or not
     */
    public function checkUserAccess() {
        if (!empty($this->options['not_authenticated']) && $this->user->isAuthenticated()) {

            return false;
        }
        if (empty($this->options['secure']) && empty($this->options['credentials'])) {

            return true;
        }
        if (!empty($this->options['secure']) && !$this->user->isAuthenticated()) {

            return false;
        }

        return $this->user->can($this->getOption('credentials'));
    }

    public function hasChildren() {
        $nbChildren = 0;

        foreach ($this->children as $child) {
            $nbChildren+= (int) $child->checkUserAccess();
        }

        return 0 !== $nbChildren;
    }

    /**
     * Returns true if the item has a child with the given name.
     *
     * @see addChild()
     *
     * @param  string $iname The child name
     *
     * @return boolean true if the item has a child with the given name
     *
     */
    public function hasChild($name) {

        return isset($this->children[$name]);
    }

    /**
     * Move
     */
    public function moveToFirst() {
        $siblings = array(
            $this->getName() => $this
                ) + $this->getSiblings();
        $this->getParent()->setChildren($siblings)->reAssignChildrenNums();

        return $this;
    }

    public function moveToLast() {
        $siblings = $this->getSiblings() + array(
            $this->getName() => $this
        );
        $this->getParent()->setChildren($siblings)->reAssignChildrenNums();

        return $this;
    }

    public function reAssignChildrenNums() {
        $it = 0;

        foreach ($this->getChildren() as $child) {
            $child->num(++$it);
        }
    }

    /**
     * Manipulation
     */
    public function addChild($child, $link = null) {
        if (!$child instanceof dmMenu) {
            $child = $this->serviceContainer->getService('menu', get_class($this))->name($child);
        }
        // don't increment num if the child already exists and is overriden
        // but use the old child num
        $num = $this->hasChild($child->getName()) ? $this->getChild($child->getName())->getNum() : $this->count() + 1;

        return $this->children[$child->getName()] = $child->link($link)->num($num)->parent($this);
    }

    public function removeChild($child) {
        if ($child instanceof dmMenu) {
            $child = $child->getName();
        }
        unset($this->children[$child]);

        return $this;
    }

    public function removeChildren() {
        $this->children = array();

        return $this;
    }

    public function setChildren(array $children) {
        $this->children = $children;

        return $this;
    }

    /**
     * Recursively add children based on the page structure
     */
    public function addRecursiveChildren($depth = 1) {
        if ($depth < 1 || !$this->getLink() instanceof dmFrontLinkTagPage || !$this->getLink()->getPage()->getNode()->hasChildren()) {

            return $this;
        }
//        $objetPageTableaux = array();
        $treeObject = dmDb::table('DmPage')->getTree();
        $treeObject->setBaseQuery(dmDb::table('DmPage')->createQuery('p')->withI18n($this->user->getCulture(), null, 'p')->select('p.*, pTranslation.*')
                        // ajout stef pour rendre visible ou pas les pages inactive
                        // ATTENTION : les enfants d'une page inactive ne seront pas visible dans le site
                        ->where('pTranslation.is_active = ? ', true)
                        ->orderBy('p.lft')
                // ajout stef pour rendre visible ou pas les pages inactive
                // tri par ordre alphabétique sur le name de la page : 
                //->orderBy('pTranslation.name')
        );
        if ($pageChildren = $this->getLink()->getPage()->getNode()->getChildren()) {
            $pageOrder = array();
            $arrayInitial = array();
            // sort pageChildren with le record_id corresponding model
            // Exploration des pages pour la récupération des infos de ces dernières (savoir si c'est une page automatique ou pas
            foreach ($pageChildren as $i => $childPage) {
                if ($childPage->getIsAutomatic()) {
                    // Est-ce que la table est nestedSet pour récupérer le classement dans le module même et ne pas prendre en compte le classement de dmPage
                    if ($childPage->getRecord()->getTable()->isNestedSet() == true) {
                        // Récupération de la clé du tableau pour plus tard (tableau des index initiaux)
                        $arrayInitial[$i] = $i;
                        // Mise en tableau de l'objet avec la clé qui correspond au nombre 'lft' de la table du module
                        $pageOrder[$childPage->getRecord()->get('lft')] = $childPage;
                    };
                    // Est-ce que la table est sortable pour récupérer la position dans le module même et ne pas prendre en compte le classement de dmPage
                    if ($childPage->getRecord()->getTable()->isSortable() == true) {
                        // Récupération de la clé du tableau pour plus tard (tableau des index initiaux)
                        $arrayInitial[$i] = $i;
                        // Mise en tableau de l'objet avec la clé qui correspond au nombre 'position' de la table du module
                        $pageOrder[$childPage->getRecord()->get('position')] = $childPage;
                    };
                }
            }

            // Tri du tableau en fonction des clés du tableau $pageOrder
            ksort($pageOrder);
            // Détection si la page est automatic ou sans frère
            if (count($arrayInitial) > 0) {
                // Je récupère le tableau des index initiaux
                // Puis je construis un tableau en fusionnant les clés du tableau des index initiaux et les valeurs du tableau $pageOrder
                $arrayInitial = array_combine($arrayInitial, $pageOrder);

                // Je remplace les valeurs des clés (si elles existent) du DoctrineCollection $pageChildren pour tout réordonner
                foreach ($arrayInitial as $key => $value) {
                    if (array_key_exists($key, $pageChildren->getKeys())) {
                        $pageChildren[$key] = $arrayInitial[$key];
                    }
                }
            }
            // launch render for children pages
            foreach ($pageChildren as $i => $childPage) {
                $this->addChild($childPage->get('name'), $childPage)->addRecursiveChildren($depth - 1);
            }
        }
        $treeObject->resetBaseQuery();

        return $this;
    }

    /**
     * Rendering
     */
    public function __toString() {
        try {

            return (string) $this->render();
        } catch (Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * render Menu
     * @param  string $menuType type of menu in dmWidgetNavigationMenuForm.php
     * @return [type]           [description]
     */
    public function render($menuType = '') {

        $html = '';
        if ($this->checkUserAccess() && $this->hasChildren()) {
            $html = $this->renderUlOpenTag($menuType);

            foreach ($this->children as $child) {
                //echo $child->getLevel().' - '.$child->getName().'<br/>' ;

                $display = true;

                // gestion de l'affichage des enfants
                if (is_object($child->getLink())) {
                    if (method_exists($child->getLink(), 'getPage')) {
                        if ($child->getLink()->getPage()->action == 'list') { // les pages ayant une action = list sont les pages manuelles qui list les page automatique filles
                            if (!$child->getLink()->getPage()->hasChildrenActive()) { // s'il n'y a pas de pages filles alors on n'affiche pas cette page list
                                $display = false;
                            }
                        }
                    }
                }

                /**
                 * Gestion des champs groupPage de dmPage, en fonction du champ groupdisplayed de chaque item
                 */
                if ($display                        // si on doit afficher le child alors on vérifie le champ groupdisplayed
                        && $child->getLevel() > 0) {      // on ne traite pas le niveau 0 qui est gérable à la main dans le formulaire du widget
                    $groupdisplayed = $child->getParentLevel0()->getOption('groupdisplayed');  // on récupère le param groupdisplayed du menu de level 0 : le niveau qui est gérable dans le form du widget

                    switch ($groupdisplayed) {
                        case '*':  // on affiche tout
                            $display = true;
                            break;
                        case '':  // on affiche les pages qui n'ont pas de groupPage ou groupPage is null
                            if (is_object($child->getLink())) {
                                if (method_exists($child->getLink(), 'getPage')) {
                                    if ($child->getLink()->getPage()->groupPage == '' || $child->getLink()->getPage()->groupPage == null) {
                                        $display = true;
                                    } else {
                                        $display = false;
                                    }
                                }
                            }
                            break;
                        default:  // on affiche seulement les pages qui ont groupPage = groupdisplayed renseigné dans le menu 
                            if (is_object($child->getLink())) {
                                if (method_exists($child->getLink(), 'getPage')) {
                                    if (in_array($child->getLink()->getPage()->groupPage, explode(' ', $groupdisplayed))) {
                                        $display = true;
                                    } else {
                                        $display = false;
                                    }
                                }
                            }
                            break;
                    }
                }
                // on stocke les child à afficher
                if ($display)
                    $childToRenders[] = $child;
            }

            // childs to render
            $i = 1;
            foreach ($childToRenders as $childToRender) {
                switch ($i) {
                    case 1:
                        $class = 'first';
                        break;
                    case count($childToRenders):
                        $class = 'last';
                        break;
                    default:
                        $class = '';
                        break;
                }
                $html.= $childToRender->renderChild($class, $menuType);
                $i++;
            }

            $html.= '</ul>';
        }
        return $html;
    }

    protected function renderUlOpenTag($menuType = '') {
        $class = $this->getOption('ul_class');
        $id = $this->getOption('show_id') ? dmString::slugify($this->name . '-menu') : null;
        $id = $this->getLevel();

        switch ($menuType) {
            case 'navbar':
                if ($this->hasChildren() && $this->getOption('show_children') && $this->getLevel() <> -1) {
                    if ($class == ''){
                        $class = 'dropdown-menu';
                    } else {
                        $class .= ' dropdown-menu';
                    }
                } else {
                    if ($class == ''){
                        $class = 'nav';
                    } else {
                        $class .= ' nav';
                    }                    
                }
                break;
            default:
                break;
        }

        return '<ul' . ($id ? ' id="' . $id . '"' : '') . ($class ? ' class="' . $class . '"' : '') . '>';
    }

    public function renderChild($class='', $menuType = '') {
        $html = '';

        if ($this->checkUserAccess()) {
            $html.= $this->renderLiOpenTag($class, $menuType);
            $html.= $this->renderChildBody($menuType);
            if ($this->hasChildren() && $this->getOption('show_children')) {
                $html.= $this->render($menuType);
            }
            $html.= '</li>';
        }

        return $html;
    }

    protected function renderLiOpenTag($class='', $menuType = '') {

        if (is_object($this->getLink())) {
            if (method_exists($this->getLink(), 'getPage')) { // method exist for page in front only...
                $moduleAction = $this->getLink()->getPage()->module . '_' . $this->getLink()->getPage()->action;
            }
        } else {
            $moduleAction = '';
        }
        $classes = array();
        $id = $this->getOption('show_id') ? dmString::slugify('menu-' . $this->getRoot()->getName() . '-' . $this->getName()) : null;
        $link = $this->getLink();
        // if ($this->isFirst()) {
        //     $classes[] = 'first';
        // }
        // if ($this->isLast()) {
        //     $classes[] = 'last';
        // }
        $classes[] = $class;

        if ($this->getOption('li_class')) {
            $classes[] = $this->getOption('li_class');
        }
        if ($link && $link->isCurrent()) {
            $classes[] = $link->getOption('current_class');
        } elseif ($link
                && $link->isParent()
                && $moduleAction != 'main_root'
        ) {
            $classes[] = $link->getOption('parent_class');
        }

        // lioshi : ajout classe dm_root au li
        if ($this->getLink() instanceof dmFrontLinkTagPage) {
            if ($moduleAction == 'main_root') {
                // ajout lioshi : pas de class dm_root en plus pour le menu de type accordion
                // && $this->parent->getOption('ul_class') != 'menu-accordion' )  {
                // modif Arnaud : désactivé car la classe dm_root peut servir à l'avenir sur ce type de menu pour styler le bouton d'acceuil sur les basedocs Ténor
                // Erratum : apparemment ça ne marchait pas car la classe dm_root était quand même ajoutée
                $classes[] = 'dm_root';
            }
        }

        return '<li' . ($id ? ' id="' . $id . '"' : '') . (!empty($classes) ? ' class="' . implode(' ', $classes) . '"' : '') . '>';
    }

    public function renderChildBody($menuType = '') {

        return $this->getLink() ? $this->renderLink($menuType) : $this->renderLabel();
    }

    public function renderLink($menuType = '') {
        // suffixe à la classe link
        if ($this->getLink() instanceof dmFrontLinkTagPage) {
            $recupName = ($this->getLink()->getPage()->module . '_' . $this->getLink()->getPage()->action == 'main_root') ? '_root' : '';
            $title = ' ' . $this->getLink()->getPage()->getTitle() . ' '; // lioshi : BUG, sans les espaces qui encadrent ça ne fonctionne pas...
        } else {
            $recupName = '';
            $title = '';
        }

        switch ($menuType) {
            case 'navbar':
                if ($this->hasChildren()){
                    $caret = '<b class="caret"></b>';
                } else {
                    $caret = '';
                }
                return $this->getLink()->addClass('dropdown-toggle')->dataToggle('dropdown')->title($title)->currentSpan(false)->text($this->__($this->getLabel()).$caret)->render($menuType);
                break;
            
            default:
                return $this->getLink()->addClass('link' . $recupName)->title($title)->currentSpan(false)->text($this->__($this->getLabel()))->render($menuType);
                break;
        }
        
    }

    public function renderLabel() {

        return $this->__($this->getLabel());
    }

    protected function __($text) {

        return $this->getOption('translate') ? $this->i18n->__($text) : $text;
    }

    public function callRecursively() {
        $args = func_get_args();
        $arguments = $args;
        unset($arguments[0]);
        call_user_func_array(array(
            $this,
            $args[0]
                ), $arguments);

        foreach ($this->children as $child) {
            call_user_func_array(array(
                $child,
                'callRecursively'
                    ), $args);
        }

        return $this;
    }

    public function getPathAsString($separator = ' > ') {
        $children = array();
        $obj = $this;
        do {
            $children[] = $this->__($obj->getLabel());
        } while ($obj = $obj->getParent());

        return implode($separator, array_reverse($children));
    }

    public function toArray() {
        $array = array(
            'name' => $this->getName(),
            'level' => $this->getLevel(),
            'options' => $this->getOptions(),
            'children' => array()
        );

        foreach ($this->children as $key => $child) {
            $array['children'][$key] = $child->toArray();
        }

        return $array;
    }

    public function debug() {

        return $this->toArray();
    }

    public function fromArray($array) {
        $this->name($array['name']);
        if (isset($array['level'])) {
            $this->level($array['level']);
        }
        if (isset($array['options'])) {
            $this->setOptions($array['options']);
        }
        if (isset($array['children'])) {

            foreach ($array['children'] as $name => $child) {
                $this->addChild($name)->fromArray($child);
            }
        }

        return $this;
    }

    public function isFirst() {

        return 1 === $this->num;
    }

    public function isLast() {

        return $this->parent && ($this->parent->count() === $this->num);
    }

    /**
     * Interfaces implementations
     */
    public function count() {

        return count($this->children);
    }

    public function getIterator() {

        return new ArrayObject($this->children);
    }

    public function current() {

        return current($this->children);
    }

    public function next() {

        return next($this->children);
    }

    public function key() {

        return key($this->children);
    }

    public function valid() {

        return false !== $this->current();
    }

    public function rewind() {

        return reset($this->children);
    }

    public function offsetExists($name) {

        return isset($this->children[$name]);
    }

    public function offsetGet($name) {

        return $this->getChild($name);
    }

    public function offsetSet($name, $value) {

        return $this->addChild($name)->setLabel($value);
    }

    public function offsetUnset($name) {

        return $this->removeChild($name);
    }

    /**
     * Service getters
     */
    public function getI18n() {

        return $this->i18n;
    }

    public function getHelper() {

        return $this->helper;
    }

    public function getUser() {

        return $this->user;
    }

}

