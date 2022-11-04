<?php
/**
 * Base class for all HTML_QuickForm2 elements
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to BSD 3-Clause License that is bundled
 * with this package in the file LICENSE and available at the URL
 * https://raw.githubusercontent.com/pear/HTML_QuickForm2/trunk/docs/LICENSE
 *
 * @category  HTML
 * @package   HTML_QuickForm2
 * @author    Alexey Borzov <avb@php.net>
 * @author    Bertrand Mansion <golgote@mamasam.com>
 * @copyright 2006-2020 Alexey Borzov <avb@php.net>, Bertrand Mansion <golgote@mamasam.com>
 * @license   https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link      https://pear.php.net/package/HTML_QuickForm2
 */

// pear-package-only /**
// pear-package-only  * HTML_Common2 - base class for HTML elements
// pear-package-only  */
// pear-package-only require_once 'HTML/Common2.php';

// By default, we generate element IDs with numeric indexes appended even for
// elements with unique names. If you want IDs to be equal to the element
// names by default, set this configuration option to false.
if (null === HTML_Common2::getOption('id_force_append_index')) {
    HTML_Common2::setOption('id_force_append_index', true);
}

// set the default language for various elements' messages
if (null === HTML_Common2::getOption('language')) {
    HTML_Common2::setOption('language', 'en');
}

// pear-package-only /**
// pear-package-only  * Exception classes for HTML_QuickForm2
// pear-package-only  */
// pear-package-only require_once 'HTML/QuickForm2/Exception.php';

// pear-package-only /**
// pear-package-only  * Static factory class for QuickForm2 elements
// pear-package-only  */
// pear-package-only require_once 'HTML/QuickForm2/Factory.php';

// pear-package-only /**
// pear-package-only  * Base class for HTML_QuickForm2 rules
// pear-package-only  */
// pear-package-only require_once 'HTML/QuickForm2/Rule.php';


/**
 * Abstract base class for all QuickForm2 Elements and Containers
 *
 * This class is mostly here to define the interface that should be implemented
 * by the subclasses. It also contains static methods handling generation
 * of unique ids for elements which do not have ids explicitly set.
 *
 * @category HTML
 * @package  HTML_QuickForm2
 * @author   Alexey Borzov <avb@php.net>
 * @author   Bertrand Mansion <golgote@mamasam.com>
 * @license  https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @version  Release: @package_version@
 * @link     https://pear.php.net/package/HTML_QuickForm2
 */
abstract class HTML_QuickForm2_Node extends HTML_Common2
{
    const ERROR_CANNOT_REMOVE_NAME_ATTRIBUTE = 38601;
    
    const ERROR_CANNOT_REMOVE_ID_ATTRIBUTE = 38602;
    
    const ERROR_ID_CANNOT_CONTAIN_SPACES = 38603;
    
    const ERROR_CANNOT_SET_CHILD_AS_OWN_CONTAINER = 38604;
    
    const ERROR_ADDRULE_INVALID_ARGUMENTS = 38605;
    
    const ERROR_FILTER_INVALID_CALLBACK = 38606;
    
   /**
    * Array containing the parts of element ids
    * @var array
    */
    protected static $ids = array();

   /**
    * Element's "frozen" status
    * @var boolean
    */
    protected $frozen = false;

   /**
    * Whether element's value should persist when element is frozen
    * @var boolean
    */
    protected $persistent = false;

   /**
    * Element containing current
    * @var HTML_QuickForm2_Container
    */
    protected $container = null;

   /**
    * Contains options and data used for the element creation
    * @var  array
    */
    protected $data = array();

   /**
    * Validation rules for element
    * @var  array
    */
    protected $rules = array();

   /**
    * An array of callback filters for element
    * @var  array
    */
    protected $filters = array();

   /**
    * Recursive filter callbacks for element
    *
    * These are recursively applied for array values of element or propagated
    * to contained elements if the element is a Container
    *
    * @var  array
    */
    protected $recursiveFilters = array();

   /**
    * Error message (usually set via Rule if validation fails)
    * @var  string
    */
    protected $error = null;

   /**
    * Changing 'name' and 'id' attributes requires some special handling
    * @var array
    */
    protected $watchedAttributes = array('id', 'name');

   /**
    * Intercepts setting 'name' and 'id' attributes
    *
    * These attributes should always be present and thus trying to remove them
    * will result in an exception. Changing their values is delegated to
    * setName() and setId() methods, respectively
    *
    * @param string $name  Attribute name
    * @param string $value Attribute value, null if attribute is being removed
    *
    * @throws   HTML_QuickForm2_InvalidArgumentException    if trying to
    *                                   remove a required attribute
    */
    protected function onAttributeChange($name, $value = null)
    {
        if ('name' == $name) {
            if (null === $value) {
                throw new HTML_QuickForm2_InvalidArgumentException(
                    "Required attribute 'name' can not be removed",
                    self::ERROR_CANNOT_REMOVE_NAME_ATTRIBUTE
                );
            } else {
                $this->setName($value);
            }
        } elseif ('id' == $name) {
            if (null === $value) {
                throw new HTML_QuickForm2_InvalidArgumentException(
                    "Required attribute 'id' can not be removed",
                    self::ERROR_CANNOT_REMOVE_ID_ATTRIBUTE
                );
            } else {
                $this->setId($value);
            }
        }
    }

   /**
    * Class constructor
    *
    * @param string       $name       Element name
    * @param string|array $attributes HTML attributes (either a string or an array)
    * @param array        $data       Element data (label, options used for element setup)
    */
    public function __construct($name = null, $attributes = null, array $data = array())
    {
        parent::__construct($attributes);
        
        $this->setName($name);
        
        // Autogenerating the id if not set on previous steps
        $id = $this->getId();
        if ('' == $id) 
        {
            $this->setId();
        }
        // if the ID uses hyphens like the auto-generated IDs,
        // we need to make sure we don't generate the same.
        else if(stristr($id, '-')) 
        {
            self::parseID($id);
        }
        
        if (!empty($data)) {
            $this->data = array_merge($this->data, $data);
        }
        
        $this->initNode();
    }
    
   /**
    * Called after the constructor: can be extended in subclasses
    * to do any necessary initializations without having to redefine
    * the constructor.
    */
    protected function initNode()
    {
    
    }
    
    protected static $elementIDs = array();
    
   /**
    * Generates an id for the element
    *
    * Called when an element is created without explicitly given id
    *
    * @param string $elementName Element name
    *
    * @return string The generated element id
    */
    protected static function generateId($elementName)
    {
        if(empty($elementName)) {
            $elementName = 'qfauto';
        }
        
        $baseID = $elementName;
        
        // handle element[][] names: simply replace brackets with hyphens,
        // and trim the hyphens at the end. This does the following:
        // 
        // element => element
        // element[] => element
        // element[name] => element-name
        // element[name][] => element-name
        //
        if(stristr($baseID, '[')) {
            $baseID = rtrim(str_replace(array('[', ']'), '-', $elementName), '-');
        }
        
        // avoid IDs that start with numbers.
        if(is_numeric(substr($baseID, 0, 1))) {
            $baseID = 'qf'.$baseID;
        }
        
        if(!isset(self::$elementIDs[$baseID])) 
        {
            self::$elementIDs[$baseID] = 0;
            
            // only append the number if it has been explicitly set.
            if(self::getOption('id_force_append_index')) {
                $baseID .= '-'.self::$elementIDs[$baseID];
            }
        } 
        else 
        {
            self::$elementIDs[$baseID]++;
            
            // the number is appended independently of the id_force_append_index
            // option, since the element has the same name as an existing element,
            // to avoid any ID conflicts.
            $baseID .= '-'.self::$elementIDs[$baseID];
        }

        return $baseID;
    }
    
   /**
    * Parses a manually provided element ID to ensure
    * that the automatically generated IDs will not 
    * conflict with it. This is only relevant if the
    * manual ID uses the same syntax, for example:
    * 
    * <code>foo-45</code>
    * 
    * In this case, the internal counter for <code>foo</code>
    * element IDs will be set to 45, unless the counter
    * is already higher than 45. 
    *     
    * @param string $id
    * @see HTML_QuickForm2_Node::generateId()
    */
    protected static function parseID($id)
    {
        $tokens = explode('-', $id);
        $last = array_pop($tokens);
        if(!is_numeric($last)) {
            return;
        }
         
        $baseID = implode('-', $tokens);
        
        if(!isset(self::$elementIDs[$baseID]) || $last > self::$elementIDs[$baseID]) {
            self::$elementIDs[$baseID] = $last;
        }
    }
    
   /**
    * Stores the explicitly given id to prevent duplicate id generation
    *
    * @param string $id Element id
    */
    protected static function storeId($id)
    {
        $tokens    =  explode('-', $id);
        $container =& self::$ids;

        do {
            $token = array_shift($tokens);
            if (!isset($container[$token])) {
                $container[$token] = array();
            }
            $container =& $container[$token];
        } while (!empty($tokens));
    }


   /**
    * Returns the element options
    *
    * @return   array
    */
    public function getData()
    {
        return $this->data;
    }


   /**
    * Returns the element's type
    *
    * @return   string
    */
    abstract public function getType();


   /**
    * Returns the element's name
    *
    * @return   string
    */
    public function getName()
    {
        return isset($this->attributes['name'])? $this->attributes['name']: null;
    }


   /**
    * Sets the element's name
    *
    * @param string $name
    *
    * @return $this
    */
    abstract public function setName($name);


   /**
    * Returns the element's id
    *
    * @return   string
    */
    public function getId()
    {
        return isset($this->attributes['id'])? $this->attributes['id']: null;
    }


   /**
    * Sets the element's id
    *
    * Please note that elements should always have an id in QuickForm2 and
    * therefore it will not be possible to remove the element's id or set it to
    * an empty value. If id is not explicitly given, it will be autogenerated.
    *
    * @param string $id Element's id, will be autogenerated if not given
    *
    * @return HTML_QuickForm2_Node
    * @throws   HTML_QuickForm2_InvalidArgumentException if id contains invalid
    *           characters (i.e. spaces)
    */
    public function setId($id = null)
    {
        if (is_null($id)) {
            $id = self::generateId($this->getName());
        // HTML5 specification only disallows having space characters in id,
        // so we don't do stricter checks here
        } elseif (strpbrk($id, " \r\n\t\x0C")) {
            throw new HTML_QuickForm2_InvalidArgumentException(
                "The value of 'id' attribute should not contain space characters",
                self::ERROR_ID_CANNOT_CONTAIN_SPACES
            );
        } else {
            self::storeId($id);
        }
        $this->attributes['id'] = (string)$id;
        
        if(isset($this->container)) {
            $this->container->invalidateLookup();
        }
        
        return $this;
    }


   /**
    * Returns the element's value without filters applied
    *
    * @return   mixed
    */
    abstract public function getRawValue();

   /**
    * Returns the element's value, possibly with filters applied
    *
    * @return mixed
    */
    public function getValue()
    {
        $value = $this->getRawValue();
        return is_null($value)? null: $this->applyFilters($value);
    }

   /**
    * Sets the element's value
    *
    * @param mixed $value
    *
    * @return $this
    */
    abstract public function setValue($value);


   /**
    * Returns the element's label(s)
    *
    * @return   string|array
    */
    public function getLabel()
    {
        if (isset($this->data['label'])) {
            return $this->data['label'];
        }
        return null;
    }


   /**
    * Sets the element's label(s)
    *
    * @param string|array $label Label for the element (may be an array of labels)
    *
    * @return $this
    */
    public function setLabel($label)
    {
        $this->data['label'] = $label;
        return $this;
    }


   /**
    * Changes the element's frozen status
    *
    * @param bool $freeze Whether the element should be frozen or editable. If
    *                     omitted, the method will not change the frozen status,
    *                     just return its current value
    *
    * @return   bool    Old value of element's frozen status
    */
    public function toggleFrozen($freeze = null)
    {
        $old = $this->frozen;
        if (null !== $freeze) {
            $this->frozen = (bool)$freeze;
        }
        return $old;
    }


   /**
    * Changes the element's persistent freeze behaviour
    *
    * If persistent freeze is on, the element's value will be kept (and
    * submitted) in a hidden field when the element is frozen.
    *
    * @param bool $persistent New value for "persistent freeze". If omitted, the
    *                         method will not set anything, just return the current
    *                         value of the flag.
    *
    * @return   bool    Old value of "persistent freeze" flag
    */
    public function persistentFreeze($persistent = null)
    {
        $old = $this->persistent;
        if (null !== $persistent) {
            $this->persistent = (bool)$persistent;
        }
        return $old;
    }


   /**
    * Adds the link to the element containing current
    *
    * @param HTML_QuickForm2_Container $container Element containing
    *                           the current one, null if the link should
    *                           really be removed (if removing from container)
    *
    * @throws   HTML_QuickForm2_InvalidArgumentException   If trying to set a
    *                               child of an element as its container
    */
    protected function setContainer(HTML_QuickForm2_Container $container = null)
    {
        if($this->hasContainerParent($container)) {
            throw new HTML_QuickForm2_InvalidArgumentException(
                'Cannot set an element or its child as its own container',
                self::ERROR_CANNOT_SET_CHILD_AS_OWN_CONTAINER
            );
        }
        
        $previous = $this->getContainer();
        
        // this element already has that same container
        if($previous === $container) {
            return;
        }
        
        // tell the original container to remove this element
        if($previous !== null) {
            $previous->removeChild($this);
        }
        
        $this->container = $container;

        if( $container !== null) {
            $this->updateValue();
        }
        
        return;
    }
    
    public function hasContainerParent(HTML_QuickForm2_Container $container = null)
    {
        if($container === null) {
            return false;
        }
        
        $check = $container;
        
        // go up through all parent containers from this
        // container, to ensure we are not adding a container
        // that is already used as parent in the tree.
        do {
            if($this === $check) {
                return true;
            }
        }
        while($check = $check->getContainer());
        
        return false;
    }


   /**
    * Returns the element containing current
    *
    * @return   HTML_QuickForm2_Container|null
    */
    public function getContainer()
    {
        return $this->container;
    }

   /**
    * Returns the data sources for this element
    *
    * @return   array
    */
    protected function getDataSources()
    {
        if (empty($this->container)) {
            return array();
        } else {
            return $this->container->getDataSources();
        }
    }

   /**
    * Called when the element needs to update its value from form's data sources
    */
    abstract protected function updateValue();

   /**
    * Adds a validation rule
    *
    * @param HTML_QuickForm2_Rule|string $rule           Validation rule or rule type
    * @param string|int                  $messageOrRunAt If first parameter is rule type,
    *            then message to display if validation fails, otherwise constant showing
    *            whether to perfom validation client-side and/or server-side
    * @param mixed                       $options        Configuration data for the rule
    * @param int                         $runAt          Whether to perfom validation
    *               server-side and/or client side. Combination of
    *               HTML_QuickForm2_Rule::SERVER and HTML_QuickForm2_Rule::CLIENT constants
    *
    * @return   HTML_QuickForm2_Rule            The added rule
    * @throws   HTML_QuickForm2_InvalidArgumentException    if $rule is of a
    *               wrong type or rule name isn't registered with Factory
    * @throws   HTML_QuickForm2_NotFoundException   if class for a given rule
    *               name cannot be found
    */
    public function addRule(
        $rule, $messageOrRunAt = '', $options = null,
        $runAt = HTML_QuickForm2_Rule::SERVER
    ) {
        if ($rule instanceof HTML_QuickForm2_Rule) {
            $rule->setOwner($this);
            $runAt = '' == $messageOrRunAt? HTML_QuickForm2_Rule::SERVER: $messageOrRunAt;
        } elseif (is_string($rule)) {
            $rule = HTML_QuickForm2_Factory::createRule($rule, $this, $messageOrRunAt, $options);
        } else {
            throw new HTML_QuickForm2_InvalidArgumentException(
                'addRule() expects either a rule type or ' .
                'a HTML_QuickForm2_Rule instance',
                self::ERROR_ADDRULE_INVALID_ARGUMENTS
            );
        }

        $this->rules[] = array($rule, $runAt);
        return $rule;
    }
    
   /**
    * Adds a callback rule to the node.
    * 
    * @param string $message
    * @param Callable $callback
    * @param array|null $arguments
    * @param int $runAt
    * @return HTML_QuickForm2_Rule_Callback
    */
    public function addRuleCallback($message, $callback, $arguments=null, $runAt = HTML_QuickForm2_Rule::SERVER)
    {
        /* @var $rule HTML_QuickForm2_Rule_Callback */
        
        $rule = $this->addRule('callback', $message, $callback);
        
        if($arguments !== null) {
            $rule->setArguments($arguments);
        }
        
        return $rule;
    }

   /**
    * Removes a validation rule
    *
    * The method will *not* throw an Exception if the rule wasn't added to the
    * element.
    *
    * @param HTML_QuickForm2_Rule $rule Validation rule to remove
    *
    * @return   HTML_QuickForm2_Rule    Removed rule
    */
    public function removeRule(HTML_QuickForm2_Rule $rule)
    {
        foreach ($this->rules as $i => $r) {
            if ($r[0] === $rule) {
                unset($this->rules[$i]);
                break;
            }
        }
        return $rule;
    }

   /**
    * Creates a validation rule
    *
    * This method is mostly useful when when chaining several rules together
    * via {@link HTML_QuickForm2_Rule::and_()} and {@link HTML_QuickForm2_Rule::or_()}
    * methods:
    * <code>
    * $first->addRule('nonempty', 'Fill in either first or second field')
    *     ->or_($second->createRule('nonempty'));
    * </code>
    *
    * @param string $type    Rule type
    * @param string $message Message to display if validation fails
    * @param mixed  $options Configuration data for the rule
    *
    * @return   HTML_QuickForm2_Rule    The created rule
    * @throws   HTML_QuickForm2_InvalidArgumentException If rule type is unknown
    * @throws   HTML_QuickForm2_NotFoundException        If class for the rule
    *           can't be found and/or loaded from file
    */
    public function createRule($type, $message = '', $options = null)
    {
        return HTML_QuickForm2_Factory::createRule($type, $this, $message, $options);
    }


   /**
    * Checks whether an element is required
    *
    * @return   boolean
    */
    public function isRequired()
    {
        foreach ($this->rules as $rule) {
            if ($rule[0] instanceof HTML_QuickForm2_Rule_Required) {
                return true;
            }
        }
        return false;
    }

   /**
    * Adds element's client-side validation rules to a builder object
    *
    * @param HTML_QuickForm2_JavascriptBuilder $builder
    */
    protected function renderClientRules(HTML_QuickForm2_JavascriptBuilder $builder)
    {
        if ($this->toggleFrozen()) {
            return;
        }
        $onblur = HTML_QuickForm2_Rule::ONBLUR_CLIENT ^ HTML_QuickForm2_Rule::CLIENT;
        foreach ($this->rules as $rule) {
            if ($rule[1] & HTML_QuickForm2_Rule::CLIENT) {
                $builder->addRule($rule[0], $rule[1] & $onblur);
            }
        }
    }

   /**
    * Performs the server-side validation
    *
    * @return   boolean     Whether the element is valid
    */
    protected function validate()
    {
        foreach ($this->rules as $rule) {
            if (strlen($this->error)) {
                break;
            }
            if ($rule[1] & HTML_QuickForm2_Rule::SERVER) {
                $rule[0]->validate();
            }
        }
        return !strlen($this->error);
    }

   /**
    * Sets the error message to the element
    *
    * @param string $error
    *
    * @return $this
    */
    public function setError($error = null)
    {
        $this->error = (string)$error;
        return $this;
    }

   /**
    * Returns the error message for the element
    *
    * @return   string
    */
    public function getError()
    {
        return $this->error;
    }

   /**
    * Returns Javascript code for getting the element's value
    *
    * @param bool $inContainer Whether it should return a parameter for
    *                          qf.form.getContainerValue()
    *
    * @return string
    */
    abstract public function getJavascriptValue($inContainer = false);

   /**
    * Returns IDs of form fields that should trigger "live" Javascript validation
    *
    * Rules added to this element with parameter HTML_QuickForm2_Rule::ONBLUR_CLIENT
    * will be run by after these form elements change or lose focus
    *
    * @return array
    */
    abstract public function getJavascriptTriggers();

    /**
     * Adds a filter
     *
     * A filter is simply a PHP callback which will be applied to the element value
     * when getValue() is called.
     *
     *  @param callable $callback The PHP callback used for filter
     * @param array    $options  Optional arguments for the callback. The first parameter
     *                       will always be the element value, then these options will
     *                       be used as parameters for the callback.
     *
     * @return HTML_QuickForm2_Node    The element
     * @throws   HTML_QuickForm2_InvalidArgumentException    If callback is incorrect
     */
    public function addFilter($callback, array $options = array())
    {
        $callbackName = null;
        if (!is_callable($callback, false, $callbackName)) {
            throw new HTML_QuickForm2_InvalidArgumentException(
                "Filter should be a valid callback, '{$callbackName}' was given",
                self::ERROR_FILTER_INVALID_CALLBACK
            );
        }
        $this->filters[] = array($callback, $options);
        return $this;
    }

    /**
     * Adds a recursive filter
     *
     * A filter is simply a PHP callback which will be applied to the element value
     * when getValue() is called. If the element value is an array, for example with
     * selects of type 'multiple', the filter is applied to all values recursively.
     * A filter on a container will not be applied on a container value but
     * propagated to all contained elements instead.
     *
     * If the element is not a container and its value is not an array the behaviour
     * will be identical to filters added via addFilter().
     *
     *  @param callable $callback The PHP callback used for filter
     * @param array    $options  Optional arguments for the callback. The first parameter
     *                       will always be the element value, then these options will
     *                       be used as parameters for the callback.
     *
     * @return HTML_QuickForm2_Node    The element
     * @throws   HTML_QuickForm2_InvalidArgumentException    If callback is incorrect
     */
    public function addRecursiveFilter($callback, array $options = array())
    {
        $callbackName = null;
        if (!is_callable($callback, false, $callbackName)) {
            throw new HTML_QuickForm2_InvalidArgumentException(
                "Filter should be a valid callback, '{$callbackName}' was given",
                self::ERROR_FILTER_INVALID_CALLBACK
            );
        }
        $this->recursiveFilters[] = array($callback, $options);
        return $this;
    }

   /**
    * Helper function for applying filter callback to a value
    *
    * @param mixed &$value Value being filtered
    * @param mixed $key    Array key (not used, present to be able to use this
    *                      method as a callback to array_walk_recursive())
    * @param array $filter Array containing callback and additional callback
    *                      parameters
    */
    protected static function applyFilter(&$value, $key, $filter)
    {
        list($callback, $options) = $filter;
        array_unshift($options, $value);
        $value = call_user_func_array($callback, $options);
    }

    /**
     * Applies non-recursive filters on element value
     *
     * @param mixed $value Element value
     *
     * @return   mixed   Filtered value
     */
    protected function applyFilters($value)
    {
        foreach ($this->filters as $filter) {
            self::applyFilter($value, null, $filter);
        }
        return $value;
    }

   /**
    * Renders the element using the given renderer
    *
    * @param HTML_QuickForm2_Renderer $renderer
    * @return   HTML_QuickForm2_Renderer
    */
    abstract public function render(HTML_QuickForm2_Renderer $renderer);

    /**
     * Makes the element optional by removing any required
     * rules that may have been added to the element.
     *
     * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
     * @custom
     */
    public function makeOptional()
    {
        $keep = array();
        foreach ($this->rules as $rule) {
            if ($rule[0] instanceof HTML_QuickForm2_Rule_Required) {
                continue;
            }
            
            $keep[] = $rule;
        }
        
        $this->rules = $keep;
    }
    
    /**
     * Whether the element has errors. Obviously, this only makes sense
     * to call after the form has been validated.
     *
     * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
     * @return boolean
     */
    public function hasErrors()
    {
        if ($this->getError()) {
            return true;
        }
        
        return false;
    }
    
   /**
    * Stores custom runtime properties for the element.
    *
    * @var array
    * @see setRuntimeProperty()
    * @see getRuntimeProperty()
    */
    protected $runtimeProperties = array();
    
   /**
    * Sets a runtime property for the node, which can be retrieved
    * again anytime. It is not used in the element in any way, but
    * can be helpful attaching related data to an element.
    *
    * @param string $name
    * @param mixed $value
    * @return HTML_QuickForm2_Node
    */
    public function setRuntimeProperty($name, $value)
    {
        $this->runtimeProperties[$name] = $value;
        
        return $this;
    }
    
   /**
    * Retrieves the value of a previously set runtime property.
    * If it does not exist, returns the default value which can
    * optionally be specified as well.
    *
    * @param string $name
    * @param mixed $default
    * @return mixed
    */
    public function getRuntimeProperty($name, $default = null)
    {
        if (isset($this->runtimeProperties[$name])) {
            return $this->runtimeProperties[$name];
        }
        
        return $default;
    }
    
   /**
    * Retrieves any rules that may have been added to the element.
    * @return HTML_QuickForm2_Rule[]
    */
    public function getRules()
    {
        $result = array();
        foreach($this->rules as $entry) {
            $result[] = $entry[0];
        }
        
        return $result;
    }
    
   /**
    * Checks whether the element has any rules set.
    * @return bool
    */
    public function hasRules()
    {
        return !empty($this->rules);
    }
    
   /**
    * Retrieves the parent form of the node.
    * @throws Exception
    * @return HTML_QuickForm2|NULL
    */
    public function getForm() : ?HTML_QuickForm2
    {
        $container = $this->getContainer();
        
        if($container instanceof HTML_QuickForm2) {
            return $container;
        }
        
        if($container instanceof HTML_QuickForm2_Node) {
            return $container->getForm();
        }
        
        if($this instanceof HTML_QuickForm2) {
            return $this;
        }
        
        return null;
    }
    
    public function preRender()
    {
        
    }
}
