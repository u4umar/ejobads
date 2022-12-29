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

/* themes/businessplus_lite/templates/page--front.html.twig */
class __TwigTemplate_4b839523988aaa26a61962f2323b4d78ba30881d5f07a9010fd021bf62ff843b extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'internal_banner' => [$this, 'block_internal_banner'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 73
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "slideout", [], "any", false, false, true, 73)) {
            // line 74
            echo "  ";
            // line 75
            echo "  <div class=\"clearfix slideout ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["slideout_background_color"] ?? null), 75, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["slideout_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["slideout_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
    ";
            // line 77
            echo "    <div class=\"clearfix slideout__container\">
      <div class=\"slideout__section\">
        ";
            // line 79
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "slideout", [], "any", false, false, true, 79), 79, $this->source), "html", null, true);
            echo "
      </div>
    </div>
    ";
            // line 83
            echo "  </div>
  ";
            // line 85
            echo "
  ";
            // line 87
            echo "  <button class=\"slideout-toggle slideout-toggle--fixed\"><i class=\"fas fa-bars\"></i></button>
  ";
        }
        // line 90
        echo "
";
        // line 92
        echo "<div class=\"page-container\">

  ";
        // line 94
        if (((((((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_first", [], "any", false, false, true, 94) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_second", [], "any", false, false, true, 94)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_first", [], "any", false, false, true, 94)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 94)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_third", [], "any", false, false, true, 94)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_first", [], "any", false, false, true, 94)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_second", [], "any", false, false, true, 94))) {
            // line 95
            echo "    ";
            // line 96
            echo "    <div class=\"header-container\">

      ";
            // line 98
            if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_first", [], "any", false, false, true, 98) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_second", [], "any", false, false, true, 98))) {
                // line 99
                echo "        ";
                // line 100
                echo "        <div class=\"clearfix header-top-highlighted ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_highlighted_background_color"] ?? null), 100, $this->source), "html", null, true);
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["header_top_highlighted_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["header_top_highlighted_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
                echo "\">
          <div class=\"";
                // line 101
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_highlighted_layout_container"] ?? null), 101, $this->source), "html", null, true);
                echo "\">
            ";
                // line 103
                echo "            <div class=\"clearfix header-top-highlighted__container";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["header_top_highlighted_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
                echo "\"
              ";
                // line 104
                if ((($context["header_top_highlighted_animations"] ?? null) == "enabled")) {
                    // line 105
                    echo "                data-animate-effect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_highlighted_animation_effect"] ?? null), 105, $this->source), "html", null, true);
                    echo "\"
              ";
                }
                // line 106
                echo ">
              <div class=\"row\">
                ";
                // line 108
                if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_first", [], "any", false, false, true, 108)) {
                    // line 109
                    echo "                  <div class=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_highlighted_first_grid_class"] ?? null), 109, $this->source), "html", null, true);
                    echo "\">
                    ";
                    // line 111
                    echo "                    <div class=\"clearfix header-top-highlighted__section header-top-highlighted-first\">
                      ";
                    // line 112
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_first", [], "any", false, false, true, 112), 112, $this->source), "html", null, true);
                    echo "
                    </div>
                    ";
                    // line 115
                    echo "                  </div>
                ";
                }
                // line 117
                echo "                ";
                if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_second", [], "any", false, false, true, 117)) {
                    // line 118
                    echo "                  <div class=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_highlighted_second_grid_class"] ?? null), 118, $this->source), "html", null, true);
                    echo "\">
                    ";
                    // line 120
                    echo "                    <div class=\"clearfix header-top-highlighted__section header-top-highlighted-second\">
                      ";
                    // line 121
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_highlighted_second", [], "any", false, false, true, 121), 121, $this->source), "html", null, true);
                    echo "
                    </div>
                    ";
                    // line 124
                    echo "                  </div>
                ";
                }
                // line 126
                echo "              </div>
            </div>
            ";
                // line 129
                echo "          </div>
        </div>
        ";
                // line 132
                echo "      ";
            }
            // line 133
            echo "
      ";
            // line 134
            if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_first", [], "any", false, false, true, 134) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_second", [], "any", false, false, true, 134))) {
                // line 135
                echo "        ";
                // line 136
                echo "        <div class=\"clearfix header-top ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_background_color"] ?? null), 136, $this->source), "html", null, true);
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["header_top_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["header_top_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
                echo "\">
          <div class=\"";
                // line 137
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_layout_container"] ?? null), 137, $this->source), "html", null, true);
                echo "\">
            ";
                // line 139
                echo "            <div class=\"clearfix header-top__container";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["header_top_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
                echo "\"
              ";
                // line 140
                if ((($context["header_top_animations"] ?? null) == "enabled")) {
                    // line 141
                    echo "                data-animate-effect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_animation_effect"] ?? null), 141, $this->source), "html", null, true);
                    echo "\"
              ";
                }
                // line 142
                echo ">
              <div class=\"row\">
                ";
                // line 144
                if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_first", [], "any", false, false, true, 144)) {
                    // line 145
                    echo "                  <div class=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_first_grid_class"] ?? null), 145, $this->source), "html", null, true);
                    echo "\">
                    ";
                    // line 147
                    echo "                    <div class=\"clearfix header-top__section header-top-first\">
                      ";
                    // line 148
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_first", [], "any", false, false, true, 148), 148, $this->source), "html", null, true);
                    echo "
                    </div>
                    ";
                    // line 151
                    echo "                  </div>
                ";
                }
                // line 153
                echo "                ";
                if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_second", [], "any", false, false, true, 153)) {
                    // line 154
                    echo "                  <div class=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_top_second_grid_class"] ?? null), 154, $this->source), "html", null, true);
                    echo "\">
                    ";
                    // line 156
                    echo "                    <div class=\"clearfix header-top__section header-top-second\">
                      ";
                    // line 157
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_second", [], "any", false, false, true, 157), 157, $this->source), "html", null, true);
                    echo "
                    </div>
                    ";
                    // line 160
                    echo "                  </div>
                ";
                }
                // line 162
                echo "              </div>
            </div>
            ";
                // line 165
                echo "          </div>
        </div>
        ";
                // line 168
                echo "      ";
            }
            // line 169
            echo "
      ";
            // line 170
            if (((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_first", [], "any", false, false, true, 170) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 170)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_third", [], "any", false, false, true, 170))) {
                // line 171
                echo "        ";
                // line 172
                echo "        <header role=\"banner\" class=\"clearfix header ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_background_color"] ?? null), 172, $this->source), "html", null, true);
                echo " ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_layout_container_class"] ?? null), 172, $this->source), "html", null, true);
                echo " ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_layout_columns_class"] ?? null), 172, $this->source), "html", null, true);
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["header_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["header_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
                echo "\">
          <div class=\"";
                // line 173
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_layout_container"] ?? null), 173, $this->source), "html", null, true);
                echo "\">
            ";
                // line 175
                echo "            <div class=\"clearfix header__container\">
              <div class=\"row\">
                ";
                // line 177
                if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_third", [], "any", false, false, true, 177)) {
                    // line 178
                    echo "                  <div class=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_third_grid_class"] ?? null), 178, $this->source), "html", null, true);
                    echo "\">
                    ";
                    // line 180
                    echo "                    <div class=\"clearfix header__section header-third\">
                      ";
                    // line 181
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_third", [], "any", false, false, true, 181), 181, $this->source), "html", null, true);
                    echo "
                    </div>
                    ";
                    // line 184
                    echo "                  </div>
                ";
                }
                // line 186
                echo "                ";
                if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_first", [], "any", false, false, true, 186)) {
                    // line 187
                    echo "                  <div class=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_first_grid_class"] ?? null), 187, $this->source), "html", null, true);
                    echo "\">
                    ";
                    // line 189
                    echo "                    <div class=\"clearfix header__section header-first\">
                      ";
                    // line 190
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_first", [], "any", false, false, true, 190), 190, $this->source), "html", null, true);
                    echo "
                    </div>
                    ";
                    // line 193
                    echo "                  </div>
                ";
                }
                // line 195
                echo "                ";
                if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 195)) {
                    // line 196
                    echo "                  <div class=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_second_grid_class"] ?? null), 196, $this->source), "html", null, true);
                    echo "\">
                    ";
                    // line 198
                    echo "                    <div class=\"clearfix header__section header-second\">
                      ";
                    // line 199
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 199), 199, $this->source), "html", null, true);
                    echo "
                    </div>
                    ";
                    // line 202
                    echo "                  </div>
                ";
                }
                // line 204
                echo "              </div>
            </div>
            ";
                // line 207
                echo "          </div>
        </header>
        ";
                // line 210
                echo "      ";
            }
            // line 211
            echo "
    </div>
    ";
            // line 214
            echo "  ";
        }
        // line 215
        echo "
  ";
        // line 216
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "banner", [], "any", false, false, true, 216)) {
            // line 217
            echo "    ";
            // line 218
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["banner_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["banner_id"] ?? null), 218, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix banner ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["banner_background_color"] ?? null), 218, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["banner_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["banner_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 219
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["banner_layout_container"] ?? null), 219, $this->source), "html", null, true);
            echo "\">
        ";
            // line 221
            echo "        <div class=\"clearfix banner__container\">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"banner__section\">
                ";
            // line 225
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "banner", [], "any", false, false, true, 225), 225, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 231
            echo "      </div>
    </div>
    ";
            // line 234
            echo "  ";
        }
        // line 235
        echo "
  ";
        // line 236
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "system_messages", [], "any", false, false, true, 236)) {
            // line 237
            echo "    <div class=\"system-messages clearfix\">
      <div class=\"container-fluid\">
        <div class=\"row\">
          <div class=\"col-12\">
            ";
            // line 241
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "system_messages", [], "any", false, false, true, 241), 241, $this->source), "html", null, true);
            echo "
          </div>
        </div>
      </div>
    </div>
  ";
        }
        // line 247
        echo "
  ";
        // line 248
        $this->displayBlock('internal_banner', $context, $blocks);
        // line 250
        echo "
  ";
        // line 251
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_top", [], "any", false, false, true, 251)) {
            // line 252
            echo "    ";
            // line 253
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_top_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["content_top_id"] ?? null), 253, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix content-top ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_top_background_color"] ?? null), 253, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_top_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_top_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 254
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_top_layout_container"] ?? null), 254, $this->source), "html", null, true);
            echo "\">
        ";
            // line 256
            echo "        <div class=\"clearfix content-top__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["content_top_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 257
            if ((($context["content_top_animations"] ?? null) == "enabled")) {
                // line 258
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_top_animation_effect"] ?? null), 258, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 259
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"content-top__section\">
                ";
            // line 263
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_top", [], "any", false, false, true, 263), 263, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 269
            echo "      </div>
    </div>
    ";
            // line 272
            echo "  ";
        }
        // line 273
        echo "
  ";
        // line 274
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_top_highlighted", [], "any", false, false, true, 274)) {
            // line 275
            echo "    ";
            // line 276
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_top_highlighted_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["content_top_highlighted_id"] ?? null), 276, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix content-top-highlighted ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_top_highlighted_background_color"] ?? null), 276, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_top_highlighted_separator"] ?? null), 276, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_top_highlighted_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_top_highlighted_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 277
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_top_highlighted_layout_container"] ?? null), 277, $this->source), "html", null, true);
            echo "\">
        ";
            // line 279
            echo "        <div class=\"clearfix content-top-highlighted__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["content_top_highlighted_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 280
            if ((($context["content_top_highlighted_animations"] ?? null) == "enabled")) {
                // line 281
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_top_highlighted_animation_effect"] ?? null), 281, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 282
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"content-top-highlighted__section\">
                ";
            // line 286
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_top_highlighted", [], "any", false, false, true, 286), 286, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 292
            echo "      </div>
    </div>
    ";
            // line 295
            echo "  ";
        }
        // line 296
        echo "
  ";
        // line 298
        echo "  ";
        $context["today"] = twig_date_converter($this->env);
        // line 299
        echo "  ";
        $context["yesterday"] = twig_date_converter($this->env, "-1days");
        // line 300
        echo "  ";
        $context["day2back"] = twig_date_converter($this->env, "-2days");
        // line 301
        echo "  ";
        $context["day3back"] = twig_date_converter($this->env, "-3days");
        // line 302
        echo "  ";
        $context["day4back"] = twig_date_converter($this->env, "-4days");
        // line 303
        echo "  ";
        $context["day5back"] = twig_date_converter($this->env, "-5days");
        // line 304
        echo "  ";
        $context["day6back"] = twig_date_converter($this->env, "-6days");
        // line 305
        echo "  
<div style=\"width: 100%;\">
<center>
<table width=\"100%\" id=\"table\" class=\"front-ads-bydate\">
<tbody>
<tr>
<td style=\"border: #DBE1E6 1px solid;\" class=\"Newspaper_border\"><b><span style=\"color:#019875;\"><br>Date - Day<br>&nbsp;</span></b></td>
<td style=\"border: #DBE1E6 1px solid;\" ><a href=\"/jang-newspaper-jobs-ads\"><img alt=\"Daily Jang\" src=\"/themes/businessplus_lite/images/papers-mini/jang-mini.jpg\"></a></td>
<td style=\"border: #DBE1E6 1px solid;\" ><a href=\"/dawn-newspaper-jobs-ads\"><img alt=\"Epress\" src=\"/themes/businessplus_lite/images/papers-mini/dawn-mini.jpg\"></a></td>
<td style=\"border: #DBE1E6 1px solid;\" ><a href=\"/thenews-newspaper-jobs-ads\"><img alt=\"Dawn\" src=\"/themes/businessplus_lite/images/papers-mini/thenews-mini.jpg\"></a></td>
<td style=\"border: #DBE1E6 1px solid;\" ><a href=\"/express-newspaper-jobs-ads\"><img alt=\"NawaiWaqt\" src=\"/themes/businessplus_lite/images/papers-mini/express-mini.jpg\"></a></td>
<td style=\"border: #DBE1E6 1px solid;\" ><a href=\"/nawaiwaqt-newspaper-jobs-ads\"><img alt=\"The News\" src=\"/themes/businessplus_lite/images/papers-mini/nawaiwaqt-mini.jpg\"></a></td>
<td style=\"border: #DBE1E6 1px solid;\" ><a href=\"/thenation-newspaper-jobs-ads\"><img alt=\"The Nation\" src=\"/themes/businessplus_lite/images/papers-mini/nation-mini.jpg\"></a></td>
</tr>

<tr>
 <td align=\"center\" style=\"border: #DBE1E6 1px solid;\">
  <b><span style=\"color:#019875;\">";
        // line 322
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 322, $this->source), "d-m-Y"), "html", null, true);
        echo "<br>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 322, $this->source), "l"), "html", null, true);
        echo "</span></b> 
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/jang-newspaper-jobs-ads-date/";
        // line 325
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 325, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/jang-newspaper-admissions-ads-date/";
        // line 326
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 326, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/jang-newspaper-tenders-ads-date/";
        // line 327
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 327, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/dawn-newspaper-jobs-ads-date/";
        // line 330
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 330, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/dawn-newspaper-admissions-ads-date/";
        // line 331
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 331, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/dawn-newspaper-tenders-ads-date/";
        // line 332
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 332, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenews-newspaper-jobs-ads-date/";
        // line 335
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 335, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenews-newspaper-admissions-ads-date/";
        // line 336
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 336, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 337
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 337, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/express-newspaper-jobs-ads-date/";
        // line 340
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 340, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/express-newspaper-admissions-ads-date/";
        // line 341
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 341, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/express-newspaper-tenders-ads-date/";
        // line 342
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 342, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/nawaiwaqt-newspaper-jobs-ads-date/";
        // line 345
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 345, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/nawaiwaqt-newspaper-admissions-ads-date/";
        // line 346
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 346, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/nawaiwaqt-newspaper-tenders-ads-date/";
        // line 347
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 347, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenation-newspaper-jobs-ads-date/";
        // line 350
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 350, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenation-newspaper-admissions-ads-date/";
        // line 351
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 351, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenation-newspaper-tenders-ads-date/";
        // line 352
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["today"] ?? null), 352, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
</tr>

<tr>
 <td align=\"center\" style=\"border: #DBE1E6 1px solid;\">
  <b><span style=\"color:#019875;\">";
        // line 358
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 358, $this->source), "d-m-Y"), "html", null, true);
        echo "<br>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 358, $this->source), "l"), "html", null, true);
        echo "</span></b> 
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/jang-newspaper-jobs-ads-date/";
        // line 361
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 361, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/jang-newspaper-admissions-ads-date/";
        // line 362
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 362, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/jang-newspaper-tenders-ads-date/";
        // line 363
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 363, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/dawn-newspaper-jobs-ads-date/";
        // line 366
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 366, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/dawn-newspaper-admissions-ads-date/";
        // line 367
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 367, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/dawn-newspaper-tenders-ads-date/";
        // line 368
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 368, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenews-newspaper-jobs-ads-date/";
        // line 371
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 371, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenews-newspaper-admissions-ads-date/";
        // line 372
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 372, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 373
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 373, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/express-newspaper-jobs-ads-date/";
        // line 376
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 376, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/express-newspaper-admissions-ads-date/";
        // line 377
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 377, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/express-newspaper-tenders-ads-date/";
        // line 378
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 378, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/nawaiwaqt-newspaper-jobs-ads-date/";
        // line 381
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 381, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/nawaiwaqt-newspaper-admissions-ads-date/";
        // line 382
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 382, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/nawaiwaqt-newspaper-tenders-ads-date/";
        // line 383
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 383, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenation-newspaper-jobs-ads-date/";
        // line 386
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 386, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenation-newspaper-admissions-ads-date/";
        // line 387
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 387, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenation-newspaper-tenders-ads-date/";
        // line 388
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["yesterday"] ?? null), 388, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
</tr>

<tr>
 <td align=\"center\" style=\"border: #DBE1E6 1px solid;\">
  <b><span style=\"color:#019875;\">";
        // line 394
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 394, $this->source), "d-m-Y"), "html", null, true);
        echo "<br>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 394, $this->source), "l"), "html", null, true);
        echo "</span></b> 
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/jang-newspaper-jobs-ads-date/";
        // line 397
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 397, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/jang-newspaper-admissions-ads-date/";
        // line 398
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 398, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/jang-newspaper-tenders-ads-date/";
        // line 399
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 399, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/dawn-newspaper-jobs-ads-date/";
        // line 402
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 402, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/dawn-newspaper-admissions-ads-date/";
        // line 403
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 403, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/dawn-newspaper-tenders-ads-date/";
        // line 404
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 404, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenews-newspaper-jobs-ads-date/";
        // line 407
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 407, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenews-newspaper-admissions-ads-date/";
        // line 408
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 408, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 409
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 409, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/express-newspaper-jobs-ads-date/";
        // line 412
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 412, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/express-newspaper-admissions-ads-date/";
        // line 413
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 413, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/express-newspaper-tenders-ads-date/";
        // line 414
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 414, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/nawaiwaqt-newspaper-jobs-ads-date/";
        // line 417
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 417, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/nawaiwaqt-newspaper-admissions-ads-date/";
        // line 418
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 418, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/nawaiwaqt-newspaper-tenders-ads-date/";
        // line 419
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 419, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenation-newspaper-jobs-ads-date/";
        // line 422
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 422, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenation-newspaper-admissions-ads-date/";
        // line 423
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 423, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenation-newspaper-tenders-ads-date/";
        // line 424
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day2back"] ?? null), 424, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
</tr>

<tr>
 <td align=\"center\" style=\"border: #DBE1E6 1px solid;\">
  <b><span style=\"color:#019875;\">";
        // line 430
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 430, $this->source), "d-m-Y"), "html", null, true);
        echo "<br>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 430, $this->source), "l"), "html", null, true);
        echo "</span></b> 
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/jang-newspaper-jobs-ads-date/";
        // line 433
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 433, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/jang-newspaper-admissions-ads-date/";
        // line 434
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 434, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/jang-newspaper-tenders-ads-date/";
        // line 435
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 435, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/dawn-newspaper-jobs-ads-date/";
        // line 438
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 438, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/dawn-newspaper-admissions-ads-date/";
        // line 439
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 439, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/dawn-newspaper-tenders-ads-date/";
        // line 440
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 440, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenews-newspaper-jobs-ads-date/";
        // line 443
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 443, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenews-newspaper-admissions-ads-date/";
        // line 444
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 444, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 445
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 445, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/express-newspaper-jobs-ads-date/";
        // line 448
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 448, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/express-newspaper-admissions-ads-date/";
        // line 449
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 449, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/express-newspaper-tenders-ads-date/";
        // line 450
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 450, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/nawaiwaqt-newspaper-jobs-ads-date/";
        // line 453
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 453, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/nawaiwaqt-newspaper-admissions-ads-date/";
        // line 454
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 454, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/nawaiwaqt-newspaper-tenders-ads-date/";
        // line 455
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 455, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenation-newspaper-jobs-ads-date/";
        // line 458
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 458, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenation-newspaper-admissions-ads-date/";
        // line 459
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 459, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenation-newspaper-tenders-ads-date/";
        // line 460
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day3back"] ?? null), 460, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
</tr>

<tr>
 <td align=\"center\" style=\"border: #DBE1E6 1px solid;\">
  <b><span style=\"color:#019875;\">";
        // line 466
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 466, $this->source), "d-m-Y"), "html", null, true);
        echo "<br>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 466, $this->source), "l"), "html", null, true);
        echo "</span></b> 
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/jang-newspaper-jobs-ads-date/";
        // line 469
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 469, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/jang-newspaper-admissions-ads-date/";
        // line 470
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 470, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/jang-newspaper-tenders-ads-date/";
        // line 471
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 471, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/dawn-newspaper-jobs-ads-date/";
        // line 474
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 474, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/dawn-newspaper-admissions-ads-date/";
        // line 475
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 475, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/dawn-newspaper-tenders-ads-date/";
        // line 476
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 476, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenews-newspaper-jobs-ads-date/";
        // line 479
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 479, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenews-newspaper-admissions-ads-date/";
        // line 480
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 480, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 481
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 481, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/express-newspaper-jobs-ads-date/";
        // line 484
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 484, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/express-newspaper-admissions-ads-date/";
        // line 485
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 485, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/express-newspaper-tenders-ads-date/";
        // line 486
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 486, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/nawaiwaqt-newspaper-jobs-ads-date/";
        // line 489
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 489, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/nawaiwaqt-newspaper-admissions-ads-date/";
        // line 490
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 490, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/nawaiwaqt-newspaper-tenders-ads-date/";
        // line 491
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 491, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenation-newspaper-jobs-ads-date/";
        // line 494
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 494, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenation-newspaper-admissions-ads-date/";
        // line 495
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 495, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenation-newspaper-tenders-ads-date/";
        // line 496
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day4back"] ?? null), 496, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
</tr>

<tr>
 <td align=\"center\" style=\"border: #DBE1E6 1px solid;\">
  <b><span style=\"color:#019875;\">";
        // line 502
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 502, $this->source), "d-m-Y"), "html", null, true);
        echo "<br>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 502, $this->source), "l"), "html", null, true);
        echo "</span></b> 
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/jang-newspaper-jobs-ads-date/";
        // line 505
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 505, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/jang-newspaper-admissions-ads-date/";
        // line 506
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 506, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/jang-newspaper-tenders-ads-date/";
        // line 507
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 507, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/dawn-newspaper-jobs-ads-date/";
        // line 510
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 510, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/dawn-newspaper-admissions-ads-date/";
        // line 511
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 511, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/dawn-newspaper-tenders-ads-date/";
        // line 512
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 512, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenews-newspaper-jobs-ads-date/";
        // line 515
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 515, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenews-newspaper-admissions-ads-date/";
        // line 516
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 516, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 517
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 517, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 518
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 518, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/express-newspaper-jobs-ads-date/";
        // line 521
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 521, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/express-newspaper-admissions-ads-date/";
        // line 522
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 522, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/express-newspaper-tenders-ads-date/";
        // line 523
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 523, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/nawaiwaqt-newspaper-jobs-ads-date/";
        // line 526
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 526, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/nawaiwaqt-newspaper-admissions-ads-date/";
        // line 527
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 527, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/nawaiwaqt-newspaper-tenders-ads-date/";
        // line 528
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 528, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenation-newspaper-jobs-ads-date/";
        // line 531
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 531, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenation-newspaper-admissions-ads-date/";
        // line 532
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 532, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenation-newspaper-tenders-ads-date/";
        // line 533
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day5back"] ?? null), 533, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
</tr>

<tr>
 <td align=\"center\" style=\"border: #DBE1E6 1px solid;\">
  <b><span style=\"color:#019875;\">";
        // line 539
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 539, $this->source), "d-m-Y"), "html", null, true);
        echo "<br>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 539, $this->source), "l"), "html", null, true);
        echo "</span></b> 
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/jang-newspaper-jobs-ads-date/";
        // line 542
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 542, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/jang-newspaper-admissions-ads-date/";
        // line 543
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 543, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/jang-newspaper-tenders-ads-date/";
        // line 544
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 544, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/dawn-newspaper-jobs-ads-date/";
        // line 547
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 547, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/dawn-newspaper-admissions-ads-date/";
        // line 548
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 548, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/dawn-newspaper-tenders-ads-date/";
        // line 549
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 549, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenews-newspaper-jobs-ads-date/";
        // line 552
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 552, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenews-newspaper-admissions-ads-date/";
        // line 553
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 553, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenews-newspaper-tenders-ads-date/";
        // line 554
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 554, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/express-newspaper-jobs-ads-date/";
        // line 557
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 557, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/express-newspaper-admissions-ads-date/";
        // line 558
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 558, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/express-newspaper-tenders-ads-date/";
        // line 559
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 559, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/nawaiwaqt-newspaper-jobs-ads-date/";
        // line 562
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 562, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/nawaiwaqt-newspaper-admissions-ads-date/";
        // line 563
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 563, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/nawaiwaqt-newspaper-tenders-ads-date/";
        // line 564
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 564, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
 <td valign=\"top\" class=\"Newspaper_border\">
  <a class=\"job-link\" href=\"/thenation-newspaper-jobs-ads-date/";
        // line 567
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 567, $this->source), "Ymd"), "html", null, true);
        echo "\">Jobs</a><br>
  <a class=\"admission-link\" href=\"/thenation-newspaper-admissions-ads-date/";
        // line 568
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 568, $this->source), "Ymd"), "html", null, true);
        echo "\">Admissions</a><br>
  <a class=\"tender-link\" href=\"/thenation-newspaper-tenders-ads-date/";
        // line 569
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["day6back"] ?? null), 569, $this->source), "Ymd"), "html", null, true);
        echo "\">Tenders</a>
 </td>
</tr>

</tbody>
</table>
</center>
</div>
  
  ";
        // line 579
        echo "
  ";
        // line 580
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_bottom_first", [], "any", false, false, true, 580) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_bottom_second", [], "any", false, false, true, 580))) {
            // line 581
            echo "    ";
            // line 582
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_bottom_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["content_bottom_id"] ?? null), 582, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix content-bottom ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_bottom_background_color"] ?? null), 582, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_bottom_separator"] ?? null), 582, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_bottom_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["content_bottom_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 583
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_bottom_layout_container"] ?? null), 583, $this->source), "html", null, true);
            echo "\">
        ";
            // line 585
            echo "        <div class=\"clearfix content-bottom__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["content_bottom_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 586
            if ((($context["content_bottom_animations"] ?? null) == "enabled")) {
                // line 587
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_bottom_animation_effect"] ?? null), 587, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 588
            echo ">
          <div class=\"row\">
            ";
            // line 590
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_bottom_first", [], "any", false, false, true, 590)) {
                // line 591
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_bottom_first_grid_class"] ?? null), 591, $this->source), "html", null, true);
                echo "\">
                ";
                // line 593
                echo "                <div class=\"clearfix content-bottom__section content-bottom-first\">
                  ";
                // line 594
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_bottom_first", [], "any", false, false, true, 594), 594, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 597
                echo "              </div>
            ";
            }
            // line 599
            echo "            ";
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_bottom_second", [], "any", false, false, true, 599)) {
                // line 600
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_bottom_second_grid_class"] ?? null), 600, $this->source), "html", null, true);
                echo "\">
                ";
                // line 602
                echo "                <div class=\"clearfix content-bottom__section content-bottom-second\">
                  ";
                // line 603
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_bottom_second", [], "any", false, false, true, 603), 603, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 606
                echo "              </div>
            ";
            }
            // line 608
            echo "          </div>
        </div>
        ";
            // line 611
            echo "      </div>
    </div>
    ";
            // line 614
            echo "  ";
        }
        // line 615
        echo "
  ";
        // line 616
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_top", [], "any", false, false, true, 616)) {
            // line 617
            echo "    ";
            // line 618
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_top_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["featured_top_id"] ?? null), 618, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix featured-top ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_top_background_color"] ?? null), 618, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_top_separator"] ?? null), 618, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_top_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_top_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 619
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_top_layout_container"] ?? null), 619, $this->source), "html", null, true);
            echo "\">
        ";
            // line 621
            echo "        <div class=\"clearfix featured-top__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["featured_top_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 622
            if ((($context["featured_top_animations"] ?? null) == "enabled")) {
                // line 623
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_top_animation_effect"] ?? null), 623, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 624
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"clearfix featured-top__section\">
                ";
            // line 628
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_top", [], "any", false, false, true, 628), 628, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 634
            echo "      </div>
    </div>
    ";
            // line 637
            echo "  ";
        }
        // line 638
        echo "
  ";
        // line 639
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured", [], "any", false, false, true, 639)) {
            // line 640
            echo "    ";
            // line 641
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["featured_id"] ?? null), 641, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix featured ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_background_color"] ?? null), 641, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_separator"] ?? null), 641, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 642
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_layout_container"] ?? null), 642, $this->source), "html", null, true);
            echo "\">
        ";
            // line 644
            echo "        <div class=\"clearfix featured__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["featured_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 645
            if ((($context["featured_animations"] ?? null) == "enabled")) {
                // line 646
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_animation_effect"] ?? null), 646, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 647
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"clearfix featured__section\">
                ";
            // line 651
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured", [], "any", false, false, true, 651), 651, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 657
            echo "      </div>
    </div>
    ";
            // line 660
            echo "  ";
        }
        // line 661
        echo "
  ";
        // line 662
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom", [], "any", false, false, true, 662)) {
            // line 663
            echo "    ";
            // line 664
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_bottom_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["featured_bottom_id"] ?? null), 664, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix featured-bottom ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_bottom_background_color"] ?? null), 664, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_bottom_separator"] ?? null), 664, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_bottom_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["featured_bottom_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 665
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_bottom_layout_container"] ?? null), 665, $this->source), "html", null, true);
            echo "\">
        ";
            // line 667
            echo "        <div class=\"clearfix featured-bottom__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["featured_bottom_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 668
            if ((($context["featured_bottom_animations"] ?? null) == "enabled")) {
                // line 669
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["featured_bottom_animation_effect"] ?? null), 669, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 670
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"clearfix featured-bottom__section\">
                ";
            // line 674
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_bottom", [], "any", false, false, true, 674), 674, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 680
            echo "      </div>
    </div>
    ";
            // line 683
            echo "  ";
        }
        // line 684
        echo "
  ";
        // line 685
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sub_featured", [], "any", false, false, true, 685)) {
            // line 686
            echo "    ";
            // line 687
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["sub_featured_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["sub_featured_id"] ?? null), 687, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix sub-featured ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sub_featured_background_color"] ?? null), 687, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sub_featured_separator"] ?? null), 687, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["sub_featured_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["sub_featured_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 688
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sub_featured_layout_container"] ?? null), 688, $this->source), "html", null, true);
            echo "\">
        ";
            // line 690
            echo "        <div class=\"clearfix sub-featured__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["sub_featured_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 691
            if ((($context["sub_featured_animations"] ?? null) == "enabled")) {
                // line 692
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sub_featured_animation_effect"] ?? null), 692, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 693
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"clearfix sub-featured__section\">
                ";
            // line 697
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sub_featured", [], "any", false, false, true, 697), 697, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 703
            echo "      </div>
    </div>
    ";
            // line 706
            echo "  ";
        }
        // line 707
        echo "
  ";
        // line 708
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted_top", [], "any", false, false, true, 708)) {
            // line 709
            echo "    ";
            // line 710
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["highlighted_top_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["highlighted_top_id"] ?? null), 710, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix highlighted-top ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_top_background_color"] ?? null), 710, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_top_separator"] ?? null), 710, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["highlighted_top_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["highlighted_top_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 711
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_top_layout_container"] ?? null), 711, $this->source), "html", null, true);
            echo "\">
        ";
            // line 713
            echo "        <div class=\"clearfix highlighted-top__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["highlighted_top_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 714
            if ((($context["highlighted_top_animations"] ?? null) == "enabled")) {
                // line 715
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_top_animation_effect"] ?? null), 715, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 716
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"clearfix highlighted-top__section\">
                ";
            // line 720
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted_top", [], "any", false, false, true, 720), 720, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 726
            echo "      </div>
    </div>
    ";
            // line 729
            echo "  ";
        }
        // line 730
        echo "
  ";
        // line 731
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted", [], "any", false, false, true, 731)) {
            // line 732
            echo "    ";
            // line 733
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["highlighted_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["highlighted_id"] ?? null), 733, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix highlighted ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_background_color"] ?? null), 733, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_separator"] ?? null), 733, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["highlighted_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["highlighted_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 734
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_layout_container"] ?? null), 734, $this->source), "html", null, true);
            echo "\">
        ";
            // line 736
            echo "        <div class=\"clearfix highlighted__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["highlighted_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 737
            if ((($context["highlighted_animations"] ?? null) == "enabled")) {
                // line 738
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["highlighted_animation_effect"] ?? null), 738, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 739
            echo ">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"clearfix highlighted__section\">
                ";
            // line 743
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted", [], "any", false, false, true, 743), 743, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 749
            echo "      </div>
    </div>
    ";
            // line 752
            echo "  ";
        }
        // line 753
        echo "
  ";
        // line 754
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top_first", [], "any", false, false, true, 754) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top_second", [], "any", false, false, true, 754))) {
            // line 755
            echo "    ";
            // line 756
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_top_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["footer_top_id"] ?? null), 756, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix footer-top ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_top_regions"] ?? null), 756, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_top_background_color"] ?? null), 756, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_top_separator"] ?? null), 756, $this->source), "html", null, true);
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_top_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_top_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 757
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_top_layout_container"] ?? null), 757, $this->source), "html", null, true);
            echo "\">
        ";
            // line 759
            echo "        <div class=\"clearfix footer-top__container";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["footer_top_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
            echo "\"
          ";
            // line 760
            if ((($context["footer_top_animations"] ?? null) == "enabled")) {
                // line 761
                echo "            data-animate-effect=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_top_animation_effect"] ?? null), 761, $this->source), "html", null, true);
                echo "\"
          ";
            }
            // line 762
            echo ">
          <div class=\"row\">
            ";
            // line 764
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top_first", [], "any", false, false, true, 764)) {
                // line 765
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_top_first_grid_class"] ?? null), 765, $this->source), "html", null, true);
                echo "\">
                ";
                // line 767
                echo "                <div class=\"clearfix footer-top__section footer-top-first\">
                  ";
                // line 768
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top_first", [], "any", false, false, true, 768), 768, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 771
                echo "              </div>
            ";
            }
            // line 773
            echo "            ";
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top_second", [], "any", false, false, true, 773)) {
                // line 774
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_top_second_grid_class"] ?? null), 774, $this->source), "html", null, true);
                echo "\">
                ";
                // line 776
                echo "                <div class=\"clearfix footer-top__section footer-top-second\">
                  ";
                // line 777
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top_second", [], "any", false, false, true, 777), 777, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 780
                echo "              </div>
            ";
            }
            // line 782
            echo "          </div>
        </div>
        ";
            // line 785
            echo "      </div>
    </div>
    ";
            // line 788
            echo "  ";
        }
        // line 789
        echo "
  ";
        // line 790
        if (((((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 790) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 790)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 790)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 790)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fifth", [], "any", false, false, true, 790))) {
            // line 791
            echo "    ";
            // line 792
            echo "    <footer ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["footer_id"] ?? null), 792, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix footer ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_background_color"] ?? null), 792, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_separator"] ?? null), 792, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 793
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_layout_container"] ?? null), 793, $this->source), "html", null, true);
            echo "\">
        <div class=\"clearfix footer__container\">
          <div class=\"row\">
            ";
            // line 796
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 796)) {
                // line 797
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_first_grid_class"] ?? null), 797, $this->source), "html", null, true);
                echo "\">
                ";
                // line 799
                echo "                <div class=\"clearfix footer__section footer-first";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["footer_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
                echo "\"
                  ";
                // line 800
                if ((($context["footer_animations"] ?? null) == "enabled")) {
                    // line 801
                    echo "                    data-animate-effect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_animation_effect"] ?? null), 801, $this->source), "html", null, true);
                    echo "\"
                  ";
                }
                // line 802
                echo ">
                  ";
                // line 803
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 803), 803, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 806
                echo "              </div>
            ";
            }
            // line 808
            echo "            ";
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 808)) {
                // line 809
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_second_grid_class"] ?? null), 809, $this->source), "html", null, true);
                echo "\">
                ";
                // line 811
                echo "                <div class=\"clearfix footer__section footer-second";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["footer_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
                echo "\"
                  ";
                // line 812
                if ((($context["footer_animations"] ?? null) == "enabled")) {
                    // line 813
                    echo "                    data-animate-effect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_animation_effect"] ?? null), 813, $this->source), "html", null, true);
                    echo "\"
                  ";
                }
                // line 814
                echo ">
                  ";
                // line 815
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 815), 815, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 818
                echo "              </div>
            ";
            }
            // line 820
            echo "            <div class=\"clearfix ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_4_columns_clearfix_first"] ?? null), 820, $this->source), "html", null, true);
            echo "\"></div>
            ";
            // line 821
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 821)) {
                // line 822
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_third_grid_class"] ?? null), 822, $this->source), "html", null, true);
                echo "\">
                ";
                // line 824
                echo "                <div class=\"clearfix footer__section footer-third";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["footer_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
                echo "\"
                  ";
                // line 825
                if ((($context["footer_animations"] ?? null) == "enabled")) {
                    // line 826
                    echo "                    data-animate-effect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_animation_effect"] ?? null), 826, $this->source), "html", null, true);
                    echo "\"
                  ";
                }
                // line 827
                echo ">
                  ";
                // line 828
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 828), 828, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 831
                echo "              </div>
            ";
            }
            // line 833
            echo "            <div class=\"clearfix ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_4_columns_clearfix_second"] ?? null), 833, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_5_columns_clearfix"] ?? null), 833, $this->source), "html", null, true);
            echo "\"></div>
            ";
            // line 834
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 834)) {
                // line 835
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_fourth_grid_class"] ?? null), 835, $this->source), "html", null, true);
                echo "\">
                ";
                // line 837
                echo "                <div class=\"clearfix footer__section footer-fourth";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["footer_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
                echo "\"
                  ";
                // line 838
                if ((($context["footer_animations"] ?? null) == "enabled")) {
                    // line 839
                    echo "                    data-animate-effect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_animation_effect"] ?? null), 839, $this->source), "html", null, true);
                    echo "\"
                  ";
                }
                // line 840
                echo ">
                  ";
                // line 841
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 841), 841, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 844
                echo "              </div>
            ";
            }
            // line 846
            echo "            ";
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fifth", [], "any", false, false, true, 846)) {
                // line 847
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_fifth_grid_class"] ?? null), 847, $this->source), "html", null, true);
                echo "\">
                ";
                // line 849
                echo "                <div class=\"clearfix footer__section footer-fifth";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["footer_animations"] ?? null) == "enabled")) ? (" mt-no-opacity") : ("")));
                echo "\"
                  ";
                // line 850
                if ((($context["footer_animations"] ?? null) == "enabled")) {
                    // line 851
                    echo "                    data-animate-effect=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_animation_effect"] ?? null), 851, $this->source), "html", null, true);
                    echo "\"
                  ";
                }
                // line 852
                echo ">
                  ";
                // line 853
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fifth", [], "any", false, false, true, 853), 853, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 856
                echo "              </div>
            ";
            }
            // line 858
            echo "          </div>
        </div>
      </div>
    </footer>
    ";
            // line 863
            echo "  ";
        }
        // line 864
        echo "
  ";
        // line 865
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_bottom", [], "any", false, false, true, 865)) {
            // line 866
            echo "    ";
            // line 867
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_bottom_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["footer_bottom_id"] ?? null), 867, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix footer-bottom ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_bottom_background_color"] ?? null), 867, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_bottom_separator"] ?? null), 867, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_bottom_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["footer_bottom_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 868
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer_bottom_layout_container"] ?? null), 868, $this->source), "html", null, true);
            echo "\">
        ";
            // line 870
            echo "        <div class=\"clearfix footer-bottom__container\">
          <div class=\"row\">
            <div class=\"col-12\">
              <div class=\"clearfix footer-bottom__section\">
                ";
            // line 874
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_bottom", [], "any", false, false, true, 874), 874, $this->source), "html", null, true);
            echo "
              </div>
            </div>
          </div>
        </div>
        ";
            // line 880
            echo "      </div>
    </div>
    ";
            // line 883
            echo "  ";
        }
        // line 884
        echo "
  ";
        // line 885
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sub_footer_first", [], "any", false, false, true, 885) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 885))) {
            // line 886
            echo "    ";
            // line 887
            echo "    <div ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["subfooter_id"] ?? null)) ? ((("id=\"" . $this->sandbox->ensureToStringAllowed(($context["subfooter_id"] ?? null), 887, $this->source)) . "\"")) : ("")));
            echo " class=\"clearfix subfooter ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["subfooter_background_color"] ?? null), 887, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["subfooter_separator"] ?? null), 887, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["subfooter_bottom_blocks_paddings"] ?? null)) ? (" region--no-block-paddings") : ("")));
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((($context["subfooter_bottom_region_paddings"] ?? null)) ? (" region--no-paddings") : ("")));
            echo "\">
      <div class=\"";
            // line 888
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["subfooter_layout_container"] ?? null), 888, $this->source), "html", null, true);
            echo "\">
        ";
            // line 890
            echo "        <div class=\"clearfix subfooter__container\">
          <div class=\"row\">
            ";
            // line 892
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sub_footer_first", [], "any", false, false, true, 892)) {
                // line 893
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["subfooter_first_grid_class"] ?? null), 893, $this->source), "html", null, true);
                echo "\">
                ";
                // line 895
                echo "                <div class=\"clearfix subfooter__section subfooter-first\">
                  ";
                // line 896
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sub_footer_first", [], "any", false, false, true, 896), 896, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 899
                echo "              </div>
            ";
            }
            // line 901
            echo "            ";
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 901)) {
                // line 902
                echo "              <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["subfooter_second_grid_class"] ?? null), 902, $this->source), "html", null, true);
                echo "\">
                ";
                // line 904
                echo "                <div class=\"clearfix subfooter__section subfooter-second\">
                  ";
                // line 905
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 905), 905, $this->source), "html", null, true);
                echo "
                </div>
                ";
                // line 908
                echo "              </div>
            ";
            }
            // line 910
            echo "          </div>
        </div>
        ";
            // line 913
            echo "      </div>
    </div>
    ";
            // line 916
            echo "  ";
        }
        // line 917
        echo "
  ";
        // line 918
        if (($context["scroll_to_top_display"] ?? null)) {
            // line 919
            echo "  ";
            // line 920
            echo "    <div class=\"to-top\"><i class=\"fas ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["scroll_to_top_icon"] ?? null), 920, $this->source), "html", null, true);
            echo "\"></i></div>
  ";
            // line 922
            echo "  ";
        }
        // line 923
        echo "
</div>
";
    }

    // line 248
    public function block_internal_banner($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 249
        echo "  ";
    }

    public function getTemplateName()
    {
        return "themes/businessplus_lite/templates/page--front.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  2117 => 249,  2113 => 248,  2107 => 923,  2104 => 922,  2099 => 920,  2097 => 919,  2095 => 918,  2092 => 917,  2089 => 916,  2085 => 913,  2081 => 910,  2077 => 908,  2072 => 905,  2069 => 904,  2064 => 902,  2061 => 901,  2057 => 899,  2052 => 896,  2049 => 895,  2044 => 893,  2042 => 892,  2038 => 890,  2034 => 888,  2022 => 887,  2020 => 886,  2018 => 885,  2015 => 884,  2012 => 883,  2008 => 880,  2000 => 874,  1994 => 870,  1990 => 868,  1978 => 867,  1976 => 866,  1974 => 865,  1971 => 864,  1968 => 863,  1962 => 858,  1958 => 856,  1953 => 853,  1950 => 852,  1944 => 851,  1942 => 850,  1937 => 849,  1932 => 847,  1929 => 846,  1925 => 844,  1920 => 841,  1917 => 840,  1911 => 839,  1909 => 838,  1904 => 837,  1899 => 835,  1897 => 834,  1890 => 833,  1886 => 831,  1881 => 828,  1878 => 827,  1872 => 826,  1870 => 825,  1865 => 824,  1860 => 822,  1858 => 821,  1853 => 820,  1849 => 818,  1844 => 815,  1841 => 814,  1835 => 813,  1833 => 812,  1828 => 811,  1823 => 809,  1820 => 808,  1816 => 806,  1811 => 803,  1808 => 802,  1802 => 801,  1800 => 800,  1795 => 799,  1790 => 797,  1788 => 796,  1782 => 793,  1770 => 792,  1768 => 791,  1766 => 790,  1763 => 789,  1760 => 788,  1756 => 785,  1752 => 782,  1748 => 780,  1743 => 777,  1740 => 776,  1735 => 774,  1732 => 773,  1728 => 771,  1723 => 768,  1720 => 767,  1715 => 765,  1713 => 764,  1709 => 762,  1703 => 761,  1701 => 760,  1696 => 759,  1692 => 757,  1679 => 756,  1677 => 755,  1675 => 754,  1672 => 753,  1669 => 752,  1665 => 749,  1657 => 743,  1651 => 739,  1645 => 738,  1643 => 737,  1638 => 736,  1634 => 734,  1623 => 733,  1621 => 732,  1619 => 731,  1616 => 730,  1613 => 729,  1609 => 726,  1601 => 720,  1595 => 716,  1589 => 715,  1587 => 714,  1582 => 713,  1578 => 711,  1567 => 710,  1565 => 709,  1563 => 708,  1560 => 707,  1557 => 706,  1553 => 703,  1545 => 697,  1539 => 693,  1533 => 692,  1531 => 691,  1526 => 690,  1522 => 688,  1511 => 687,  1509 => 686,  1507 => 685,  1504 => 684,  1501 => 683,  1497 => 680,  1489 => 674,  1483 => 670,  1477 => 669,  1475 => 668,  1470 => 667,  1466 => 665,  1455 => 664,  1453 => 663,  1451 => 662,  1448 => 661,  1445 => 660,  1441 => 657,  1433 => 651,  1427 => 647,  1421 => 646,  1419 => 645,  1414 => 644,  1410 => 642,  1399 => 641,  1397 => 640,  1395 => 639,  1392 => 638,  1389 => 637,  1385 => 634,  1377 => 628,  1371 => 624,  1365 => 623,  1363 => 622,  1358 => 621,  1354 => 619,  1343 => 618,  1341 => 617,  1339 => 616,  1336 => 615,  1333 => 614,  1329 => 611,  1325 => 608,  1321 => 606,  1316 => 603,  1313 => 602,  1308 => 600,  1305 => 599,  1301 => 597,  1296 => 594,  1293 => 593,  1288 => 591,  1286 => 590,  1282 => 588,  1276 => 587,  1274 => 586,  1269 => 585,  1265 => 583,  1254 => 582,  1252 => 581,  1250 => 580,  1247 => 579,  1235 => 569,  1231 => 568,  1227 => 567,  1221 => 564,  1217 => 563,  1213 => 562,  1207 => 559,  1203 => 558,  1199 => 557,  1193 => 554,  1189 => 553,  1185 => 552,  1179 => 549,  1175 => 548,  1171 => 547,  1165 => 544,  1161 => 543,  1157 => 542,  1149 => 539,  1140 => 533,  1136 => 532,  1132 => 531,  1126 => 528,  1122 => 527,  1118 => 526,  1112 => 523,  1108 => 522,  1104 => 521,  1098 => 518,  1094 => 517,  1090 => 516,  1086 => 515,  1080 => 512,  1076 => 511,  1072 => 510,  1066 => 507,  1062 => 506,  1058 => 505,  1050 => 502,  1041 => 496,  1037 => 495,  1033 => 494,  1027 => 491,  1023 => 490,  1019 => 489,  1013 => 486,  1009 => 485,  1005 => 484,  999 => 481,  995 => 480,  991 => 479,  985 => 476,  981 => 475,  977 => 474,  971 => 471,  967 => 470,  963 => 469,  955 => 466,  946 => 460,  942 => 459,  938 => 458,  932 => 455,  928 => 454,  924 => 453,  918 => 450,  914 => 449,  910 => 448,  904 => 445,  900 => 444,  896 => 443,  890 => 440,  886 => 439,  882 => 438,  876 => 435,  872 => 434,  868 => 433,  860 => 430,  851 => 424,  847 => 423,  843 => 422,  837 => 419,  833 => 418,  829 => 417,  823 => 414,  819 => 413,  815 => 412,  809 => 409,  805 => 408,  801 => 407,  795 => 404,  791 => 403,  787 => 402,  781 => 399,  777 => 398,  773 => 397,  765 => 394,  756 => 388,  752 => 387,  748 => 386,  742 => 383,  738 => 382,  734 => 381,  728 => 378,  724 => 377,  720 => 376,  714 => 373,  710 => 372,  706 => 371,  700 => 368,  696 => 367,  692 => 366,  686 => 363,  682 => 362,  678 => 361,  670 => 358,  661 => 352,  657 => 351,  653 => 350,  647 => 347,  643 => 346,  639 => 345,  633 => 342,  629 => 341,  625 => 340,  619 => 337,  615 => 336,  611 => 335,  605 => 332,  601 => 331,  597 => 330,  591 => 327,  587 => 326,  583 => 325,  575 => 322,  556 => 305,  553 => 304,  550 => 303,  547 => 302,  544 => 301,  541 => 300,  538 => 299,  535 => 298,  532 => 296,  529 => 295,  525 => 292,  517 => 286,  511 => 282,  505 => 281,  503 => 280,  498 => 279,  494 => 277,  483 => 276,  481 => 275,  479 => 274,  476 => 273,  473 => 272,  469 => 269,  461 => 263,  455 => 259,  449 => 258,  447 => 257,  442 => 256,  438 => 254,  429 => 253,  427 => 252,  425 => 251,  422 => 250,  420 => 248,  417 => 247,  408 => 241,  402 => 237,  400 => 236,  397 => 235,  394 => 234,  390 => 231,  382 => 225,  376 => 221,  372 => 219,  363 => 218,  361 => 217,  359 => 216,  356 => 215,  353 => 214,  349 => 211,  346 => 210,  342 => 207,  338 => 204,  334 => 202,  329 => 199,  326 => 198,  321 => 196,  318 => 195,  314 => 193,  309 => 190,  306 => 189,  301 => 187,  298 => 186,  294 => 184,  289 => 181,  286 => 180,  281 => 178,  279 => 177,  275 => 175,  271 => 173,  260 => 172,  258 => 171,  256 => 170,  253 => 169,  250 => 168,  246 => 165,  242 => 162,  238 => 160,  233 => 157,  230 => 156,  225 => 154,  222 => 153,  218 => 151,  213 => 148,  210 => 147,  205 => 145,  203 => 144,  199 => 142,  193 => 141,  191 => 140,  186 => 139,  182 => 137,  175 => 136,  173 => 135,  171 => 134,  168 => 133,  165 => 132,  161 => 129,  157 => 126,  153 => 124,  148 => 121,  145 => 120,  140 => 118,  137 => 117,  133 => 115,  128 => 112,  125 => 111,  120 => 109,  118 => 108,  114 => 106,  108 => 105,  106 => 104,  101 => 103,  97 => 101,  90 => 100,  88 => 99,  86 => 98,  82 => 96,  80 => 95,  78 => 94,  74 => 92,  71 => 90,  67 => 87,  64 => 85,  61 => 83,  55 => 79,  51 => 77,  44 => 75,  42 => 74,  40 => 73,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/businessplus_lite/templates/page--front.html.twig", "/home/rm520fzqbykb/public_html/ejobads.com/themes/businessplus_lite/templates/page--front.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 73, "block" => 248, "set" => 298);
        static $filters = array("escape" => 75, "raw" => 218, "date" => 322);
        static $functions = array("date" => 298);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'block', 'set'],
                ['escape', 'raw', 'date'],
                ['date']
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
