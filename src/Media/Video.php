<?php

namespace src\Media;

class Video implements BaseMediaInterface
{

    #[\Override] public function photo()
    {
        // TODO: Implement photo() method.
    }

    #[\Override] public function title()
    {
        return $this->getMediaTitle();
    }

    #[\Override] public function content()
    {
        // TODO: Implement content() method.
    }
}