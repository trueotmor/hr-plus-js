<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Updates a resource.
 *
 * @param string $pagetitle The page title.
 * @param string $content The HTML content. Used in conjunction with $ta.
 * @param integer $template (optional) The modTemplate to use with this
 * resource. Defaults to 0, or a blank template.
 * @param integer $parent (optional) The parent resource ID. Defaults to 0.
 * @param string $class_key (optional) The class key. Defaults to modDocument.
 * @param integer $menuindex (optional) The menu order. Defaults to 0.
 * @param string $variablesmodified (optional) A collection of modified TVs.
 * Along with $tv1, $tv2, etc.
 * @param string $context_key (optional) The context in which this resource is
 * located. Defaults to web.
 * @param string $alias (optional) The alias for FURLs that this resource is
 * designated to.
 * @param integer $content_type (optional) The content type. Defaults to
 * text/html.
 * @param boolean $published (optional) The published status.
 * @param string $pub_date (optional) The date on which this resource should
 * become published.
 * @param string $unpub_date (optional) The date on which this resource should
 * become unpublished.
 * @param string $publishedon (optional) The date this resource was published.
 * Defaults to time()
 * @param integer $publishedby (optional) The modUser who published this
 * resource. Defaults to the current user.
 * @param json $resource_groups (optional) A JSON array of resource groups to
 * assign this resource to.
 * @param boolean $hidemenu (optional) If true, The resource will not show up in
 * menu builders.
 * @param boolean $isfolder (optional) Whether or not the resource is a
 * container of resources.
 * @param boolean $richtext (optional) If true, MODX will render the available
 * RTE for editing this resource.
 * @param boolean $donthit (optional) (deprecated) If true, MODX will not log
 * visits on this resource.
 * @param boolean $cacheable (optional) If false, the resource will not be
 * cached.
 * @param boolean $searchable (optional) If false, the resource will not appear
 * in searches.
 * @param boolean $syncsite (optional) If false, will not empty the cache on
 * save.
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $permission = 'save_document';
    public $objectType = 'resource';
    public $beforeSaveEvent = 'OnBeforeDocFormSave';
    public $afterSaveEvent = 'OnDocFormSave';

    /** @var modResource $object */
    public $object;
    /** @var modResource $parentResource */
    public $parentResource;
    /** @var string $resourceClass */
    public $resourceClass;
    /** @var modContext $this->workingContext */
    public $workingContext;
    /** @var modTemplate $template */
    public $template;
    /** @var modUser $lockedUser; */
    public $lockedUser;
    /** @var boolean $isSiteStart */
    public $isSiteStart = false;
    /** @var boolean $resourceDeleted */
    public $resourceDeleted = false;
    /** @var boolean $resourceUnDeleted */
    public $resourceUnDeleted = false;
    /** @var modResource $oldParent */
    public $oldParent;
    /** @var modResource $newParent */
    public $newParent;
    /** @var modContext $oldContext */
    public $oldContext;

    /**
     * Allow for Resources to use derivative classes for their processors
     *
     * @static
     * @param modX $modx
     * @param string $className
     * @param array $properties
     * @return modProcessor
     */
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        /** @var modResource $object */
        $object = $modx->getObject('modResource',$properties['id']);
        $classKey = !empty($properties['class_key']) ? $properties['class_key'] : ($object ? $object->get('class_key') : 'modDocument');

        if (!in_array($classKey,array('modDocument','modResource',''))) {
            $className = $classKey.'UpdateProcessor';
            if (!class_exists($className)) {
                $className = 'modResourceUpdateProcessor';
            }
        }
        /** @var modProcessor $processor */
        $processor = new $className($modx,$properties);
        return $processor;
    }

    /**
     * {@inheritDoc}
     * @return boolean|string
     */
    public function beforeSet() {
        $locked = $this->addLock();
        if ($locked !== true) {
            if ($this->lockedUser) {
                return $this->modx->lexicon('resource_locked_by', array('id' => $this->object->get('id'), 'user' => $this->lockedUser->get('username')));
            } else {
                return $this->modx->lexicon('access_denied');
            }
        }

        /* RTE workaround */
        $properties = $this->getProperties();
        if (isset($properties['ta'])) $this->setProperty('content',$properties['ta']);

        // Check if we have permission to **edit the current resource type**
        if (!$this->checkEditPermissions($this->object->get('class_key'))) {
            return $this->modx->lexicon('access_denied');
        }
        // If changing the resource type, check if we have permission to **create the selected resource type**
        if (($this->object->get('class_key') !== $properties['class_key'])
            && !$this->checkCreatePermissions($properties['class_key'])
        ) {
            return $this->modx->lexicon('access_denied');
        }


        $this->workingContext = $this->modx->getContext($this->getProperty('context_key', $this->object->get('context_key') ? $this->object->get('context_key') : 'web'));

        $this->trimPageTitle();
        $this->handleParent();
        $root = (int) $this->modx->getOption('tree_root_id');
        if ($this->object->parent != $root && $this->getProperty('parent') === $root && !$this->modx->hasPermission('new_document_in_root')) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->checkParentContext();
        $this->handleCheckBoxes();
        $this->checkFriendlyAlias();
        $this->setPublishDate();
        $this->setUnPublishDate();
        $this->checkPublishedOn();
        $this->checkPublishingPermissions();
        $result = $this->checkForUnPublishOnSiteStart();
        if ($result !== true) {
            return $result;
        }
        $this->checkDeletedStatus();

        // If we are changing an existing modResource that is not already a symlink/weblink, it does not make
        // much sense to run this check, as it would attempt to validate the existing content of the content field
        if ($properties['class_key'] === 'modSymLink' && $this->object->get('class_key') === 'modSymLink') {
            $this->checkSymLinkTarget();
        }
        if ($properties['class_key'] === 'modWebLink' && $this->object->get('class_key') === 'modWebLink') {
            $this->checkWebLinkTarget();
        }

        $this->handleResourceProperties();
        $this->unsetProperty('variablesmodified');
        return parent::beforeSet();
    }

    public function checkEditPermissions($classKey)
    {
        $permissions = [$this->permission];

        $map = [
            'modWebLink' => 'edit_weblink',
            'modSymLink' => 'edit_symlink',
            'modStaticResource' => 'edit_static_resource',
        ];
        if (array_key_exists($classKey, $map)) {
            $permissions[] = $map[$classKey];
        }

        foreach ($permissions as $permission) {
            if (!$this->modx->hasPermission($permission)) {
                // allow the error message shown to the user (in modProcessor->run()) to show the right failed permission
                $this->permission = $permission;
                return false;
            }
        }

        return true;
    }

    public function checkCreatePermissions($classKey)
    {
        $permissions = [];

        $map = [
            'modWebLink' => 'new_weblink',
            'modSymLink' => 'new_symlink',
            'modStaticResource' => 'new_static_resource',
        ];
        if (array_key_exists($classKey, $map)) {
            $permissions[] = $map[$classKey];
        }

        foreach ($permissions as $permission) {
            if (!$this->modx->hasPermission($permission)) {
                // allow the error message shown to the user (in modProcessor->run()) to show the right failed permission
                $this->permission = $permission;
                return false;
            }
        }

        return true;
    }

    /**
     * Handle any properties-specific fields
     */
    public function handleResourceProperties() {
        if ($this->object->get('class_key') == 'modWebLink') {
            $responseCode = $this->getProperty('responseCode');
            if (!empty($responseCode)) {
                $this->object->setProperty('responseCode',$responseCode);
            }
        }
    }

    /**
     * Add a lock to the resource we are saving
     * @return boolean
     */
    public function addLock() {
        $locked = $this->object->addLock();
        if ($locked !== true) {
            $stealLock = $this->getProperty('steal_lock',false);
            if (!empty($stealLock)) {
                if (!$this->modx->hasPermission('steal_locks') || !$this->object->checkPolicy('steal_lock')) {
                    return false;
                }
                if ($locked > 0 && $locked != $this->modx->user->get('id')) {
                    $this->object->removeLock($locked);
                    $locked = $this->object->addLock($this->modx->user->get('id'));
                }
            }
            if ($locked !== true) {
                $lockedBy = intval($locked);
                $this->lockedUser = $this->modx->getObject('modUser', $lockedBy);
                if ($this->lockedUser) {
                    $locked = false;
                } else {
                    $this->object->removeLock($lockedBy);
                    $locked = true;
                }
            }
        }
        return $locked;
    }

    /**
     * Trim the page title
     * @return string
     */
    public function trimPageTitle() {
        $pageTitle = $this->getProperty('pagetitle',null);
        if ($pageTitle != null && !$this->getProperty('reloadOnly',false)) {
            if ($pageTitle === '') {
                $pageTitle = $this->modx->lexicon('resource_untitled');
            }
            $pageTitle = trim($pageTitle);
            $this->setProperty('pagetitle',$pageTitle);
        }
        return $pageTitle;
    }

    /**
     * Handle the parent field, checking for veracity
     * @return int|mixed
     */
    public function handleParent() {
        $parent = $this->getProperty('parent',null);
        if ($parent !== null) {
            /* handle if parent is a context */
            if (!is_numeric($parent)) {
                $ct = $this->modx->getCount('modContext',$parent);
                if ($ct > 0) {
                    $this->setProperty('context_key',$parent);
                }
                $parent = 0;
            }

            /* ensure parent isn't a child of self */
            if ($this->object->get('parent') != $parent) {
                $children = $this->modx->getChildIds($this->object->get('id'),20,array(
                    'context' => $this->object->get('context_key'),
                ));
                if (in_array($parent,$children)) {
                    $this->addFieldError('parent-cmb',$this->modx->lexicon('resource_err_move_to_child'));
                }
            }

            if ($this->object->get('id') == $parent) {
                $this->addFieldError('parent-cmb', $this->modx->lexicon('resource_err_own_parent'));
            }

            /* convert parent to int */
            $this->setProperty('parent',empty($parent) ? 0 : intval($parent));
        }
        return $parent;
    }

    /**
     * If parent is changed, set context to new parent's context
     * @return mixed
     */
    public function checkParentContext() {
        $parent = $this->getProperty('parent',null);
        if ($this->object->get('parent') != $parent) {
            $this->oldParent = $this->modx->getObject('modResource',array('id' => $this->object->get('parent')));
            $this->newParent = $this->modx->getObject('modResource', $parent);
            if ($this->newParent && $this->newParent->get('context_key') !== $this->object->get('context_key')) {
                $this->oldContext = $this->modx->getContext($this->object->get('context_key'));
                if ($this->object->get('id') == $this->oldContext->getOption('site_start')) {
                    return $this->addFieldError('parent',$this->modx->lexicon('resource_err_move_sitestart'));
                }
                $this->setProperty('context_key',$this->newParent->get('context_key'));
            }
        }
        return $parent;
    }

    /**
     * Handle formatting of various checkbox fields
     * @return void
     */
    public function handleCheckBoxes() {
        $this->setCheckbox('hidemenu');
        $this->setCheckbox('isfolder');
        $this->setCheckbox('richtext');
        $this->setCheckbox('published');
        $this->setCheckbox('cacheable');
        $this->setCheckbox('searchable');
        $this->setCheckbox('syncsite');
        $this->setCheckbox('deleted');
        $this->setCheckbox('uri_override');
    }

    /**
     * Friendly URL alias checks
     * @return mixed|string
     */
    public function checkFriendlyAlias() {
        // The user submitted alias & page title
        $alias = $this->getProperty('alias');
        $pageTitle = $this->getProperty('pagetitle');
        $autoGenerated = false;

        // If we don't have an alias passed, and automatic_alias is enabled, we generate one from the pagetitle.
        if (empty($alias) && $this->workingContext->getOption('automatic_alias', false)) {
            $alias = $this->object->cleanAlias($pageTitle);
            $autoGenerated = true;
        }

        $friendlyUrlsEnabled = $this->workingContext->getOption('friendly_urls', false) && (!$this->getProperty('reloadOnly',false) || !empty($pageTitle));

        // Check for duplicates
        $duplicateContext = $this->workingContext->getOption('global_duplicate_uri_check', false) ? '' : $this->getProperty('context_key');
        $aliasPath = $this->object->getAliasPath($alias,$this->getProperties());
        $duplicateId = $this->object->isDuplicateAlias($aliasPath, $duplicateContext);
        // We have a duplicate!
        if ($duplicateId) {
            // If friendly urls is enabled, we throw an error about the alias
            if ($friendlyUrlsEnabled) {
                $err = $this->modx->lexicon('duplicate_uri_found', array(
                    'id' => $duplicateId,
                    'uri' => $aliasPath,
                ));
                $this->addFieldError('uri', $err);
                if ($this->getProperty('uri_override',0) !== 1) {
                    $this->addFieldError('alias', $err);
                }
            }
            // If friendly urls is not enabled, and we automatically generated the alias, then we just unset it
            elseif ($autoGenerated) {
                $alias = '';
            }
        }

        // If the alias is empty yet friendly urls is enabled, add an error to the alias field
        if (empty($alias) && $friendlyUrlsEnabled) {
            $this->addFieldError('alias', $this->modx->lexicon('field_required'));
        }

        // Set the new alias and return it, too.
        $this->setProperty('alias',$alias);
        return $alias;
    }

    /**
     * Format the pub_date if it is set and adjust contingencies
     * @return int
     */
    public function setPublishDate() {
        $now = time();
        $publishDate = $this->getProperty('pub_date',null);
        if ($publishDate !== null) {
            if (empty($publishDate)) {
                $publishDate = 0;
            } else {
                $strPubDate = $publishDate;
                $publishDate = strtotime($publishDate);
                if ($publishDate <= $now) { /* if we're past publish date, publish resource */
                    $this->setProperty('published',true);
                    $this->setProperty('publishedon',$strPubDate);
                    $publishDate = 0;
                }
                if ($publishDate > $now) { /* if publish date is in future, unpublish resource */
                    $this->setProperty('published',0);
                    $this->setProperty('publishedon',0);
                }
            }
            $this->setProperty('pub_date',$publishDate);
        }
        return $publishDate;
    }

    /**
     * Format the unpub_date if it is set and adjust contingencies
     * @return int|mixed
     */
    public function setUnPublishDate() {
        $now = time();
        $unPublishDate = $this->getProperty('unpub_date',null);
        if ($unPublishDate !== null) {
            if (empty($unPublishDate)) {
                $unPublishDate = 0;
            } else {
                $unPublishDate = strtotime($unPublishDate);
                if ($unPublishDate < $now) { /* if we're past the unpublish date */
                    $this->setProperty('published',0);
                    $this->setProperty('unpub_date',0);
                    $this->setProperty('pub_date',0);
                    $this->setProperty('publishedon',0);
                }
            }
            $this->setProperty('unpub_date',$unPublishDate);
        }
        return $unPublishDate;
    }

    /**
     * Set publishedon date if publish change is different
     * @return int
     */
    public function checkPublishedOn() {
        $published = $this->getProperty('published',null);
        if ($published !== null && $published != $this->object->get('published')) {
            if (empty($published)) { /* if unpublishing */
                $this->setProperty('publishedon',0);
                $this->setProperty('publishedby',0);
            } else { /* if publishing */
                $publishedOn = $this->getProperty('publishedon',null);
                $this->setProperty('publishedon',!empty($publishedOn) ? strtotime($publishedOn) : time());
                $this->setProperty('publishedby',$this->modx->user->get('id'));
            }
        } else { /* if no change, unset publishedon/publishedby */
            if (empty($published)) { /* allow changing of publishedon date if resource is published */
                $this->unsetProperty('publishedon');
            }
            $this->unsetProperty('publishedby');
        }
        return $this->getProperty('publishedon');
    }

    /**
     * Deny publishing if the user does not have access to
     * @return boolean
     */
    public function checkPublishingPermissions() {
        $canPublish = $this->modx->hasPermission('publish_document');
        if (!$canPublish) {
            $this->setProperty('published',$this->object->get('published'));
            $this->setProperty('publishedon',$this->object->get('publishedon'));
            $this->setProperty('publishedby',$this->object->get('publishedby'));
            $this->setProperty('pub_date',$this->object->get('pub_date'));
            $this->setProperty('unpub_date',$this->object->get('unpub_date'));
        }

        $canUnpublish = $this->modx->hasPermission('unpublish_document');
        if (!$canUnpublish && !$this->getProperty('published')) {
            $this->setProperty('published', $this->object->get('published'));
        }

        return $canPublish;
    }

    /**
     * Check to prevent unpublishing of site_start
     *
     * @return boolean
     */
    public function checkForUnPublishOnSiteStart() {
        $passed = true;
        $published = $this->getProperty('published',null);
        $publishDate = $this->getProperty('pub_date');
        $unPublishDate = $this->getProperty('unpub_date');
        if ($this->isSiteStart && ($published !== null && empty($published))) {
            $passed = $this->modx->lexicon('resource_err_unpublish_sitestart');
        } else if ($this->isSiteStart && (!empty($publishDate) || !empty($unPublishDate))) {
            $passed = $this->modx->lexicon('resource_err_unpublish_sitestart_dates');
        }
        return $passed;
    }

    /**
     * Check deleted status and ensure user has permissions to delete resource
     * @return boolean
     */
    public function checkDeletedStatus() {
        $deleted = $this->getProperty('deleted',null);
        if ($deleted !== null && $deleted != $this->object->get('deleted')) {
            if ($this->object->get('deleted')) { /* undelete */
                if (!$this->modx->hasPermission('undelete_document')) {
                    $this->setProperty('deleted',$this->object->get('deleted'));
                } else {
                    $this->object->set('deleted',false);
                    $this->resourceUnDeleted = true;
                }
            } else { /* delete */
                $hasPermission = $this->modx->hasPermission('delete_document');
                $map = [
                    'modWebLink' => 'delete_weblink',
                    'modSymLink' => 'delete_symlink',
                    'modStaticResource' => 'delete_resource',
                ];
                if (array_key_exists($this->object->get('class_key'), $map)) {
                    $permission = $map[$this->object->get('class_key')];
                    $hasPermission = $hasPermission && $this->modx->hasPermission($permission);
                }

                if (!$hasPermission) {
                    $this->setProperty('deleted',$this->object->get('deleted'));
                }
                else {
                    $this->object->set('deleted',true);
                    $this->resourceDeleted = true;
                }
            }
        }
        return $deleted;
    }

    /**
     * Check that the symlink target is a valid resource ID
     * @return bool
     */
    public function checkSymLinkTarget() {
        $target = $this->getProperty('content', null);

        $matches = array();
        $countFoundTags = $this->modx->getParser()->collectElementTags($target, $matches);

        if ($target === null || $target === '' || $countFoundTags) {
            return true;
        }

        if (filter_var($target, FILTER_VALIDATE_INT) === false) {
            $this->addFieldError('content', $this->modx->lexicon('resource_err_symlink_target_invalid'));
            return false;
        }

        $targetResource = $this->modx->getObject('modResource', $target);
        if (!$targetResource) {
            $this->addFieldError('content', $this->modx->lexicon('resource_err_symlink_target_nf'));
            return false;
        }

        if ($targetResource->get('id') === $this->object->get('id')) {
            $this->addFieldError('content', $this->modx->lexicon('resource_err_symlink_target_self'));
            return false;
        }

        return true;
    }

    /**
     * Check that the weblink target is a valid resource ID if it contains an integer value
     * @return bool
     */
    public function checkWebLinkTarget() {
        $target = $this->getProperty('content', null);

        $matches = array();
        $countFoundTags = $this->modx->getParser()->collectElementTags($target, $matches);

        if ($target === null || $target === '' || $countFoundTags) {
            return true;
        }

        if (filter_var($target, FILTER_VALIDATE_INT) !== false) {
            $targetResource = $this->modx->getObject('modResource', $target);
            if (!$targetResource) {
                $this->addFieldError('content', $this->modx->lexicon('resource_err_weblink_target_nf'));
                return false;
            }

            if ($targetResource->get('id') === $this->object->get('id')) {
                $this->addFieldError('content', $this->modx->lexicon('resource_err_weblink_target_self'));
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeSave() {
        $this->object->set('editedby', $this->modx->user->get('id'));
        $this->object->set('editedon', time(), 'integer');
        return parent::beforeSave();
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function afterSave() {
        $this->fixParents();
        $this->saveTemplateVariables();
        $this->setResourceGroups();
        $this->checkContextOfChildren();
        $this->fireUnDeleteEvent();
        $this->fireDeleteEvent();
        return parent::afterSave();
    }

    /**
     * Set the parents isfolder status based upon remaining children
     *
     * @return void
     */
    public function fixParents() {
        $autoIsFolder = $this->modx->getOption('auto_isfolder', null, true);
        if (!$autoIsFolder) return;

        if (!empty($this->oldParent)) {
            $oldParentChildrenCount = $this->modx->getCount('modResource', array('parent' => $this->oldParent->get('id')));
            if ($oldParentChildrenCount <= 0 || $oldParentChildrenCount == null) {
                $this->oldParent->set('isfolder', false);
                $this->oldParent->save();
            }
        }

        if (!empty($this->newParent)) {
            $this->newParent->set('isfolder', true);
            $this->newParent->save();
        }
    }

    /**
     * Set any Template Variables passed to the Resource. You must pass "tvs" as 1 or true to initiate these checks.
     * @return array|mixed
     */
    public function saveTemplateVariables() {
        $tvs = $this->getProperty('tvs',null);
        if (!empty($tvs)) {
            $tmplvars = array();

            $tvs = $this->object->getTemplateVars();
            /** @var modTemplateVar $tv */
            foreach ($tvs as $tv) {
                if (!$tv->checkResourceGroupAccess()) {
                    continue;
                }

                $tvKey = 'tv'.$tv->get('id');
                $value = $this->getProperty($tvKey,null);
                /* set value of TV */
                if ($tv->get('type') != 'checkbox') {
                    $value = $value !== null ? $value : $tv->get('default_text');
                } else {
                    $value = $value ? $value : '';
                }

                /* validation for different types */
                switch ($tv->get('type')) {
                    case 'url':
                        $prefix = $this->getProperty($tvKey.'_prefix','');
                        if ($prefix != '--') {
                            $value = str_replace(array('ftp://','http://'),'', $value);
                            $value = $prefix.$value;
                        }
                        break;
                    case 'date':
                        $value = empty($value) ? '' : strftime('%Y-%m-%d %H:%M:%S',strtotime($value));
                        break;
                    /* ensure tag types trim whitespace from tags */
                    case 'tag':
                    case 'autotag':
                        $tags = explode(',',$value);
                        $newTags = array();
                        foreach ($tags as $tag) {
                            $newTags[] = trim($tag);
                        }
                        $value = implode(',',$newTags);
                        break;
                    default:
                        /* handles checkboxes & multiple selects elements */
                        if (is_array($value)) {
                            $featureInsert = array();
                            foreach ($value as $featureValue => $featureItem) {
                                if (isset($featureItem) && $featureItem === '') {
                                    continue;
                                }
                                $featureInsert[count($featureInsert)] = $featureItem;
                            }
                            $value = implode('||',$featureInsert);
                        }
                        break;
                }

                /* if different than default and set, set TVR record */
                $default = $tv->processBindings($tv->get('default_text'),$this->object->get('id'));
                if (strcmp($value,$default) != 0) {
                    /* update the existing record */
                    $tvc = $this->modx->getObject('modTemplateVarResource',array(
                        'tmplvarid' => $tv->get('id'),
                        'contentid' => $this->object->get('id'),
                    ));
                    if ($tvc == null) {
                        /** @var modTemplateVarResource $tvc add a new record */
                        $tvc = $this->modx->newObject('modTemplateVarResource');
                        $tvc->set('tmplvarid',$tv->get('id'));
                        $tvc->set('contentid',$this->object->get('id'));
                    }
                    $tvc->set('value',$value);
                    $tvc->save();

                /* if equal to default value, erase TVR record */
                } else {
                    $tvc = $this->modx->getObject('modTemplateVarResource',array(
                        'tmplvarid' => $tv->get('id'),
                        'contentid' => $this->object->get('id'),
                    ));
                    if (!empty($tvc)) {
                        $tvc->remove();
                    }
                }
            }
        }
        return $tvs;
    }

    /**
     * If specified, set the Resource Groups attached to the Resource
     * @return mixed
     */
    public function setResourceGroups() {
        $resourceGroups = $this->getProperty('resource_groups',null);
        if ($resourceGroups !== null) {
            $resourceGroups = is_array($resourceGroups) ? $resourceGroups : $this->modx->fromJSON($resourceGroups);
            if (is_array($resourceGroups)) {
                foreach ($resourceGroups as $id => $resourceGroupAccess) {
                    /* prevent adding records for non-existing groups */
                    $resourceGroup = $this->modx->getObject('modResourceGroup',$resourceGroupAccess['id']);
                    if (empty($resourceGroup)) continue;

                    /* if assigning to group */
                    if ($resourceGroupAccess['access']) {
                        /** @var modResourceGroupResource $resourceGroupResource */
                        $resourceGroupResource = $this->modx->getObject('modResourceGroupResource',array(
                            'document_group' => $resourceGroupAccess['id'],
                            'document' => $this->object->get('id'),
                        ));
                        if (empty($resourceGroupResource)) {
                            $resourceGroupResource = $this->modx->newObject('modResourceGroupResource');
                        }
                        $resourceGroupResource->set('document_group',$resourceGroupAccess['id']);
                        $resourceGroupResource->set('document',$this->object->get('id'));
                        if ($resourceGroupResource->save()) {
                            $this->modx->invokeEvent('OnResourceAddToResourceGroup',array(
                                'mode' => 'resource-update',
                                'resource' => &$this->object,
                                'resourceGroup' => &$resourceGroup,
                            ));
                        }
                    /* if removing access to group */
                    } else {
                        $resourceGroupResource = $this->modx->getObject('modResourceGroupResource',array(
                            'document_group' => $resourceGroupAccess['id'],
                            'document' => $this->object->get('id'),
                        ));
                        if ($resourceGroupResource && $resourceGroupResource instanceof modResourceGroupResource) {
                            if ($resourceGroupResource->remove()) {
                                $this->modx->invokeEvent('OnResourceRemoveFromResourceGroup',array(
                                    'mode' => 'resource-update',
                                    'resource' => &$this->object,
                                    'resourceGroup' => &$resourceGroup,
                                ));
                            }
                        }
                    }
                } /* end foreach */
            } /* end if is_array */
        }
        return $resourceGroups;
    }

    /**
     * Reassign context for children if changed on main Resource
     * @return void
     */
    public function checkContextOfChildren() {
        if (is_object($this->oldContext) && $this->oldContext instanceof modContext && $this->oldContext->get('key') !== $this->workingContext->get('key')) {
            $this->modx->call($this->object->get('class_key'), 'updateContextOfChildren', array(&$this->modx, $this->object));
        }
    }

    /**
     * Fire UnDelete event if resource was undeleted
     * @return mixed
     */
    public function fireUnDeleteEvent() {
        $response = null;
        if (!empty($this->resourceUnDeleted)) {
            $response = $this->modx->invokeEvent('OnResourceUndelete',array(
                'id' => $this->object->get('id'),
                'resource' => &$this->object,
            ));
        }
        return $response;
    }

    /**
     * Fire Delete event if resource was deleted
     * @return null
     */
    public function fireDeleteEvent() {
        $response = null;
        if (!empty($this->resourceDeleted)) {
            $this->modx->invokeEvent('OnResourceDelete',array(
                'id' => $this->object->get('id'),
                'resource' => &$this->object,
            ));
        }
        return $response;
    }

    /**
     * Cleanup the processor and return the resulting object
     *
     * @return array
     */
    public function cleanup() {
        $this->object->removeLock();
        $this->clearCache();

        $returnArray = $this->object->get(array_diff(array_keys($this->object->_fields), array('content','ta','introtext','description','link_attributes','pagetitle','longtitle','menutitle', 'properties', 'resource_groups')));
        foreach ($returnArray as $k => $v) {
            if (strpos($k,'tv') === 0) {
                unset($returnArray[$k]);
            }
        }
        $returnArray['class_key'] = $this->object->get('class_key');
        $this->workingContext->prepare(false);
        $this->modx->reloadContext($this->workingContext->key);
        $returnArray['preview_url'] = $this->object->getPreviewUrl();

        return $this->success('',$returnArray);
    }

    /**
     * Empty site cache if specified to do so
     * @return void
     */
    public function clearCache() {
		$clear = $this->getProperty('syncsite',$this->modx->getOption('syncsite_default')) || $this->getProperty('clearCache',false);
        if ($clear) {
            $contexts = array($this->object->get('context_key'));
            if (!empty($this->oldContext)) {
                $contexts[] = $this->oldContext->get('key');
            }
            $this->modx->cacheManager->refresh(array(
                'db' => array(),
                'auto_publish' => array('contexts' => $contexts),
                'context_settings' => array('contexts' => $contexts),
                'resource' => array('contexts' => $contexts),
            ));
        }
    }
}
return 'modResourceUpdateProcessor';
