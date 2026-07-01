<?php

defined('_JEXEC') or die;

class Pkg_FileuploaderInstallerScript
{
    public function install($parent)
    {
        return true;
    }

    public function update($parent)
    {
        return true;
    }

    public function postflight($type, $parent)
    {
        return true;
    }
}
