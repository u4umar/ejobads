<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/businessplus_lite/templates/field--node--field-image.html.twig */
class __TwigTemplate_2408addd04c83304513e503a58edbddb52a5c88d7a93b103af9d917e87dfbdb0 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 41
        $context["classes"] = [0 => "field", 1 => ("field--name-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 43
($context["field_name"] ?? null), 43, $this->source))), 2 => ("field--type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 44
($context["field_type"] ?? null), 44, $this->source))), 3 => ("field--label-" . $this->sandbox->ensureToStringAllowed(        // line 45
($context["label_display"] ?? null), 45, $this->source))];
        // line 49
        $context["title_classes"] = [0 => "field__label", 1 => (((        // line 51
($context["label_display"] ?? null) == "visually_hidden")) ? ("visually-hidden") : (""))];
        // line 54
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("businessplus_lite/node-images"), "html", null, true);
        echo "
";
        // line 55
        if ((($context["view_mode"] ?? null) == "full")) {
            // line 56
            echo "  ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("businessplus_lite/magnific-popup-field-image-init"), "html", null, true);
            echo "
";
        }
        // line 58
        if (($context["label_hidden"] ?? null)) {
            // line 59
            echo "  ";
            if (($context["multiple"] ?? null)) {
                // line 60
                echo "    <div";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null), 1 => "field__items"], "method", false, false, true, 60), 60, $this->source), "html", null, true);
                echo ">
      <div class=\"images-container clearfix\">
        <div class=\"image-preview clearfix\">
          <div class=\"image-wrapper clearfix\">
            <div";
                // line 64
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (($__internal_compile_0 = ($context["items"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[0] ?? null) : null), "attributes", [], "any", false, false, true, 64), "addClass", [0 => "field__item"], "method", false, false, true, 64), 64, $this->source), "html", null, true);
                echo ">
              ";
                // line 65
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_1 = ($context["items"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[0] ?? null) : null), "content", [], "any", false, false, true, 65), 65, $this->source), "html", null, true);
                echo "
            </div>
          </div>
          ";
                // line 68
                if ((twig_get_attribute($this->env, $this->source, (($__internal_compile_2 = twig_get_attribute($this->env, $this->source, (($__internal_compile_3 = ($context["items"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3[0] ?? null) : null), "content", [], "any", false, false, true, 68)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["#item"] ?? null) : null), "title", [], "any", false, false, true, 68) && (($context["view_mode"] ?? null) == "full"))) {
                    // line 69
                    echo "            <div class=\"image-caption clearfix\">
              <h4>";
                    // line 70
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_4 = twig_get_attribute($this->env, $this->source, (($__internal_compile_5 = ($context["items"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5[0] ?? null) : null), "content", [], "any", false, false, true, 70)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["#item"] ?? null) : null), "title", [], "any", false, false, true, 70), 70, $this->source), "html", null, true);
                    echo "</h4>
            </div>
          ";
                }
                // line 73
                echo "        </div>
        ";
                // line 74
                if (((twig_length_filter($this->env, ($context["items"] ?? null)) > 1) && (($context["view_mode"] ?? null) == "full"))) {
                    // line 75
                    echo "          <div class=\"image-listing-items clearfix\">
            ";
                    // line 76
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                    foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                        // line 77
                        echo "              ";
                        if (((($__internal_compile_6 = ($context["items"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6[0] ?? null) : null) != $context["item"])) {
                            // line 78
                            echo "                <div class=\"image-listing-item\">
                  <div";
                            // line 79
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 79), "addClass", [0 => "field__item"], "method", false, false, true, 79), 79, $this->source), "html", null, true);
                            echo ">";
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 79), 79, $this->source), "html", null, true);
                            echo "</div>
                </div>
              ";
                        }
                        // line 82
                        echo "            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 83
                    echo "          </div>
        ";
                }
                // line 85
                echo "      </div>
    </div>
  ";
            } else {
                // line 88
                echo "    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 89
                    echo "      <div class=\"images-container clearfix\">
        <div class=\"image-preview clearfix\">
          <div class=\"image-wrapper clearfix\">
            <div";
                    // line 92
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null), 1 => "field__item"], "method", false, false, true, 92), 92, $this->source), "html", null, true);
                    echo ">";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 92), 92, $this->source), "html", null, true);
                    echo "</div>
          </div>
          ";
                    // line 94
                    if ((twig_get_attribute($this->env, $this->source, (($__internal_compile_7 = twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 94)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["#item"] ?? null) : null), "title", [], "any", false, false, true, 94) && (($context["view_mode"] ?? null) == "full"))) {
                        // line 95
                        echo "            <div class=\"image-caption clearfix\">
              <h4>";
                        // line 96
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_8 = twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 96)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["#item"] ?? null) : null), "title", [], "any", false, false, true, 96), 96, $this->source), "html", null, true);
                        echo "</h4>
            </div>
          ";
                    }
                    // line 99
                    echo "        </div>
      </div>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 102
                echo "  ";
            }
        } else {
            // line 104
            echo "  <div";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 104), 104, $this->source), "html", null, true);
            echo ">
    <div";
            // line 105
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [0 => ($context["title_classes"] ?? null)], "method", false, false, true, 105), 105, $this->source), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 105, $this->source), "html", null, true);
            echo "</div>
    ";
            // line 106
            if (($context["multiple"] ?? null)) {
                // line 107
                echo "      <div";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null), 1 => "field__items"], "method", false, false, true, 107), 107, $this->source), "html", null, true);
                echo ">
        <div class=\"images-container clearfix\">
          <div class=\"image-preview clearfix\">
            <div class=\"image-wrapper clearfix\">
              <div";
                // line 111
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (($__internal_compile_9 = ($context["items"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9[0] ?? null) : null), "attributes", [], "any", false, false, true, 111), "addClass", [0 => "field__item"], "method", false, false, true, 111), 111, $this->source), "html", null, true);
                echo ">
                ";
                // line 112
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_10 = ($context["items"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10[0] ?? null) : null), "content", [], "any", false, false, true, 112), 112, $this->source), "html", null, true);
                echo "
              </div>
            </div>
            ";
                // line 115
                if ((twig_get_attribute($this->env, $this->source, (($__internal_compile_11 = twig_get_attribute($this->env, $this->source, (($__internal_compile_12 = ($context["items"] ?? null)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12[0] ?? null) : null), "content", [], "any", false, false, true, 115)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["#item"] ?? null) : null), "title", [], "any", false, false, true, 115) && (($context["view_mode"] ?? null) == "full"))) {
                    // line 116
                    echo "              <div class=\"image-caption clearfix\">
                <h4>";
                    // line 117
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_13 = twig_get_attribute($this->env, $this->source, (($__internal_compile_14 = ($context["items"] ?? null)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14[0] ?? null) : null), "content", [], "any", false, false, true, 117)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["#item"] ?? null) : null), "title", [], "any", false, false, true, 117), 117, $this->source), "html", null, true);
                    echo "</h4>
              </div>
            ";
                }
                // line 120
                echo "          </div>
          ";
                // line 121
                if (((twig_length_filter($this->env, ($context["items"] ?? null)) > 1) && (($context["view_mode"] ?? null) == "full"))) {
                    // line 122
                    echo "            <div class=\"image-listing-items clearfix\">
              ";
                    // line 123
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                    foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                        // line 124
                        echo "                ";
                        if (((($__internal_compile_15 = ($context["items"] ?? null)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15[0] ?? null) : null) != $context["item"])) {
                            // line 125
                            echo "                  <div class=\"image-listing-item\">
                    <div";
                            // line 126
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 126), "addClass", [0 => "field__item"], "method", false, false, true, 126), 126, $this->source), "html", null, true);
                            echo ">";
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 126), 126, $this->source), "html", null, true);
                            echo "</div>
                  </div>
                ";
                        }
                        // line 129
                        echo "              ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 130
                    echo "            </div>
          ";
                }
                // line 132
                echo "        </div>
      </div>
    ";
            } else {
                // line 135
                echo "      ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 136
                    echo "        <div class=\"images-container clearfix\">
          <div class=\"image-preview clearfix\">
            <div class=\"image-wrapper clearfix\">
              <div";
                    // line 139
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null), 1 => "field__item"], "method", false, false, true, 139), 139, $this->source), "html", null, true);
                    echo ">";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 139), 139, $this->source), "html", null, true);
                    echo "</div>
            </div>
            ";
                    // line 141
                    if ((twig_get_attribute($this->env, $this->source, (($__internal_compile_16 = twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 141)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["#item"] ?? null) : null), "title", [], "any", false, false, true, 141) && (($context["view_mode"] ?? null) == "full"))) {
                        // line 142
                        echo "              <div class=\"image-caption clearfix\">
                <h4>";
                        // line 143
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_17 = twig_get_attribute($this->env, $this->source, $context["item"], "content", [], "any", false, false, true, 143)) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["#item"] ?? null) : null), "title", [], "any", false, false, true, 143), 143, $this->source), "html", null, true);
                        echo "</h4>
              </div>
            ";
                    }
                    // line 146
                    echo "          </div>
        </div>
      ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 149
                echo "    ";
            }
            // line 150
            echo "  </div>
";
        }
    }

    public function getTemplateName()
    {
        return "themes/businessplus_lite/templates/field--node--field-image.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  296 => 150,  293 => 149,  285 => 146,  279 => 143,  276 => 142,  274 => 141,  267 => 139,  262 => 136,  257 => 135,  252 => 132,  248 => 130,  242 => 129,  234 => 126,  231 => 125,  228 => 124,  224 => 123,  221 => 122,  219 => 121,  216 => 120,  210 => 117,  207 => 116,  205 => 115,  199 => 112,  195 => 111,  187 => 107,  185 => 106,  179 => 105,  174 => 104,  170 => 102,  162 => 99,  156 => 96,  153 => 95,  151 => 94,  144 => 92,  139 => 89,  134 => 88,  129 => 85,  125 => 83,  119 => 82,  111 => 79,  108 => 78,  105 => 77,  101 => 76,  98 => 75,  96 => 74,  93 => 73,  87 => 70,  84 => 69,  82 => 68,  76 => 65,  72 => 64,  64 => 60,  61 => 59,  59 => 58,  53 => 56,  51 => 55,  47 => 54,  45 => 51,  44 => 49,  42 => 45,  41 => 44,  40 => 43,  39 => 41,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/businessplus_lite/templates/field--node--field-image.html.twig", "/home/rm520fzqbykb/public_html/ejobads.com/themes/businessplus_lite/templates/field--node--field-image.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 41, "if" => 55, "for" => 76);
        static $filters = array("clean_class" => 43, "escape" => 54, "length" => 74);
        static $functions = array("attach_library" => 54);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['clean_class', 'escape', 'length'],
                ['attach_library']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
