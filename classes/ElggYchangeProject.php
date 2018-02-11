<?php
/**
 * Project class
 *
 * @package Ychange
 */

class ElggYchangeProject extends ElggObject
{
    /**
     * Override attribute initializator and set correct subtype
     * @return void
     */
    protected function initializeAttributes()
    {
        parent::initializeAttributes();
        $this->attributes['subtype'] = 'project';
    }

    /**
     * Checks if project has any satellite images attached
     * @return boolean
     */
    public function hasSatelliteImages()
    {
        $count = elgg_get_entities([
            'type' => 'object',
            'subtype' => 'satellite_image',
            'container_guids' => $this->guid,
            'count' => true,
        ]);

        return $count > 0;
    }

    /**
     * Returns all satellite images attached to the entity.
     * @return mixed Array of satellite image objects
     */
    public function getSatelliteImages()
    {
        return elgg_get_entities([
            'type' => 'object',
            'subtype' => 'satellite_image',
            'container_guids' => $this->guid,
            'limit' => 0,
        ]);
    }

    /**
     * Detemines if provided value looks like geolocation
     * @return boolean
     */
    public function hasCorrectGeolocation()
    {
        $location = $this->location;

        if ( empty($location) ) return false;

        return (bool)preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $location);
    }

    /**
     * Changes access_id of existing satellite images to match the current one
     * @return void
     */
    public function changeAccessIdForSatelliteImages()
    {
        if ( $this->hasSatelliteImages() )
        {
            foreach($this->getSatelliteImages() as $image)
            {
                $image->access_id = $this->access_id;
                $image->save();
            }
        }
    }

    /**
     * Checks if current user change project access_id
     * @return boolean
     */
    public function canPublishAndUnpublish()
    {
        return $this->canEdit() && ( elgg_is_admin_logged_in() || ychange_is_teacher(elgg_get_logged_in_user_entity()) );
    }

    /**
     * Notify group owner about project being published (by email),
     * the system does not track if it is the initial publishing or the project
     * got unpublished at some point in time
     * @return void
     */
    private function notifyGroupOwner()
    {
        elgg_load_library('elgg:ychange:project');

        $container = $this->getContainerEntity();
        if ( $container && elgg_instanceof($container, 'group', null, ElggGroup) )
        {
            $owner = $container->getOwnerEntity();
            if ( $owner && elgg_instanceof($owner, 'user', null, ElggUser) )
            {
                $site = elgg_get_site_entity();
                $subject = elgg_echo('ychange:email:project:published:subject', [], $owner->language);
                $body = elgg_echo('ychange:email:project:published:body', [
                    $this->getURL(),
                    ychange_project_get_teacher_questionnaire_url(),
                    ychange_project_get_student_questionnaire_url(),
                ], $owner->language);
                $params = [
                    'object' => $this,
                    'action' => 'publish',
                ];
                notify_user($owner->getGUID(), $site->getGUID(), $subject, $body, $params, 'email');
            }
        }
    }

    /**
     * Make project and attached images public
     * @return boolean
     */
    public function makePublic()
    {
        if ( $this->getAccessID() === ACCESS_PUBLIC )
        {
            return false;
        }

        if ( !$this->canPublishAndUnpublish() )
        {
            return false;
        }

        $this->access_id = ACCESS_PUBLIC;

        if ( $this->save() )
        {
            elgg_trigger_event('publish', 'project', $this);
            $this->changeAccessIdForSatelliteImages();
            $this->notifyGroupOwner();

            return true;
        }

        return false;
    }

    /**
     * Make project and attached images available only to the group
     * @return boolean
     */
    public function removeFromPublic()
    {
        if ( $this->getAccessID() !== ACCESS_PUBLIC )
        {
            return false;
        }

        if ( !$this->canPublishAndUnpublish() )
        {
            return false;
        }

        $this->access_id = get_user_access_collections($this->getContainerGUID())[0]->id;

        if ( $this->save() )
        {
            elgg_trigger_event('unpublish', 'project', $this);
            $this->changeAccessIdForSatelliteImages();

            return true;
        }

        return false;
    }
}
