<?php
namespace Composer\Installers;

class mayaInstaller extends BaseInstaller
{
    protected $locations = array(
        'module' => 'maya/modules/addons/{$name}/',
    );

    /**
     * Format module name.
     *
     * Strip `module-` prefix from package name.
     *
     * @param array @vars
     *
     * @return array
     */
    public function inflectPackageVars($vars)
    {
        if ($vars['type'] == 'maya-module') {
            return $this->inflectModuleVars($vars);
        }

        return $vars;
    }

    public function inflectModuleVars($vars)
    {
        $vars['name'] = ucfirst(preg_replace('/maya-/i', '', $vars['name']));

        return $vars;
    }
}
