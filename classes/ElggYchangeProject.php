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

    public function getSatelliteImages()
    {
        return elgg_get_entities([
            'type' => 'object',
            'subtype' => 'satellite_image',
            'container_guids' => $this->guid,
            'lmit' => 0,
        ]);
    }
}
