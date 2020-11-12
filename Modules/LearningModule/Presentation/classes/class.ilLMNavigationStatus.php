<?php

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Checks current navigation request status
 * - determines the current page (request may e.g. pass a chapter id, in this case the next page is searched)
 * - determines if all pages of a chapter are deactivated
 * - determines if the current page is deactivated
 *
 * @author killing@leifos.de
 */
class ilLMNavigationStatus
{

    /**
     * @var int?
     */
    protected $current_page_id = null;

    /**
     * @var bool
     */
    protected $chapter_has_no_active_page = false;

    /**
     * @var bool
     */
    protected $deactivated_page = false;

    /**
     * @var ilLMTree
     */
    protected $lm;

    /**
     * @var ilLMTree
     */
    protected $lm_tree;

    /**
     * @var ilObjUser
     */
    protected $user;

    /**
     * @var ilSetting
     */
    protected $lm_set;

    /**
     * @var int
     */
    protected $requested_back_page;

    /**
     * Constructor
     */
    public function __construct(
        ilObjUser $user,
        $request_obj_id,
        ilLMTree $lm_tree,
        ilObjLearningModule $lm,
        ilSetting $lm_set,
        $requested_back_page
    ) {
        $this->user = $user;
        $this->requested_obj_id = (int) $request_obj_id;
        $this->lm_tree = $lm_tree;
        $this->lm = $lm;
        $this->lm_set = $lm_set;
        $this->current_page_id;
        $this->requested_back_page = (int) $requested_back_page;

        $this->determineStatus();
    }

    /**
     * Has current chapter no avtive page?
     *
     * @return bool
     */
    public function isChapterWithoutActivePage()
    {
        return $this->chapter_has_no_active_page;
    }

    /**
     * Has current chapter no avtive page?
     *
     * @return bool
     */
    public function isDeactivatedPage()
    {
        return $this->deactivated_page;
    }

    /**
     * Has current chapter no avtive page?
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->current_page_id;
    }


    /**
     * Determine status
     */
    protected function determineStatus()
    {
        $user = $this->user;

        $this->chapter_has_no_active_page = false;
        $this->deactivated_page = false;

        // determine object id
        if ($this->requested_obj_id == 0) {
            $obj_id = $this->lm_tree->getRootId();
        } else {
            $obj_id = $this->requested_obj_id;
            $active = ilLMPage::_lookupActive(
                $obj_id,
                $this->lm->getType(),
                $this->lm_set->get("time_scheduled_page_activation")
            );

            if (!$active &&
                ilLMPageObject::_lookupType($obj_id) == "pg") {
                $this->deactivated_page = true;
            }
        }

        // obj_id not in tree -> it is a unassigned page -> return page id
        if (!$this->lm_tree->isInTree($obj_id)) {
            $this->current_page_id = $obj_id;
            return null;
        }

        $curr_node = $this->lm_tree->getNodeData($obj_id);

        $active = ilLMPage::_lookupActive(
            $obj_id,
            $this->lm->getType(),
            $this->lm_set->get("time_scheduled_page_activation")
        );

        if ($curr_node["type"] == "pg" &&
            $active) {		// page in tree -> return page id
            $page_id = $curr_node["obj_id"];
        } else { 		// no page -> search for next page and return its id
            $succ_node = true;
            $active = false;
            $page_id = $obj_id;
            while ($succ_node && !$active) {
                $succ_node = $this->lm_tree->fetchSuccessorNode($page_id, "pg");
                $page_id = $succ_node["obj_id"];
                $active = ilLMPage::_lookupActive(
                    $page_id,
                    $this->lm->getType(),
                    $this->lm_set->get("time_scheduled_page_activation")
                );
            }

            if ($succ_node["type"] != "pg") {
                $this->chapter_has_no_active_page = true;
                $this->current_page_id = 0;
                return null;
            }

            // if public access get first public page in chapter
            if ($user->getId() == ANONYMOUS_USER_ID &&
                $this->lm->getPublicAccessMode() == 'selected') {
                $public = ilLMObject::_isPagePublic($page_id);

                while ($public === false && $page_id > 0) {
                    $succ_node = $this->lm_tree->fetchSuccessorNode($page_id, 'pg');
                    $page_id = $succ_node['obj_id'];
                    $public = ilLMObject::_isPagePublic($page_id);
                }
            }

            // check whether page found is within "clicked" chapter
            if ($this->lm_tree->isInTree($page_id)) {
                $path = $this->lm_tree->getPathId($page_id);
                if (!in_array($this->requested_obj_id, $path)) {
                    $this->chapter_has_no_active_page = true;
                }
            }
        }

        $this->current_page_id = $page_id;
    }

    /**
     * Get back link page id
     * @return int
     */
    public function getBackPageId() : int
    {
        $page_id = $this->current_page_id;

        if (empty($page_id)) {
            return 0;
        }

        $back_pg = $this->requested_back_page;

        // process navigation for free page
        if (!$this->lm_tree->isInTree($page_id)) {
            return $back_pg;
        }
        return $back_pg;
    }

    /**
     * @return int
     */
    public function getSuccessorPageId(): int
    {
        $page_id = $this->current_page_id;
        $user_id = $this->user->getId();

        // determine successor page_id
        $found = false;

        // empty chapter
        if ($this->chapter_has_no_active_page &&
            ilLMObject::_lookupType($this->requested_obj_id) == "st") {
            $c_id = $this->requested_obj_id;
        } else {
            if ($this->deactivated_page) {
                $c_id = $this->requested_obj_id;
            } else {
                $c_id = $page_id;
            }
        }
        while (!$found) {
            $succ_node = $this->lm_tree->fetchSuccessorNode($c_id, "pg");
            $c_id = $succ_node["obj_id"];

            $active = ilLMPage::_lookupActive(
                $c_id,
                $this->lm->getType(),
                $this->lm_set->get("time_scheduled_page_activation")
            );

            if ($succ_node["obj_id"] > 0 &&
                $user_id == ANONYMOUS_USER_ID &&
                ($this->lm->getPublicAccessMode() == "selected" &&
                    !ilLMObject::_isPagePublic($succ_node["obj_id"]))) {
                $found = false;
            } else {
                if ($succ_node["obj_id"] > 0 && !$active) {
                    // look, whether activation data should be shown
                    $act_data = ilLMPage::_lookupActivationData((int) $succ_node["obj_id"], $this->lm->getType());
                    if ($act_data["show_activation_info"] &&
                        (ilUtil::now() < $act_data["activation_start"])) {
                        $found = true;
                    } else {
                        $found = false;
                    }
                } else {
                    $found = true;
                }
            }
        }
        if (is_array($succ_node)) {
            return (int) $succ_node["obj_id"];
        }

        return 0;
    }

    /**
     * Get predecessor page id
     * @return int
     */
    public function getPredecessorPageId(): int
    {
        $page_id = $this->current_page_id;
        $user_id = $this->user->getId();

        // determine predecessor page id
        $found = false;
        if ($this->deactivated_page) {
            $c_id = $this->requested_obj_id;
        } else {
            $c_id = $page_id;
        }
        while (!$found) {
            $pre_node = $this->lm_tree->fetchPredecessorNode($c_id, "pg");
            $c_id = $pre_node["obj_id"];
            $active = ilLMPage::_lookupActive(
                $c_id,
                $this->lm->getType(),
                $this->lm_set->get("time_scheduled_page_activation")
            );
            if ($pre_node["obj_id"] > 0 &&
                $user_id == ANONYMOUS_USER_ID &&
                ($this->lm->getPublicAccessMode() == "selected" &&
                    !ilLMObject::_isPagePublic($pre_node["obj_id"]))) {
                $found = false;
            } else {
                if ($pre_node["obj_id"] > 0 && !$active) {
                    // look, whether activation data should be shown
                    $act_data = ilLMPage::_lookupActivationData((int) $pre_node["obj_id"], $this->lm->getType());
                    if ($act_data["show_activation_info"] &&
                        (ilUtil::now() < $act_data["activation_start"])) {
                        $found = true;
                    } else {
                        $found = false;
                    }
                } else {
                    $found = true;
                }
            }
        }
        if (is_array($pre_node)) {
            return (int) $pre_node["obj_id"];
        }

        return 0;
    }

}
