<?php

declare(strict_types=1);

namespace AppUtils;

class VariableInfo_Renderer_HTML_Unknowntype extends VariableInfo_Renderer_HTML
{
    protected function _render()
    {
        return $this->info->toString();
    }
}
