<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* @NelmioApiDoc/Stoplight/index.html.twig */
class __TwigTemplate_2d479771d380c5996e568e88553b9767 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'meta' => [$this, 'block_meta'],
            'title' => [$this, 'block_title'],
            'javascripts' => [$this, 'block_javascripts'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'swagger_ui' => [$this, 'block_swagger_ui'],
            'swagger_initialization' => [$this, 'block_swagger_initialization'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html>
<head>
    ";
        // line 4
        yield from $this->unwrap()->yieldBlock('meta', $context, $blocks);
        // line 8
        yield "    <title>";
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        yield "</title>

    ";
        // line 10
        yield from $this->unwrap()->yieldBlock('javascripts', $context, $blocks);
        // line 13
        yield "    ";
        yield from $this->unwrap()->yieldBlock('stylesheets', $context, $blocks);
        // line 16
        yield "</head>
<body style=\"height: 100vh;\">
    ";
        // line 18
        yield from $this->unwrap()->yieldBlock('swagger_ui', $context, $blocks);
        // line 21
        yield "    ";
        yield from $this->unwrap()->yieldBlock('swagger_initialization', $context, $blocks);
        // line 34
        yield "</body>
</html>
";
        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_meta(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 5
        yield "        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
    ";
        yield from [];
    }

    // line 8
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["swagger_data"] ?? null), "spec", [], "any", false, false, false, 8), "info", [], "any", false, false, false, 8), "title", [], "any", false, false, false, 8), "html", null, true);
        yield from [];
    }

    // line 10
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 11
        yield "        ";
        yield $this->extensions['Nelmio\ApiDocBundle\Render\Html\GetNelmioAsset']->__invoke(($context["assets_mode"] ?? null), "stoplight/web-components.min.js");
        yield "
    ";
        yield from [];
    }

    // line 13
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_stylesheets(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 14
        yield "        ";
        yield $this->extensions['Nelmio\ApiDocBundle\Render\Html\GetNelmioAsset']->__invoke(($context["assets_mode"] ?? null), "stoplight/styles.min.css");
        yield "
    ";
        yield from [];
    }

    // line 18
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_swagger_ui(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 19
        yield "        <elements-api id=\"docs\"/>
    ";
        yield from [];
    }

    // line 21
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_swagger_initialization(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 22
        yield "        <script defer>
            const docs = document.getElementById('docs');

            docs.apiDescriptionDocument = ";
        // line 25
        yield json_encode(($context["swagger_data"] ?? null), 65);
        yield ".spec;

            const config = ";
        // line 27
        yield json_encode(($context["stoplight_config"] ?? null), 65);
        yield ";

            Object.keys(config).forEach(key => {
                docs[key] = config[key];
            });
        </script>
    ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@NelmioApiDoc/Stoplight/index.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  167 => 27,  162 => 25,  157 => 22,  150 => 21,  144 => 19,  137 => 18,  129 => 14,  122 => 13,  114 => 11,  107 => 10,  96 => 8,  89 => 5,  82 => 4,  75 => 34,  72 => 21,  70 => 18,  66 => 16,  63 => 13,  61 => 10,  55 => 8,  53 => 4,  48 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "@NelmioApiDoc/Stoplight/index.html.twig", "/Users/maamouriwael/QuaiAntiqueRestaurantBack/vendor/nelmio/api-doc-bundle/templates/Stoplight/index.html.twig");
    }
}
