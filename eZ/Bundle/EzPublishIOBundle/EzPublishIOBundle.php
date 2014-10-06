<?php

namespace eZ\Bundle\EzPublishIOBundle;

use eZ\Bundle\EzPublishIOBundle\DependencyInjection\Compiler;
use eZ\Bundle\EzPublishIOBundle\DependencyInjection\Configuration\Parser as IOConfigParser;
use eZ\Bundle\EzPublishIOBundle\DependencyInjection\EzPublishIOExtension;
use eZ\Publish\Core\Base\Container\Compiler\IOHandlerTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzPublishIOBundle extends Bundle
{
    public function build( ContainerBuilder $container )
    {
        $container->addCompilerPass( new IOHandlerTagPass() );
        $container->addCompilerPass( new Compiler\IOConfigurationPass() );
        parent::build( $container );
    }

    public function getContainerExtension()
    {
        if ( !isset( $this->extension ) )
        {
            $this->extension = new EzPublishIOExtension();
        }

        return $this->extension;
    }
}
