<?php declare(strict_types=1);

namespace Mediagone\Doctrine\Types\Common;

use Doctrine\DBAL\Types\Type;
use LogicException;
use function class_exists;
use function is_a;


final class DoctrineTypesLoader
{
    //========================================================================================================
    // EventSubscriberInterface interface
    //========================================================================================================
    
    public function registerTypes(array $typeClasses) : void
    {
        foreach ($typeClasses as $fqcn) {
            if (! class_exists($fqcn)) {
                throw new LogicException("The type class doesn't exists ($fqcn)");
            }
            
            if (! is_a($fqcn, Type::class, true)) {
                throw new LogicException("Unsupported type class ($fqcn), it must extends Doctrine\DBAL\Types\Type.");
            }
            
            Type::addType($fqcn::NAME, $fqcn);
        }
    }
    
    
    
}
