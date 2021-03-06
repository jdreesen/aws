<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassFactory;
use AsyncAws\Core\Result;
use Nette\PhpGenerator\Method;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class OperationGenerator
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var InputGenerator
     */
    private $inputGenerator;

    /**
     * @var ResultGenerator
     */
    private $resultGenerator;

    /**
     * @var PaginationGenerator
     */
    private $paginationGenerator;

    /**
     * @var TestGenerator
     */
    private $testGenerator;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    public function __construct(NamespaceRegistry $namespaceRegistry, InputGenerator $inputGenerator, ResultGenerator $resultGenerator, PaginationGenerator $paginationGenerator, TestGenerator $testGenerator, FileWriter $fileWriter, ?TypeGenerator $typeGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->inputGenerator = $inputGenerator;
        $this->resultGenerator = $resultGenerator;
        $this->paginationGenerator = $paginationGenerator;
        $this->testGenerator = $testGenerator;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
    }

    /**
     * Update the API client with a new function call.
     */
    public function generate(Operation $operation): void
    {
        $inputShape = $operation->getInput();
        $inputClass = $this->inputGenerator->generate($operation);

        $namespace = ClassFactory::fromExistingClass($this->namespaceRegistry->getClient($operation->getService())->getFqdn());
        $namespace->addUse($inputClass->getFqdn());
        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];

        $method = $class->addMethod(\lcfirst($operation->getName()));
        if (null !== $documentation = $operation->getDocumentation()) {
            $method->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        if (null !== $documentationUrl = $operation->getDocumentationUrl()) {
            $method->addComment('@see ' . $documentationUrl);
        } elseif (null !== $prefix = $operation->getService()->getEndpointPrefix()) {
            $method->addComment('@see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-' . $prefix . '-' . $operation->getService()->getApiVersion() . '.html#' . \strtolower($operation->getName()));
        }
        $method->addComment($this->typeGenerator->generateDocblock($inputShape, $inputClass));

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }

        if (null !== $operation->getOutput()) {
            $resutClass = $this->resultGenerator->generate($operation);
            if (null !== $operation->getPagination()) {
                $this->paginationGenerator->generate($operation);
            }

            $method->setReturnType($resutClass->getFqdn());
            $namespace->addUse($resutClass->getFqdn());
        } else {
            $resutClass = null;
            $method->setReturnType(Result::class);
            $namespace->addUse(Result::class);
        }

        // Generate method body
        $this->setMethodBody($method, $operation, $inputClass, $resutClass);

        $this->fileWriter->write($namespace);

        $this->testGenerator->generate($operation);
    }

    private function setMethodBody(Method $method, Operation $operation, ClassName $inputClass, ?ClassName $resultClass): void
    {
        $params = ['$response', '$this->httpClient'];
        if ((null !== $pagination = $operation->getPagination()) && !empty($pagination->getOutputToken())) {
            $params = \array_merge($params, ['$this', '$input']);
        }

        $method->setBody(strtr('
$input = INPUT_CLASS::create($input);
$input->validate();

$response = $this->getResponse(
    METHOD,
    $input->requestBody(),
    $input->requestHeaders(),
    $this->getEndpoint($input->requestUri(), $input->requestQuery())
);

return new RESULT_CLASS(RESULT_PARAM);
        ', [
            'INPUT_CLASS' => $inputClass->getName(),
            'METHOD' => \var_export($operation->getHttpMethod(), true),
            'RESULT_CLASS' => $resultClass ? $resultClass->getName() : 'Result',
            'RESULT_PARAM' => \implode(', ', $params),
        ]));
    }
}
