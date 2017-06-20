<?php

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
}
